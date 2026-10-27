<?php
namespace App\Services;

use App\Models\Criteria;
use App\Models\Driver;
use App\Models\Hasil;
use App\Models\Nilai;

class SawService
{
    /** Hitung SAW untuk satu periode (Y-m) dan simpan ranking ke tabel hasils. */
    public function hitung(string $periode): array
    {
        $result = $this->compute($periode);

        Hasil::where('periode', $periode)->delete();   // buang hasil lama agar tidak basi
        foreach ($result['pref'] as $did => $v) {
            Hasil::create([
                'driver_id'         => $did,
                'periode'           => $periode,
                'nilai_preferensi'  => $v,
                'ranking'           => $result['ranks'][$did],
            ]);
        }

        return $result;
    }

    /**
     * Rincian SAW satu driver: nilai mentah, normalisasi, kontribusi bobot, dan saran vs juara 1.
     * Tidak menulis database (hanya membaca matriks yang sama dengan perhitungan admin).
     */
    public function rincianDriver(int $driverId, string $periode): ?array
    {
        $saw = $this->compute($periode);
        if (!isset($saw['matrix'][$driverId], $saw['norm'][$driverId], $saw['pref'][$driverId])) {
            return null;
        }

        $juara1Id = null;
        foreach ($saw['ranks'] as $did => $rank) {
            if ((int) $rank === 1) {
                $juara1Id = (int) $did;
                break;
            }
        }

        $juara1 = $juara1Id ? ($saw['drivers'][$juara1Id] ?? null) : null;
        $isJuara1 = $juara1Id === $driverId;
        $rows = [];
        $saranUtama = [];

        foreach ($saw['criterias'] as $c) {
            $bobotN = $c->bobot / $saw['totalBobot'];
            $mentah = (float) $saw['matrix'][$driverId][$c->id];
            $r = (float) $saw['norm'][$driverId][$c->id];
            $kontribusi = $bobotN * $r;
            $mentahJuara = ($juara1Id && isset($saw['matrix'][$juara1Id][$c->id]))
                ? (float) $saw['matrix'][$juara1Id][$c->id]
                : null;

            $status = 'setara';
            $saran = 'Kinerja pada kriteria ini setara dengan juara 1. Pertahankan.';
            $gap = 0.0;

            if ($isJuara1) {
                $status = 'unggul';
                $saran = 'Anda unggul di kriteria ini. Pertahankan capaiannya.';
            } elseif ($mentahJuara !== null) {
                if ($c->tipe === 'benefit') {
                    $gap = $mentahJuara - $mentah;
                    if ($gap > 0.0001) {
                        $status = 'perlu_perbaikan';
                        $saran = 'Naikkan '.$c->nama.': Anda '.$this->fmtAngka($mentah).' '.$c->satuan
                            .', juara 1 '.$this->fmtAngka($mentahJuara).' '.$c->satuan.'.';
                    } elseif ($gap < -0.0001) {
                        $status = 'unggul';
                        $saran = 'Capaian '.$c->nama.' Anda lebih tinggi dari juara 1. Pertahankan.';
                        $gap = abs($gap);
                    } else {
                        $gap = 0.0;
                    }
                } else {
                    $gap = $mentah - $mentahJuara;
                    if ($gap > 0.0001) {
                        $status = 'perlu_perbaikan';
                        $saran = 'Turunkan '.$c->nama.': Anda '.$this->fmtAngka($mentah).' '.$c->satuan
                            .', juara 1 '.$this->fmtAngka($mentahJuara).' '.$c->satuan.'.';
                    } elseif ($gap < -0.0001) {
                        $status = 'unggul';
                        $saran = 'Capaian '.$c->nama.' Anda lebih baik (lebih rendah) dari juara 1. Pertahankan.';
                        $gap = abs($gap);
                    } else {
                        $gap = 0.0;
                    }
                }
            }

            $row = [
                'criteria'      => $c,
                'mentah'        => $mentah,
                'normalisasi'   => $r,
                'bobot'         => (float) $c->bobot,
                'bobot_normal'  => $bobotN,
                'kontribusi'    => $kontribusi,
                'mentah_juara1' => $mentahJuara,
                'gap'           => $gap,
                'status'        => $status,
                'saran'         => $saran,
            ];
            $rows[] = $row;
            if ($status === 'perlu_perbaikan') {
                $saranUtama[] = $row;
            }
        }

        usort($saranUtama, fn ($a, $b) => $b['gap'] <=> $a['gap']);

        return [
            'rows'        => $rows,
            'saran_utama' => array_slice($saranUtama, 0, 3),
            'juara1'      => $juara1,
            'is_juara1'   => $isJuara1,
            'v'           => (float) $saw['pref'][$driverId],
            'v_juara1'    => $juara1Id ? (float) ($saw['pref'][$juara1Id] ?? 0) : null,
            'criterias'   => $saw['criterias'],
        ];
    }

