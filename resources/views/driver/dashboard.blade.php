@extends('layouts.driver')
@section('title', 'Performa Saya - ' . $driver->nama)

@php
    $fmt = function ($v) {
        $n = (float) $v;
        return abs($n - round($n)) < 0.0001
            ? number_format($n, 0)
            : rtrim(rtrim(number_format($n, 2, '.', ''), '0'), '.');
    };
    $jmlKriteria = $nilais->count();
@endphp

@section('content')
<!-- Filter Periode & Greeting -->
<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <div class="text-muted small">Selamat Datang,</div>
        <h5 class="fw-bold mb-0 text-dark">{{ $driver->nama }}</h5>
    </div>
    <form method="GET" action="{{ route('driver.dashboard') }}">
        <select name="periode" class="form-select form-select-sm rounded-pill fw-semibold shadow-sm border-0" onchange="this.form.submit()" style="background-color: #fff;">
            @foreach($periods as $p)
                <option value="{{ $p }}" {{ $periode === $p ? 'selected' : '' }}>
                    {{ \Carbon\Carbon::parse($p)->translatedFormat('M Y') }}
                </option>
            @endforeach
        </select>
    </form>
</div>

<!-- Hero Card: Status Peringkat & Skor SAW Driver -->
<div class="driver-hero-card">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <span class="badge bg-white text-dark rounded-pill px-2 py-1 small fw-bold">
            <i class="bi bi-person-badge text-primary me-1"></i>{{ $driver->kode }}
        </span>
        <span class="small text-white-50">
            <i class="bi bi-geo-alt-fill text-warning me-1"></i>{{ $driver->wilayah ?? 'Hub Jakarta' }}
        </span>
    </div>

    @if($hasilDriver)
        <div class="d-flex align-items-center gap-3">
            <div style="width: 58px; height: 58px; background: #fff; border-radius: 16px; display: grid; place-items: center; color: var(--navy); font-size: 1.5rem; font-weight: 900; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                @if($hasilDriver->ranking == 1) 🥇
                @elseif($hasilDriver->ranking == 2) 🥈
                @elseif($hasilDriver->ranking == 3) 🥉
                @else #{{ $hasilDriver->ranking }}
                @endif
            </div>
            <div class="flex-grow-1">
                <div class="small text-white-50 d-flex align-items-center gap-1" style="line-height: 1;">
                    <span>Peringkat SAW Anda</span>
                    <span class="info-popover-wrapper">
                        <span class="info-popover-trigger sm" tabindex="0" role="button">!</span>
                        <div class="info-popover-content shadow-lg popover-start" style="width:260px;">
                            <div class="fw-bold text-dark small mb-1">Peringkat &amp; Skor SAW</div>
                            <div class="text-secondary small" style="font-size:0.75rem;">
                                Peringkat dihitung dengan metode SAW dari {{ $jmlKriteria ?: 6 }} kriteria: 4 benefit (ketepatan waktu, jumlah pengiriman, absensi, rating) dan 2 cost (paket gagal, komplain).
                            </div>
                        </div>
                    </span>
                </div>
                <div class="fs-5 fw-bold text-white mt-1">
                    Juara #{{ $hasilDriver->ranking }} <span class="fs-6 fw-normal text-white-50">dari {{ $totalEvaluasi }} Driver</span>
                </div>
                <div class="small mt-1" style="color: var(--accent);">
                    Skor Preferensi (V): <strong>{{ number_format($hasilDriver->nilai_preferensi, 4) }}</strong>
                </div>
            </div>
        </div>
        <a href="{{ route('driver.pdf', ['periode' => $periode]) }}" class="btn btn-light btn-sm rounded-pill fw-semibold mt-3 w-100">
            <i class="bi bi-file-earmark-pdf text-danger me-1"></i> Unduh ringkasan kinerja PDF
        </a>
    @else
        <div class="text-center py-2 text-warning small">
            <i class="bi bi-info-circle me-1"></i> Penilaian periode ini belum dikalkulasi oleh Admin.
        </div>
    @endif
</div>

<!-- Kriteria SPK SAW -->
<div class="d-flex align-items-center justify-content-between mb-2">
    <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-1">
        <i class="bi bi-clipboard-data text-primary me-1"></i>
        <span>Kriteria Penilaian SAW</span>
        <span class="info-popover-wrapper ms-1">
            <span class="info-popover-trigger" tabindex="0" role="button">!</span>
            <div class="info-popover-content shadow-lg popover-start" style="width:270px;">
                <div class="fw-bold text-dark small mb-1">Enam Kriteria Evaluasi</div>
                <div class="text-secondary small" style="font-size:0.75rem;">
                    <strong>Benefit</strong> (semakin tinggi semakin baik): C1 ketepatan waktu, C2 jumlah pengiriman, C3 absensi, C4 rating pelanggan.<br>
                    <strong>Cost</strong> (semakin rendah semakin baik): C5 paket gagal, C6 komplain.
                </div>
            </div>
        </span>
    </h6>
    <span class="badge bg-light text-muted small border">{{ $jmlKriteria }} Kriteria</span>
