@extends('layouts.spk')
@section('title', 'Dashboard')
@section('subtitle', 'Sistem Pendukung Keputusan Pemilihan Driver Terbaik · Metode Simple Additive Weighting (SAW)')

@section('actions')
    @if($periods->count())
    <form method="GET" class="d-flex align-items-center gap-2">
        <div class="input-group input-group-sm shadow-xs rounded-3" style="min-width: 220px;">
            <span class="input-group-text bg-white border-end-0 text-primary">
                <i class="bi bi-calendar-event"></i>
            </span>
            <select name="periode" class="form-select border-start-0 ps-1 fw-semibold text-dark" onchange="this.form.submit()">
                @foreach($periods as $p)
                    <option value="{{ $p }}" @selected($p == $periode)>{{ \App\Support\Periode::label($p) }}</option>
                @endforeach
            </select>
        </div>
    </form>
    @endif
@endsection

@section('content')
<!-- KPI Summary Cards -->
<div class="row g-4 mb-4">
    <!-- Card 1: Jumlah Driver -->
    <div class="col-sm-6 col-xl-3">
        <div class="card-soft shadow-sm border-0 h-100" style="border-radius: 16px; padding: 18px 22px;">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px; font-size: 1.15rem;">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div>
                    <div class="text-muted small fw-medium">Mitra Driver</div>
                    <div class="fs-4 fw-bold text-dark lh-sm font-monospace">{{ $jmlDriver }} Driver</div>
                    <div class="text-muted small" style="font-size: 0.75rem;">Total armada terdaftar</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 2: Jumlah Kriteria -->
    <div class="col-sm-6 col-xl-3">
        <div class="card-soft shadow-sm border-0 h-100" style="border-radius: 16px; padding: 18px 22px;">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px; font-size: 1.15rem; background-color: #ede9fe; color: #7c3aed;">
                    <i class="bi bi-sliders2"></i>
                </div>
                <div>
                    <div class="text-muted small fw-medium">Kriteria Penilaian</div>
                    <div class="fs-4 fw-bold text-dark lh-sm font-monospace">{{ $jmlCriteria }} Kriteria</div>
                    <div class="text-muted small" style="font-size: 0.75rem;">Bobot ternormalisasi 100%</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 3: Skor Tertinggi -->
    <div class="col-sm-6 col-xl-3">
        <div class="card-soft shadow-sm border-0 h-100" style="border-radius: 16px; padding: 18px 22px;">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-3 bg-warning-subtle text-warning-emphasis d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px; font-size: 1.15rem;">
                    <i class="bi bi-trophy-fill"></i>
                </div>
                <div>
                    <div class="text-muted small fw-medium d-flex align-items-center">
                        <span>Skor Tertinggi</span>
                        <span class="info-popover-wrapper ms-2">
                            <span class="info-popover-trigger sm" tabindex="0" role="button">!</span>
                            <div class="info-popover-content shadow-lg popover-start" style="width:260px;">
                                <div class="fw-bold text-dark small mb-1">Skor Tertinggi (V)</div>
                                <div class="text-secondary small" style="font-size:0.75rem;">
                                    Nilai preferensi SAW tertinggi yang diraih oleh driver peringkat 1 pada periode aktif ini.
                                </div>
                            </div>
                        </span>
                    </div>
                    <div class="fs-4 fw-bold text-dark lh-sm font-monospace">
                        {{ $hasil->count() ? number_format($hasil->first()->nilai_preferensi, 4) : '-' }}
                    </div>
                    <div class="text-truncate text-secondary fw-medium" style="font-size: 0.75rem; max-width: 170px;" title="{{ $bestDriver->nama ?? '' }}">
                        {{ $bestDriver->nama ?? 'Belum ada data' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 4: Rata-rata Skor -->
    <div class="col-sm-6 col-xl-3">
        <div class="card-soft shadow-sm border-0 h-100" style="border-radius: 16px; padding: 18px 22px;">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-3 bg-success-subtle text-success d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px; font-size: 1.15rem;">
                    <i class="bi bi-bar-chart-fill"></i>
                </div>
                <div>
                    <div class="text-muted small fw-medium d-flex align-items-center">
                        <span>Rata-rata Skor</span>
                        <span class="info-popover-wrapper ms-2">
                            <span class="info-popover-trigger sm" tabindex="0" role="button">!</span>
                            <div class="info-popover-content shadow-lg popover-end" style="width:260px;">
                                <div class="fw-bold text-dark small mb-1">Rata-rata Nilai V</div>
                                <div class="text-secondary small" style="font-size:0.75rem;">
                                    Rata-rata nilai preferensi seluruh driver pada periode ini sebagai tolok ukur performa armada secara kolektif.
                                </div>
                            </div>
                        </span>
                    </div>
                    <div class="fs-4 fw-bold text-dark lh-sm font-monospace">
                        {{ $rataSkor ? number_format($rataSkor, 4) : '-' }}
                    </div>
                    <div class="text-muted small" style="font-size: 0.75rem;">Rerata seluruh driver</div>
                </div>
            </div>
        </div>
    </div>
</div>

@if($hasil->isEmpty())
    <div class="card-soft shadow-sm border-0 p-5 text-center my-4" style="border-radius: 18px;">
        <div class="empty py-4">
            <i class="bi bi-inbox text-muted opacity-50 mb-3" style="font-size: 3rem;"></i>
            <h5 class="fw-bold text-dark mt-2">Belum Ada Hasil Perankingan Periode Ini</h5>
            <p class="text-muted mb-4" style="max-width: 480px; margin: 0 auto;">
                Silakan isi data penilaian mitra driver terlebih dahulu lalu jalankan modul perhitungan SPK metode SAW.
            </p>
            <div class="d-flex align-items-center justify-content-center gap-2">
                <a href="{{ route('nilai.index') }}" class="btn btn-accent px-4 py-2 rounded-3">
                    <i class="bi bi-pencil-square me-1"></i> Isi Data Penilaian
                </a>
                <a href="{{ route('perhitungan.index') }}" class="btn btn-outline-secondary px-4 py-2 rounded-3">
                    <i class="bi bi-calculator me-1"></i> Hitung SAW
                </a>
            </div>
        </div>
    </div>
@else
    <!-- Baris 2: Podium Juara & Grafik Perbandingan Skor -->
    <div class="row g-4 mb-4">
        <!-- Kolom Podium Juara -->
        <div class="col-xxl-5 col-xl-5 col-12">
            <div class="card-soft shadow-sm border-0 h-100 d-flex flex-column" style="border-radius: 18px; min-height: 400px; overflow: hidden;">
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <span class="fs-5">🏆</span>
                        <h6 class="fw-bold mb-0 text-dark">Driver Terbaik &bull; {{ \App\Support\Periode::label($periode) }}</h6>
                    </div>
                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2.5 py-1 rounded-pill small">
                        Top 3 Peringkat
                    </span>
                </div>
                <div class="card-body p-3 p-sm-4 flex-grow-1 d-flex flex-column justify-content-end">
                    @php $t = $hasil->take(3); @endphp
                    
                    <div class="podium-arena d-flex align-items-end justify-content-center pt-2 pb-2" style="gap: 10px; width: 100%; min-height: 250px;">
                        <!-- Juara 2 -->
                        @if($t->get(1))
                        <div class="podium-pillar d-flex flex-column align-items-center text-center text-white" 
                             style="flex: 1 1 0; max-width: 125px; min-width: 80px; height: 175px; background: linear-gradient(180deg, #64748b 0%, #334155 100%); border-radius: 16px 16px 8px 8px; padding: 12px 6px 10px; box-shadow: 0 8px 20px rgba(51, 65, 85, 0.20); position: relative;">
                            <div class="badge rounded-circle bg-white text-secondary fw-bold shadow-xs d-flex align-items-center justify-content-center mb-1" style="width: 32px; height: 32px; font-size: 0.95rem;">
                                🥈
                            </div>
                            <div class="fw-bold text-truncate w-100 px-1 mt-1" style="font-size: 0.82rem;" title="{{ $t[1]->driver->nama }}">
                                {{ $t[1]->driver->nama }}
                            </div>
                            <span class="badge bg-white bg-opacity-25 text-white my-1 font-monospace" style="font-size: 0.68rem;">
                                {{ $t[1]->driver->kode }}
                            </span>
                            <div class="badge bg-white text-dark fw-bold font-monospace mt-auto px-2 py-1 shadow-xs" style="font-size: 0.78rem;">
                                {{ number_format($t[1]->nilai_preferensi, 4) }}
                            </div>
                        </div>
                        @endif

                        <!-- Juara 1 -->
                        @if($t->get(0))
                        <div class="podium-pillar d-flex flex-column align-items-center text-center text-white" 
                             style="flex: 1 1 0; max-width: 135px; min-width: 90px; height: 215px; background: linear-gradient(180deg, #f59e0b 0%, #b45309 100%); border-radius: 18px 18px 8px 8px; padding: 14px 8px 12px; box-shadow: 0 10px 24px rgba(245, 158, 11, 0.28); position: relative;">
                            <div class="badge rounded-circle bg-white text-warning fw-bold shadow-xs d-flex align-items-center justify-content-center mb-1" style="width: 36px; height: 36px; font-size: 1.1rem;">
                                🥇
                            </div>
                            <div class="fw-bold text-truncate w-100 px-1 mt-1" style="font-size: 0.88rem;" title="{{ $t[0]->driver->nama }}">
                                {{ $t[0]->driver->nama }}
                            </div>
                            <span class="badge bg-white bg-opacity-25 text-white my-1 font-monospace" style="font-size: 0.7rem;">
                                {{ $t[0]->driver->kode }}
                            </span>
                            <div class="badge bg-white text-dark fw-bold font-monospace mt-auto px-2 py-1 shadow-xs" style="font-size: 0.82rem;">
                                {{ number_format($t[0]->nilai_preferensi, 4) }}
                            </div>
                        </div>
                        @endif

                        <!-- Juara 3 -->
                        @if($t->get(2))
                        <div class="podium-pillar d-flex flex-column align-items-center text-center text-white" 
                             style="flex: 1 1 0; max-width: 125px; min-width: 80px; height: 150px; background: linear-gradient(180deg, #ea580c 0%, #9a3412 100%); border-radius: 16px 16px 8px 8px; padding: 12px 6px 10px; box-shadow: 0 8px 20px rgba(154, 52, 18, 0.20); position: relative;">
                            <div class="badge rounded-circle bg-white text-danger fw-bold shadow-xs d-flex align-items-center justify-content-center mb-1" style="width: 32px; height: 32px; font-size: 0.95rem;">
                                🥉
                            </div>
                            <div class="fw-bold text-truncate w-100 px-1 mt-1" style="font-size: 0.82rem;" title="{{ $t[2]->driver->nama }}">
                                {{ $t[2]->driver->nama }}
                            </div>
                            <span class="badge bg-white bg-opacity-25 text-white my-1 font-monospace" style="font-size: 0.68rem;">
                                {{ $t[2]->driver->kode }}
                            </span>
                            <div class="badge bg-white text-dark fw-bold font-monospace mt-auto px-2 py-1 shadow-xs" style="font-size: 0.78rem;">
                                {{ number_format($t[2]->nilai_preferensi, 4) }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Grafik Bar Perbandingan Skor -->
        <div class="col-xxl-7 col-xl-7 col-12">
            <div class="card-soft shadow-sm border-0 h-100" style="border-radius: 18px; min-height: 400px;">
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-primary-subtle text-primary p-2 rounded-3">
                            <i class="bi bi-bar-chart-fill fs-6"></i>
                        </span>
                        <h6 class="fw-bold mb-0 text-dark">Perbandingan Skor Seluruh Driver</h6>
                    </div>
                    <span class="badge bg-light text-secondary border px-2.5 py-1 rounded-pill small">
                        {{ $hasil->count() }} Driver Dinilai
                    </span>
                </div>
                <div class="card-body p-4 d-flex align-items-center">
                    <div style="position: relative; height: 290px; width: 100%;">
                        <canvas id="chartRank"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Baris 3: Tabel Peringkat Lengkap & Grafik Tren 5 Driver Teratas -->
    <div class="row g-4 mb-4">
        <!-- Kolom Tabel Peringkat Lengkap -->
        <div class="col-xxl-7 col-xl-7 col-12">
            <div class="card-soft shadow-sm border-0 h-100" style="border-radius: 18px;">
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-primary-subtle text-primary p-2 rounded-3">
                            <i class="bi bi-list-ol fs-6"></i>
                        </span>
                        <div>
                            <h6 class="fw-bold mb-0 text-dark">Peringkat Lengkap SAW</h6>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <a href="{{ route('laporan.pdf', ['periode'=>$periode]) }}" class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1.5 px-3 py-1.5 rounded-3 shadow-xs">
                            <i class="bi bi-file-earmark-pdf"></i>
                            <span>PDF</span>
                        </a>
                        <a href="{{ route('laporan.excel', ['periode'=>$periode]) }}" class="btn btn-sm btn-outline-success d-inline-flex align-items-center gap-1.5 px-3 py-1.5 rounded-3 shadow-xs">
                            <i class="bi bi-file-earmark-excel"></i>
                            <span>Excel</span>
                        </a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th width="100" class="py-3 px-3 text-center">Rank</th>
                                <th class="py-3 px-3">Mitra Driver</th>
                                <th class="py-3 px-3 text-end" style="min-width: 130px;">
                                    <div class="d-flex align-items-center justify-content-end gap-1">
                                        <span>Nilai V</span>
                                        <span class="info-popover-wrapper ms-1">
                                            <span class="info-popover-trigger sm" tabindex="0" role="button">!</span>
                                            <div class="info-popover-content shadow-lg popover-end" style="width:260px;">
                                                <div class="fw-bold text-dark small mb-1">Nilai Preferensi (V)</div>
                                                <div class="text-secondary small" style="font-size:0.75rem;">
                                                    Skor akhir hasil penjumlahan terbobot SAW (0 sampai 1). Semakin tinggi nilai V, semakin unggul peringkat driver.
                                                </div>
                                            </div>
                                        </span>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($hasil as $h)
                                <tr>
                                    <!-- Rank -->
                                    <td class="py-3 px-3 text-center">
                                        @if($h->ranking == 1)
                                            <span class="badge bg-warning text-dark fw-bold rounded-pill px-2.5 py-1 d-inline-flex align-items-center gap-1 shadow-xs" title="Juara 1">
                                                <i class="bi bi-trophy-fill"></i> 1
                                            </span>
                                        @elseif($h->ranking == 2)
                                            <span class="badge bg-secondary-subtle text-secondary-emphasis fw-bold rounded-pill px-2.5 py-1 border d-inline-flex align-items-center gap-1" title="Juara 2">
                                                <i class="bi bi-award"></i> 2
                                            </span>
                                        @elseif($h->ranking == 3)
                                            <span class="badge bg-danger-subtle text-danger-emphasis fw-bold rounded-pill px-2.5 py-1 border d-inline-flex align-items-center gap-1" title="Juara 3">
                                                <i class="bi bi-award-fill"></i> 3
                                            </span>
                                        @else
                                            <span class="badge bg-light text-muted fw-bold rounded-pill px-2.5 py-1 border">
                                                #{{ $h->ranking }}
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Driver -->
                                    <td class="py-3 px-3">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center fw-bold flex-shrink-0 bg-light text-secondary border me-3" style="width:36px;height:36px;min-width:36px;font-size:0.88rem;">
                                                {{ strtoupper(substr($h->driver->nama, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark" style="font-size: 0.9rem;">{{ $h->driver->nama }}</div>
                                                <div class="text-muted small" style="font-size: 0.75rem;">
                                                    <span class="badge bg-secondary-subtle text-secondary font-monospace">{{ $h->driver->kode }}</span>
                                                    @if(!empty($h->driver->wilayah))
                                                        <span class="ms-1">{{ $h->driver->wilayah }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Skor -->
                                    <td class="py-3 px-3 text-end">
                                        <span class="badge bg-light text-dark border fw-bold font-monospace shadow-2xs" style="padding: 6px 14px; font-size: 0.86rem; letter-spacing: 0.02em;">
                                            {{ number_format($h->nilai_preferensi, 4) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Kolom Tren Skor 5 Driver Teratas -->
        <div class="col-xxl-5 col-xl-5 col-12">
            <div class="card-soft shadow-sm border-0 h-100" style="border-radius: 18px;">
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-primary-subtle text-primary p-2 rounded-3">
                            <i class="bi bi-graph-up fs-6"></i>
                        </span>
                        <h6 class="fw-bold mb-0 text-dark">Tren Skor 5 Driver Teratas</h6>
                    </div>
                    <span class="badge bg-light text-secondary border px-2.5 py-1 rounded-pill small">
                        Maks. 6 Periode
                    </span>
                </div>
                <div class="card-body p-4">
                    @if(count($trendLabels) < 2)
                        <div class="empty py-5 text-center text-muted">
                            <i class="bi bi-graph-up fs-1 text-muted opacity-50 mb-2 d-block"></i>
                            <p class="mb-0 fw-medium">Grafik tren historis akan muncul setelah terdapat minimal 2 periode penilaian.</p>
                        </div>
                    @else
                        <div style="position: relative; height: 280px; width: 100%;">
                            <canvas id="chartTrend"></canvas>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif
@endsection

@push('scripts')
@if($hasil->isNotEmpty())
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctxRank = document.getElementById('chartRank');
    if (ctxRank) {
        new Chart(ctxRank, {
            type: 'bar',
            data: {
                labels: @json($hasil->map(fn($h) => $h->driver->nama)->values()),
                datasets: [{
                    label: 'Nilai Preferensi (V)',
                    data: @json($hasil->map(fn($h) => (float) $h->nilai_preferensi)->values()),
                    backgroundColor: @json($hasil->map(fn($h) => $h->ranking == 1 ? '#f59e0b' : '#00ADEF')->values()),
                    borderRadius: 8,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(11, 27, 43, 0.92)',
                        titleFont: { family: "'Inter', sans-serif", size: 13, weight: 'bold' },
                        bodyFont: { family: "'Inter', sans-serif", size: 12 },
                        padding: 10,
                        cornerRadius: 8,
                        callbacks: {
                            label: function (context) {
                                return ' Nilai V: ' + Number(context.parsed.y).toFixed(4);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 1.0,
                        ticks: {
                            stepSize: 0.2,
                            callback: function (value) {
                                return value.toFixed(2);
                            },
                            font: { family: "'Inter', sans-serif", size: 11 }
                        },
                        grid: { color: '#f1f5f9' }
                    },
                    x: {
                        ticks: {
                            font: { family: "'Inter', sans-serif", size: 11 }
                        },
                        grid: { display: false }
                    }
                }
            }
        });
    }

    @if(count($trendLabels) >= 2)
    const ctxTrend = document.getElementById('chartTrend');
    if (ctxTrend) {
        const warna = [
            '#00ADEF', // Blitz Brand Cyan
            '#10b981', // Emerald
            '#f59e0b', // Amber
            '#ef4444', // Red
            '#8b5cf6'  // Violet
        ];

        const rawTrend = @json($trend);
        const trendDatasets = rawTrend.map((d, i) => ({
            ...d,
            borderColor: warna[i % warna.length],
            backgroundColor: warna[i % warna.length],
            tension: 0.35,
            spanGaps: true,
            pointRadius: 4,
            pointHoverRadius: 6,
            borderWidth: 2.5
        }));

        new Chart(ctxTrend, {
            type: 'line',
            data: {
                labels: @json(collect($trendLabels)->map(fn($p) => \App\Support\Periode::label($p))->values()),
                datasets: trendDatasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 16,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            boxWidth: 8,
                            boxHeight: 8,
                            font: {
                                family: "'Inter', sans-serif",
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(11, 27, 43, 0.92)',
                        titleFont: { family: "'Inter', sans-serif", size: 13, weight: 'bold' },
                        bodyFont: { family: "'Inter', sans-serif", size: 12 },
                        padding: 10,
                        cornerRadius: 8,
                        callbacks: {
                            label: function (context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += Number(context.parsed.y).toFixed(4);
                                } else {
                                    label += 'Belum dinilai';
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        min: 0.5,
                        max: 1.0,
                        ticks: {
                            stepSize: 0.1,
                            callback: function (value) {
                                return value.toFixed(2);
                            },
                            font: { family: "'Inter', sans-serif", size: 11 }
                        },
                        grid: { color: '#f1f5f9' }
                    },
                    x: {
                        ticks: {
                            font: { family: "'Inter', sans-serif", size: 11 }
                        },
                        grid: { display: false }
                    }
                }
            }
        });
    }
    @endif
});
</script>
@endif
@endpush
