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

        Hasil::where('periode', $periode)->delete();   // buang hasil lama agar tidak basi
        if ($criterias->isEmpty() || empty($matrix)) return $result;

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
            foreach ($criterias as $c) $v += ($c->bobot / $totalBobot) * $rs[$c->id];
            $pref[$did] = round($v, 4);
        }
        arsort($pref);

        // 4. Ranking (skor sama => ranking sama) + simpan
        $ranks = []; $pos = 0; $rank = 0; $prev = null;
        foreach ($pref as $did => $v) {
            $pos++;
            if ($prev === null || $v != $prev) $rank = $pos;
            $prev = $v;
            $ranks[$did] = $rank;
            Hasil::create(['driver_id' => $did, 'periode' => $periode, 'nilai_preferensi' => $v, 'ranking' => $rank]);
        }

        $result['norm']  = $norm;
        $result['pref']  = $pref;
        $result['ranks'] = $ranks;
        return $result;
    }
}
