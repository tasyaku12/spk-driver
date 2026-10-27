<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color:#1e293b; }
    h2 { margin:0; color:#0b1b2b; }
    .sub { color:#64748b; margin-bottom:14px; }
    table { width:100%; border-collapse:collapse; margin-bottom:14px; }
    th { background:#0b1b2b; color:#fff; padding:7px; text-align:left; font-size:10px; }
    td { padding:6px 7px; border-bottom:1px solid #e2e8f0; }
    .c { text-align:center; } .r { text-align:right; }
    .box { border:1px solid #e2e8f0; border-radius:8px; padding:10px 12px; margin-bottom:12px; }
    .warn { background:#fff7ed; }
    .ok { background:#ecfdf5; }
</style>
</head>
<body>
    <h2>Ringkasan Kinerja Driver</h2>
    <div class="sub">
        Sistem Pendukung Keputusan Pemilihan Driver Terbaik · Metode SAW<br>
        PT Blitz Electric Mobility<br>
        Periode: <strong>{{ \App\Support\Periode::label($periode) }}</strong>
        · Dicetak: {{ now()->format('d-m-Y H:i') }}
    </div>

    <div class="box">
        <strong>{{ $driver->nama }}</strong> ({{ $driver->kode }})<br>
        Wilayah: {{ $driver->wilayah ?? '-' }} · HP: {{ $driver->no_hp ?? '-' }}<br>
        Peringkat: <strong>#{{ $hasilDriver->ranking }}</strong> dari {{ $totalEvaluasi }} driver
        · Skor V: <strong>{{ number_format($hasilDriver->nilai_preferensi, 4) }}</strong>
    </div>

    @if($rincian)
        <strong>Rincian SAW (mentah &rarr; normalisasi R &rarr; kontribusi bobot)</strong>
        <table>
            <tr>
                <th>Kode</th>
                <th>Kriteria</th>
                <th class="c">Tipe</th>
                <th class="r">Mentah</th>
                <th class="r">R</th>
                <th class="r">Bobot</th>
                <th class="r">Kontribusi</th>
            </tr>
            @foreach($rincian['rows'] as $row)
                @php $c = $row['criteria']; @endphp
                <tr>
                    <td>{{ $c->kode }}</td>
                    <td>{{ $c->nama }}</td>
                    <td class="c">{{ ucfirst($c->tipe) }}</td>
                    <td class="r">{{ $row['mentah'] }} {{ $c->satuan }}</td>
                    <td class="r">{{ number_format($row['normalisasi'], 4) }}</td>
                    <td class="r">{{ number_format($row['bobot'], 2) }}</td>
                    <td class="r">{{ number_format($row['kontribusi'], 4) }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="6"><strong>Total nilai preferensi (V)</strong></td>
                <td class="r"><strong>{{ number_format($rincian['v'], 4) }}</strong></td>
            </tr>
        </table>

        <strong>Saran perbaikan vs juara 1</strong>
        @if($rincian['is_juara1'])
            <div class="box ok">Anda driver terbaik periode ini. Pertahankan kinerja pada seluruh kriteria.</div>
        @elseif(!empty($rincian['saran_utama']))
            <div class="box warn">
                Juara 1: {{ $rincian['juara1']->nama ?? '-' }}
                (V {{ number_format($rincian['v_juara1'] ?? 0, 4) }})
                <ul>
                    @foreach($rincian['saran_utama'] as $s)
                        <li>{{ $s['saran'] }}</li>
                    @endforeach
                </ul>
            </div>
        @else
            <div class="box">Capaian sudah dekat dengan juara 1 pada semua kriteria.</div>
        @endif
    @endif

    <strong>Riwayat evaluasi</strong>
    <table>
        <tr><th>Periode</th><th class="r">Skor V</th><th class="c">Peringkat</th></tr>
        @forelse($riwayat as $rw)
            <tr>
                <td>{{ \App\Support\Periode::label($rw->periode) }}</td>
                <td class="r">{{ number_format($rw->nilai_preferensi, 4) }}</td>
                <td class="c">#{{ $rw->ranking }}</td>
            </tr>
        @empty
            <tr><td colspan="3" class="c">Belum ada riwayat.</td></tr>
        @endforelse
    </table>

    <p style="color:#64748b;font-size:10px;">Dokumen ini ringkasan untuk driver yang dinilai. Laporan resmi seluruh armada diterbitkan oleh Admin.</p>
</body>
</html>