</div>

<div class="row g-2 mb-3">
    @forelse($nilais as $n)
        <div class="col-6">
            <div class="card-app p-3 text-center h-100">
                <span class="badge {{ $n->criteria->tipe === 'cost' ? 'bg-danger-subtle text-danger' : 'bg-light text-primary' }} border rounded-pill mb-2 px-2 py-1" style="font-size: 0.65rem;">
                    {{ $n->criteria->kode }} · {{ (int) round($n->criteria->bobot * 100) }}% · {{ $n->criteria->tipe }}
                </span>
                <div class="text-muted text-truncate mb-1" style="font-size: 0.72rem;">{{ $n->criteria->nama }}</div>
                <div class="fs-5 fw-bold text-dark">{{ $fmt($n->nilai) }}</div>
                <div class="text-muted" style="font-size: 0.68rem;">{{ $n->criteria->satuan }}</div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card-app text-center py-3 text-muted small">
                Belum ada rincian nilai untuk periode ini.
            </div>
        </div>
    @endforelse
</div>

@if($rincian)
<!-- Rincian langkah SAW -->
<div class="card-app mb-3">
    <h6 class="fw-bold mb-1 text-dark d-flex align-items-center gap-1">
        <i class="bi bi-calculator text-primary me-1"></i>Rincian Skor SAW Anda
    </h6>
    <p class="text-muted small mb-3">Nilai mentah dinormalisasi, lalu dikalikan bobot. Jumlah kontribusi = skor V.</p>
    <div class="table-responsive">
        <table class="table table-sm align-middle mb-0" style="font-size: 0.75rem;">
            <thead class="table-light">
                <tr class="text-muted">
                    <th>Kriteria</th>
                    <th class="text-end">Mentah</th>
                    <th class="text-end">R</th>
                    <th class="text-end">Kontribusi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rincian['rows'] as $row)
                    @php $c = $row['criteria']; @endphp
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $c->kode }} · {{ $c->nama }}</div>
                            <div class="progress mt-1" style="height: 5px;">
                                <div class="progress-bar {{ $c->tipe === 'cost' ? 'bg-danger' : 'bg-primary' }}"
                                     style="width: {{ min(100, $row['normalisasi'] * 100) }}%"></div>
                            </div>
                        </td>
                        <td class="text-end">{{ $fmt($row['mentah']) }}</td>
                        <td class="text-end">{{ number_format($row['normalisasi'], 3) }}</td>
                        <td class="text-end fw-semibold">{{ number_format($row['kontribusi'], 4) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="fw-bold">
                    <td colspan="3">Nilai preferensi (V)</td>
                    <td class="text-end text-primary">{{ number_format($rincian['v'], 4) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<!-- Saran vs juara 1 -->
<div class="card-app mb-3">
    <h6 class="fw-bold mb-2 text-dark">
        <i class="bi bi-lightbulb-fill text-warning me-1"></i>Saran vs Juara 1
    </h6>
    @if($rincian['is_juara1'])
        <div class="alert alert-success py-2 px-3 small mb-0 rounded-4">
            Anda driver terbaik periode ini. Pertahankan seluruh kriteria, terutama yang bobotnya besar.
        </div>
    @elseif($rincian['juara1'])
        <p class="small text-muted mb-2">
            Juara 1: <strong>{{ $rincian['juara1']->nama }}</strong>
            (V {{ number_format($rincian['v_juara1'], 4) }}).
            Selisih skor Anda: {{ number_format($rincian['v_juara1'] - $rincian['v'], 4) }}.
        </p>
        @forelse($rincian['saran_utama'] as $s)
            <div class="d-flex gap-2 align-items-start mb-2 p-2 rounded-3" style="background:#fff7ed;">
                <i class="bi bi-arrow-up-right text-warning mt-1"></i>
                <div class="small mb-0">{{ $s['saran'] }}</div>
            </div>
        @empty
            <div class="small text-muted mb-0">Capaian Anda sudah dekat dengan juara 1 di semua kriteria.</div>
        @endforelse
        @if($rincian['saran_utama'])
            <p class="text-muted mb-0 mt-2" style="font-size:0.7rem;">Fokus ke celah terbesar lebih dulu agar skor V naik paling cepat.</p>
        @endif
    @endif
</div>
@endif

<!-- Tren pribadi -->
@if($tren->count())
<div class="card-app mb-3">
    <h6 class="fw-bold mb-2 text-dark"><i class="bi bi-graph-up-arrow text-success me-1"></i>Tren Performa Anda</h6>
    @if($tren->count() === 1)
        <p class="small text-muted mb-0">Baru ada 1 periode. Grafik akan memanjang setelah admin menghitung periode berikutnya.</p>
    @else
        <canvas id="trenDriverChart" height="160"></canvas>
    @endif
</div>
@endif

<!-- Podium Mini Top 3 Driver Bulan Ini -->
<div class="card-app mb-3">
    <div class="d-flex align-items-center justify-content-between mb-2">
        <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-trophy-fill text-warning me-1"></i>Top 3 Driver Bulan Ini</h6>
        <a href="{{ route('driver.leaderboard', ['periode' => $periode]) }}" class="small text-decoration-none fw-semibold">Lihat Semua &rarr;</a>
    </div>

    @if($top3->count() >= 3)
        <div class="podium-box">
            <!-- Juara 2 -->
            <div class="p-col p2">
                <div class="medal">🥈</div>
                <div class="name">{{ $top3[1]->driver->nama }}</div>
                <div class="score">V: {{ number_format($top3[1]->nilai_preferensi, 4) }}</div>
                <span class="badge bg-white text-dark mt-2 py-1 px-2 rounded-pill" style="font-size: 0.65rem;">#2</span>
            </div>
            <!-- Juara 1 -->
            <div class="p-col p1">
                <div class="medal">🥇</div>
                <div class="name">{{ $top3[0]->driver->nama }}</div>
                <div class="score">V: {{ number_format($top3[0]->nilai_preferensi, 4) }}</div>
                <span class="badge bg-white text-dark mt-2 py-1 px-2 rounded-pill" style="font-size: 0.65rem;">#1 Terbaik</span>
            </div>
            <!-- Juara 3 -->
            <div class="p-col p3">
                <div class="medal">🥉</div>
                <div class="name">{{ $top3[2]->driver->nama }}</div>
                <div class="score">V: {{ number_format($top3[2]->nilai_preferensi, 4) }}</div>
                <span class="badge bg-white text-dark mt-2 py-1 px-2 rounded-pill" style="font-size: 0.65rem;">#3</span>
            </div>
        </div>
    @else
        <div class="text-center py-3 text-muted small">
            Data ranking periode ini belum lengkap.
        </div>
    @endif
</div>

<!-- Riwayat Evaluasi Driver -->
<div class="card-app mb-2">
    <h6 class="fw-bold mb-3 text-dark"><i class="bi bi-clock-history text-info me-1"></i>Riwayat Evaluasi Anda</h6>
    <div class="table-responsive">
        <table class="table table-sm table-hover align-middle mb-0" style="font-size: 0.82rem;">
            <thead class="table-light">
                <tr class="text-muted">
                    <th>Periode</th>
                    <th>Skor SAW</th>
                    <th class="text-end">Peringkat</th>
                </tr>
            </thead>
            <tbody>
                @forelse($riwayat as $rw)
                    <tr class="{{ $rw->periode === $periode ? 'table-primary fw-bold' : '' }}">
                        <td>
                            <i class="bi bi-calendar3 me-1 text-primary"></i>
                            {{ \Carbon\Carbon::parse($rw->periode)->translatedFormat('M Y') }}
                        </td>
                        <td>{{ number_format($rw->nilai_preferensi, 4) }}</td>
                        <td class="text-end">
                            <span class="badge {{ $rw->ranking <= 3 ? 'bg-warning text-dark' : 'bg-secondary' }} rounded-pill px-2 py-1">
                                #{{ $rw->ranking }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted py-3">Belum ada riwayat sebelumnya.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@if(isset($tren) && $tren->count() > 1)
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    const trenLabels = @json($tren->map(fn ($r) => \Carbon\Carbon::parse($r->periode)->translatedFormat('M Y'))->values());
    const trenSkor = @json($tren->pluck('nilai_preferensi')->map(fn ($v) => (float) $v)->values());
    const trenRank = @json($tren->pluck('ranking')->map(fn ($v) => (int) $v)->values());
    const ctx = document.getElementById('trenDriverChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: trenLabels,
                datasets: [{
                    label: 'Skor V',
                    data: trenSkor,
                    borderColor: '#00ADEF',
                    backgroundColor: 'rgba(0, 173, 239, 0.12)',
                    fill: true,
                    tension: 0.35,
                    pointRadius: 4,
                    pointBackgroundColor: '#0b1b2b'
                }]
            },
            options: {
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            afterLabel: (item) => 'Peringkat #' + trenRank[item.dataIndex]
                        }
                    }
                },
                scales: {
                    y: { min: 0, max: 1, ticks: { font: { size: 10 } } },
                    x: { ticks: { font: { size: 10 } } }
                }
            }
        });
    }
</script>
@endpush
@endif
