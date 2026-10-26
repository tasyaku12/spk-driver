<!doctype html>
<html lang="id"><head><meta charset="utf-8">
<style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color:#1e293b; }
    h2 { margin:0; color:#0b1b2b; } .sub { color:#64748b; margin-bottom:14px; }
    table { width:100%; border-collapse:collapse; margin-bottom:16px; }
    th { background:#0b1b2b; color:#fff; padding:7px; text-align:left; font-size:10px; }
    td { padding:6px 7px; border-bottom:1px solid #e2e8f0; }
    .c { text-align:center; } .r { text-align:right; } .top1 td { background:#fef3c7; font-weight:bold; }
    .ttd { margin-top:40px; width:100%; } .ttd td { border:0; text-align:center; }
</style></head><body>
    <h2>Laporan Pemilihan Driver Terbaik</h2>
    <div class="sub">PT Blitz Electric Mobility · Metode Simple Additive Weighting (SAW)<br>
        Periode: <strong>{{ \App\Support\Periode::label($periode) }}</strong> · Dicetak: {{ now()->format('d-m-Y H:i') }}</div>

    <strong>Kriteria &amp; Bobot</strong>
    <table>
        <tr><th>Kode</th><th>Kriteria</th><th>Satuan</th><th class="c">Tipe</th><th class="r">Bobot</th></tr>
        @foreach($criterias as $c)
            <tr><td>{{ $c->kode }}</td><td>{{ $c->nama }}</td><td>{{ $c->satuan }}</td><td class="c">{{ ucfirst($c->tipe) }}</td><td class="r">{{ $c->bobot }}</td></tr>
        @endforeach
    </table>

    <strong>Hasil Perankingan</strong>
    <table>
        <tr><th class="c">Rank</th><th>Kode</th><th>Nama Driver</th>
            @foreach($criterias as $c)<th class="r">{{ $c->kode }}</th>@endforeach<th class="r">Nilai V</th></tr>
        @foreach($hasil as $h)
            <tr class="{{ $h->ranking == 1 ? 'top1' : '' }}">
                <td class="c">{{ $h->ranking }}</td><td>{{ $h->driver->kode }}</td><td>{{ $h->driver->nama }}</td>
                @foreach($criterias as $c)<td class="r">{{ $nilai[$h->driver_id.'-'.$c->id] ?? '-' }}</td>@endforeach
                <td class="r">{{ number_format($h->nilai_preferensi, 4) }}</td></tr>
        @endforeach
    </table>

    <p>Berdasarkan perhitungan SAW, driver terbaik periode {{ \App\Support\Periode::label($periode) }} adalah
        <strong>{{ $hasil->first()->driver->nama }}</strong> dengan nilai preferensi {{ number_format($hasil->first()->nilai_preferensi, 4) }}.</p>

    <table class="ttd"><tr><td></td><td>Mengetahui,<br><br><br><br>( ........................ )<br>Manajer Operasional</td></tr></table>
</body></html>