    /** Perhitungan SAW tanpa menulis tabel hasils (rumus sama dengan hitung()). */
    private function compute(string $periode): array
    {
        $criterias  = Criteria::orderBy('kode')->get();
        $totalBobot = $criterias->sum('bobot') ?: 1;   // bobot dinormalisasi otomatis bila total != 1
        $semua      = Driver::orderBy('kode')->get();

        // 1. Matriks keputusan: hanya driver yang nilainya lengkap untuk SEMUA kriteria
        $matrix = [];
        foreach (Nilai::where('periode', $periode)->get()->groupBy('driver_id') as $driverId => $items) {
            $vals = $items->pluck('nilai', 'criteria_id');
            if ($criterias->every(fn ($c) => $vals->has($c->id))) {
                $matrix[$driverId] = $vals->map(fn ($v) => (float) $v)->all();
            }
        }

        $result = [
            'criterias'    => $criterias,
            'drivers'      => $semua->keyBy('id'),
            'matrix'       => $matrix,
            'norm'         => [],
            'pref'         => [],
            'ranks'        => [],
            'totalBobot'   => $totalBobot,
            'belumLengkap' => $semua->whereNotIn('id', array_keys($matrix))->values(),
        ];

        if ($criterias->isEmpty() || empty($matrix)) {
            return $result;
        }

        // 2. Normalisasi
        $norm = [];
        foreach ($criterias as $c) {
            $col = array_column($matrix, $c->id);
            $max = max($col);
            $min = min($col);
            foreach ($matrix as $did => $vals) {
                $x = $vals[$c->id];
                if ($c->tipe === 'benefit') {
                    $norm[$did][$c->id] = $max > 0 ? $x / $max : 0;
                } else {
                    // Tipe cost: semakin kecil x semakin baik.
                    // Jika min = 0, gunakan ($min + 1) / ($x + 1) agar driver dengan nilai 0 (komplain/gagal) mendapat skor sempurna 1.0
                    $norm[$did][$c->id] = $min == 0
                        ? ($min + 1) / ($x + 1)
                        : ($x > 0 ? $min / $x : 1.0);
                }
            }
        }

        // 3. Nilai preferensi
        $pref = [];
        foreach ($norm as $did => $rs) {
            $v = 0;
            foreach ($criterias as $c) {
                $v += ($c->bobot / $totalBobot) * $rs[$c->id];
            }
            $pref[$did] = round($v, 4);
        }
        arsort($pref);

        // 4. Ranking (skor sama => ranking sama)
        $ranks = [];
        $pos = 0;
        $rank = 0;
        $prev = null;
        foreach ($pref as $did => $v) {
            $pos++;
            if ($prev === null || $v != $prev) {
                $rank = $pos;
            }
            $prev = $v;
            $ranks[$did] = $rank;
        }

        $result['norm']  = $norm;
        $result['pref']  = $pref;
        $result['ranks'] = $ranks;

        return $result;
    }

    private function fmtAngka(float $v): string
    {
        return abs($v - round($v)) < 0.0001
            ? number_format($v, 0)
            : rtrim(rtrim(number_format($v, 2, '.', ''), '0'), '.');
    }
}
