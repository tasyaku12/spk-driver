@extends('layouts.spk')
@section('title', 'Riwayat Ranking')
@section('subtitle', 'Perbandingan hasil perankingan antarperiode (maksimal 12 periode terakhir)')

@section('actions')
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <a href="{{ route('perhitungan.index') }}" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-3 shadow-xs">
            <i class="bi bi-calculator"></i>
            <span>Perhitungan SAW</span>
        </a>
        <a href="{{ route('riwayat.index') }}" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-3 shadow-xs" title="Refresh Data">
            <i class="bi bi-arrow-clockwise"></i>
            <span>Refresh</span>
        </a>
    </div>
@endsection

@section('content')
@if($periods->isEmpty())
    <div class="card-soft shadow-sm border-0 p-5 text-center">
        <div class="empty py-4">
            <i class="bi bi-clock-history text-muted opacity-50 mb-3" style="font-size: 3rem;"></i>
            <h5 class="fw-bold text-dark mt-2">Belum Ada Riwayat Perhitungan</h5>
            <p class="text-muted mb-4" style="max-width: 480px; margin: 0 auto;">
                Sistem belum menemukan riwayat perangkingan driver. Silakan lakukan perhitungan SAW minimal satu periode penilaian.
            </p>
            <a href="{{ route('perhitungan.index') }}" class="btn btn-accent px-4 py-2 rounded-3 d-inline-flex align-items-center gap-2">
                <i class="bi bi-calculator"></i>
                <span>Menuju Perhitungan SAW</span>
            </a>
        </div>
    </div>
