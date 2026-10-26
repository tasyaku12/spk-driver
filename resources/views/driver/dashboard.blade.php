@extends('layouts.driver')
@section('title', 'Performa Saya - ' . $driver->nama)

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
            <div>
                <div class="small text-white-50 d-flex align-items-center gap-1" style="line-height: 1;">
                    <span>Peringkat SAW Anda</span>
                    <span class="info-popover-wrapper">
                        <span class="info-popover-trigger sm" tabindex="0" role="button">!</span>
                        <div class="info-popover-content shadow-lg popover-start" style="width:260px;">
                            <div class="fw-bold text-dark small mb-1">Peringkat &amp; Skor SAW</div>
                            <div class="text-secondary small" style="font-size:0.75rem;">
                                Posisi peringkat kinerja dihitung menggunakan metode SAW (Simple Additive Weighting) berdasarkan kriteria ketepatan waktu, paket, dan absensi.
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
    @else
        <div class="text-center py-2 text-warning small">
            <i class="bi bi-info-circle me-1"></i> Penilaian periode ini belum dikalkulasi oleh Admin.
        </div>
    @endif
</div>

<!-- 3 Kriteria SPK SAW -->
<div class="d-flex align-items-center justify-content-between mb-2">
    <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-1">
        <i class="bi bi-clipboard-data text-primary me-1"></i>
        <span>Kriteria Penilaian SAW</span>
        <span class="info-popover-wrapper ms-1">
            <span class="info-popover-trigger sm" tabindex="0" role="button">!</span>
            <div class="info-popover-content shadow-lg popover-start" style="width:260px;">
                <div class="fw-bold text-dark small mb-1">Kriteria Evaluasi Driver</div>
                <div class="text-secondary small" style="font-size:0.75rem;">
                    <strong>• C1</strong>: Ketepatan waktu pengiriman (% on-time).<br>
                    <strong>• C2</strong>: Total paket berhasil diantar (satuan paket).<br>
                    <strong>• C3</strong>: Tingkat absensi / kehadiran kerja bulanan.
                </div>
            </div>
        </span>
    </h6>
    <span class="badge bg-light text-muted small border">3 Kriteria</span>
</div>

<div class="row g-2 mb-3">
    @forelse($nilais as $n)
        <div class="col-4">
            <div class="card-app p-3 text-center h-100">
                <span class="badge bg-light text-primary border rounded-pill mb-2 px-2 py-1" style="font-size: 0.65rem;">
                    {{ $n->criteria->kode }} ({{ ($n->criteria->bobot * 100) }}%)
                </span>
                <div class="text-muted text-truncate mb-1" style="font-size: 0.72rem;">{{ $n->criteria->nama }}</div>
                <div class="fs-5 fw-bold text-dark">{{ $n->nilai }}</div>
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