@else
    <!-- KPI Summary Cards -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card-soft shadow-sm border-0 h-100 p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px; font-size: 1.4rem;">
                        <i class="bi bi-calendar3"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-medium">Periode Terhitung</div>
                        <div class="fs-5 fw-bold text-dark lh-sm">{{ $stats['total_periods'] }} Periode</div>
                        <div class="text-muted" style="font-size: 0.75rem;">Maksimal 12 periode aktif</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card-soft shadow-sm border-0 h-100 p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 bg-info-subtle text-info d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px; font-size: 1.4rem;">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-medium">Mitra Driver SPK</div>
                        <div class="fs-5 fw-bold text-dark lh-sm">{{ $stats['total_drivers'] }} Driver</div>
                        <div class="text-muted" style="font-size: 0.75rem;">Tercatat dalam perankingan</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card-soft shadow-sm border-0 h-100 p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 bg-warning-subtle text-warning-emphasis d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px; font-size: 1.4rem;">
                        <i class="bi bi-award-fill"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-medium">Rata-rata Skor Tertinggi</div>
                        <div class="fs-5 fw-bold text-dark lh-sm font-monospace">{{ $stats['top_avg'] }}</div>
                        <div class="text-truncate text-secondary fw-medium" style="font-size: 0.75rem; max-width: 170px;" title="{{ $stats['top_driver'] }}">
                            {{ $stats['top_driver'] }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card-soft shadow-sm border-0 h-100 p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 bg-success-subtle text-success d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px; font-size: 1.4rem;">
                        <i class="bi bi-trophy-fill"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-medium">Juara Terkini</div>
                        <div class="fs-5 fw-bold text-dark lh-sm text-truncate" style="max-width: 170px;" title="{{ $stats['latest_champion'] }}">
                            {{ $stats['latest_champion'] }}
                        </div>
                        <div class="text-muted" style="font-size: 0.75rem;">{{ $stats['latest_period'] }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik Tren & Juara Tiap Periode -->
    <div class="row g-4 mb-4">
        <!-- Kolom Grafik Tren -->
        <div class="col-lg-7">
            <div class="card-soft shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2.5">
                        <span class="badge bg-primary-subtle text-primary p-2 rounded-3">
                            <i class="bi bi-graph-up-arrow fs-6"></i>
                        </span>
                        <div>
                            <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2 flex-wrap">
                                <span>Tren Nilai Preferensi</span>
                                <span class="badge bg-light text-secondary border small fw-normal">8 Driver Rata-Rata Tertinggi</span>
                                <span class="info-popover-wrapper ms-1">
                                    <span class="info-popover-trigger sm" tabindex="0" role="button">!</span>
                                    <div class="info-popover-content shadow-lg popover-start" style="width:280px;">
                                        <div class="fw-bold text-dark small mb-1">Tren Nilai Preferensi</div>
                                        <div class="text-secondary small" style="font-size:0.75rem;">
                                            Grafik fluktuasi skor preferensi SAW driver antarperiode untuk menganalisis tren performa dan konsistensi kerja.
                                        </div>
                                    </div>
                                </span>
                            </h6>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div style="position: relative; height: 280px; width: 100%;">
                        <canvas id="chartRiwayat"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Driver Terbaik Tiap Periode -->
        <div class="col-lg-5">
            <div class="card-soft shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2.5">
                        <span class="badge bg-warning-subtle text-warning-emphasis p-2 rounded-3">
                            <i class="bi bi-trophy-fill fs-6"></i>
                        </span>
                        <h6 class="fw-bold mb-0 text-dark">Driver Terbaik Tiap Periode</h6>
                    </div>
                    <span class="badge bg-light text-secondary border px-2.5 py-1 small rounded-pill">
                        {{ $champions->count() }} Pemenang
                    </span>
                </div>
                <div class="table-responsive" style="max-height: 330px; overflow-y: auto;">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="sticky-top bg-light">
                            <tr>
                                <th>Periode</th>
                                <th>Driver Juara</th>
                                <th class="text-end">Nilai V</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($champions->reverse() as $h)
                                <tr>
                                    <td>
                                        <span class="badge bg-light text-dark border fw-semibold d-inline-flex align-items-center gap-2" style="padding: 6px 12px;">
                                            <i class="bi bi-calendar-check text-primary"></i>
                                            <span>{{ \App\Support\Periode::label($h->periode) }}</span>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center fw-bold flex-shrink-0 bg-primary-subtle text-primary border border-primary-subtle me-3" style="width:36px;height:36px;min-width:36px;font-size:0.88rem;">
                                                {{ strtoupper(substr($h->driver->nama, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark" style="font-size:0.9rem;">{{ $h->driver->nama }}</div>
                                                <div class="text-muted small" style="font-size:0.75rem;">
                                                    <span class="badge bg-secondary-subtle text-secondary font-monospace">{{ $h->driver->kode }}</span>
                                                    @if(!empty($h->driver->wilayah))
                                                        <span class="ms-1">{{ $h->driver->wilayah }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <span class="badge bg-success-subtle text-success-emphasis border border-success-subtle fw-bold font-monospace" style="padding: 6px 14px; font-size:0.85rem; letter-spacing: 0.02em;">
                                            <i class="bi bi-star-fill text-warning me-1"></i>{{ number_format($h->nilai_preferensi, 4) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Matriks Ranking per Periode Table Card -->
    <div class="card-soft shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <span class="badge bg-primary-subtle text-primary p-2 rounded-3">
                    <i class="bi bi-grid-3x3-gap-fill fs-6"></i>
                </span>
                <div>
                    <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2 flex-wrap">
                        <span>Matriks Ranking per Periode</span>
                        <span class="badge bg-light text-secondary border small fw-normal">Format: #Ranking (Nilai V)</span>
                        <span class="info-popover-wrapper ms-1">
                            <span class="info-popover-trigger sm" tabindex="0" role="button">!</span>
                            <div class="info-popover-content shadow-lg popover-start" style="width:280px;">
                                <div class="fw-bold text-dark small mb-1">Matriks Ranking per Periode</div>
                                <div class="text-secondary small" style="font-size:0.75rem;">
                                    Rekapitulasi posisi ranking dan skor preferensi (V) tiap driver pada masing-masing periode penilaian SAW.
                                </div>
                            </div>
                        </span>
                    </h6>
                </div>
            </div>

            <!-- Searching Bar untuk Matriks Ranking -->
            <form method="GET" action="{{ route('riwayat.index') }}" class="d-flex align-items-center gap-2 flex-wrap" style="max-width: 440px;">
                <div class="input-group input-group-sm" style="width: 250px;">
                    <span class="input-group-text bg-light border-end-0 text-muted ps-2.5">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" name="q" value="{{ $q ?? '' }}" class="form-control border-start-0 ps-1" placeholder="Cari nama, kode driver...">
                </div>
                <button type="submit" class="btn btn-sm btn-primary px-3 py-1.5 d-inline-flex align-items-center gap-1.5 shadow-xs flex-shrink-0">
                    <i class="bi bi-funnel"></i>
                    <span>Cari</span>
                </button>
                @if(!empty($q))
                    <a href="{{ route('riwayat.index') }}" class="btn btn-sm btn-light border px-2.5 py-1.5 d-inline-flex align-items-center gap-1 text-secondary shadow-xs flex-shrink-0" title="Reset Pencarian">
                        <i class="bi bi-x-circle text-danger"></i>
                        <span>Reset</span>
                    </a>
                @endif
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th style="min-width: 230px;" class="py-3 px-3">Mitra Driver</th>
                        <th style="min-width: 140px;" class="py-3 px-3">
                            <div class="d-flex align-items-center gap-1">
                                <span>Rata-rata V</span>
                                <span class="info-popover-wrapper ms-1">
                                    <span class="info-popover-trigger sm" tabindex="0" role="button">!</span>
                                    <div class="info-popover-content shadow-lg popover-start" style="width:260px;">
                                        <div class="fw-bold text-dark small mb-1">Rata-rata Nilai V</div>
                                        <div class="text-secondary small" style="font-size:0.75rem;">
                                            Rata-rata nilai preferensi SAW dari semua periode tercatat. Menunjukkan konsistensi kerja jangka panjang.
                                        </div>
                                    </div>
                                </span>
                            </div>
                        </th>
                        @foreach($periods as $p)
                            <th style="min-width: 160px;" class="text-center py-3 px-3">{{ \App\Support\Periode::label($p) }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse($driverRows as $row)
                        <tr>
                            <td class="py-3 px-3">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center fw-bold flex-shrink-0 bg-light text-secondary border me-3" style="width:38px;height:38px;min-width:38px;font-size:0.9rem;">
                                        {{ strtoupper(substr($row['driver']->nama, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark" style="font-size:0.92rem;">{{ $row['driver']->nama }}</div>
                                        <div class="text-muted small" style="font-size:0.75rem;">
                                            <span class="badge bg-secondary-subtle text-secondary font-monospace">{{ $row['driver']->kode }}</span>
                                            @if(!empty($row['driver']->wilayah))
                                                <span class="ms-1">{{ $row['driver']->wilayah }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-3">
                                <span class="badge bg-light text-dark border fw-bold font-monospace" style="padding: 7px 16px; font-size:0.88rem; letter-spacing: 0.02em;">
                                    {{ number_format($row['avg'], 4) }}
                                </span>
                            </td>
                            @foreach($periods as $p)
                                <td class="text-center py-3 px-3">
                                    @if(isset($row['byP'][$p]))
                                        @php
                                            $rank = $row['byP'][$p]->ranking;
                                            $val = $row['byP'][$p]->nilai_preferensi;
                                        @endphp
                                        <div class="d-inline-flex align-items-center bg-white rounded-3 border shadow-2xs" style="padding: 6px 14px; gap: 12px;">
                                            @if($rank == 1)
                                                <span class="badge bg-warning text-dark fw-bold rounded-pill shadow-xs d-inline-flex align-items-center" style="padding: 4px 10px; gap: 6px; font-size: 0.8rem;" title="Ranking 1">
                                                    <i class="bi bi-trophy-fill text-dark" style="font-size: 0.82rem;"></i>
                                                    <span>1</span>
                                                </span>
                                            @elseif($rank == 2)
                                                <span class="badge bg-secondary-subtle text-secondary-emphasis fw-bold rounded-pill border d-inline-flex align-items-center" style="padding: 4px 10px; gap: 6px; font-size: 0.8rem;" title="Ranking 2">
                                                    <i class="bi bi-award text-secondary-emphasis" style="font-size: 0.82rem;"></i>
                                                    <span>2</span>
                                                </span>
                                            @elseif($rank == 3)
                                                <span class="badge bg-danger-subtle text-danger-emphasis fw-bold rounded-pill border d-inline-flex align-items-center" style="padding: 4px 10px; gap: 6px; font-size: 0.8rem;" title="Ranking 3">
                                                    <i class="bi bi-award-fill text-danger" style="font-size: 0.82rem;"></i>
                                                    <span>3</span>
                                                </span>
                                            @else
                                                <span class="badge bg-light text-muted fw-bold rounded-pill border d-inline-flex align-items-center" style="padding: 4px 10px; font-size: 0.8rem;">
                                                    <span>#{{ $rank }}</span>
                                                </span>
                                            @endif
                                            <span class="font-monospace fw-semibold text-dark" style="font-size: 0.86rem; letter-spacing: 0.02em;">
                                                {{ number_format($val, 4) }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-muted opacity-50 font-monospace">-</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ 2 + count($periods) }}" class="text-center py-5 text-muted">
                                <div class="py-3">
                                    <i class="bi bi-search fs-1 text-muted opacity-50 d-block mb-2"></i>
                                    <p class="mb-2 fw-medium text-dark">Tidak ada data driver yang cocok dengan kata kunci "{{ $q }}".</p>
                                    <p class="text-secondary small mb-3">Coba gunakan nama atau kode driver lainnya.</p>
                                    <a href="{{ route('riwayat.index') }}" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1.5 px-3 py-1.5 rounded-3">
                                        <i class="bi bi-arrow-clockwise"></i>
                                        <span>Reset Pencarian</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endif
@endsection

@push('scripts')
@if($periods->isNotEmpty())
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('chartRiwayat');
    if (!ctx) return;

    const warna = [
        '#00ADEF', // Blitz Brand Cyan
        '#10b981', // Emerald
        '#f59e0b', // Amber
        '#ef4444', // Red
        '#8b5cf6', // Violet
        '#06b6d4', // Cyan
        '#84cc16', // Lime
        '#ec4899'  // Pink
    ];

    const rawDatasets = @json($datasets);
    const labels = @json($periods->map(fn($p) => \App\Support\Periode::label($p))->values());

    const datasets = rawDatasets.map((d, i) => ({
        label: d.label,
        data: d.data,
        borderColor: warna[i % warna.length],
        backgroundColor: warna[i % warna.length],
        tension: 0.35,
        spanGaps: true,
        pointRadius: 4,
        pointHoverRadius: 6,
        borderWidth: 2.5
    }));

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: datasets
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
                        callback: function(value) {
                            return value.toFixed(2);
                        },
                        font: { family: "'Inter', sans-serif", size: 11 }
                    },
                    grid: {
                        color: '#f1f5f9'
                    }
                },
                x: {
                    ticks: {
                        font: { family: "'Inter', sans-serif", size: 11 }
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
});
</script>
@endif
@endpush
