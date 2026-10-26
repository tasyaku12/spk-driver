@extends('layouts.spk')
@section('title', 'Data Penilaian')
@section('subtitle', 'Input nilai tiap driver per kriteria untuk satu periode')

@section('content')
<!-- Row Kartu Periode & Import -->
<div class="row g-4 mb-4">
    <!-- Card Periode -->
    <div class="col-xxl-4 col-xl-4 col-lg-5 col-12">
        <div class="card-soft h-100 shadow-sm border-0">
            <div class="card-body p-4 d-flex flex-column justify-content-between">
                <div>
                    <div class="fw-bold fs-6 mb-1 text-dark d-flex align-items-center gap-2">
                        <i class="bi bi-calendar-event text-primary fs-5 me-1"></i>
                        <span>Pilih Periode Penilaian</span>
                    </div>
                    <p class="text-muted small mb-3">Tampilkan data penilaian berdasarkan bulan & tahun.</p>
                </div>

                <form method="GET" class="d-flex flex-wrap gap-2 mt-auto align-items-center">
                    <div class="input-group flex-grow-1" style="min-width: 170px;">
                        <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-calendar3"></i></span>
                        <input type="month" name="periode" value="{{ $periode }}" class="form-control border-start-0 ps-1" required>
                    </div>
                    <button type="submit" class="btn btn-primary px-3 shadow-xs d-inline-flex align-items-center justify-content-center text-nowrap">
                        <i class="bi bi-arrow-repeat me-2"></i>
                        <span>Tampilkan</span>
                    </button>
                </form>

                <div class="text-muted small mt-2.5 pt-2 border-top" style="font-size:0.75rem;">
                    Periode aktif: <strong class="text-dark">{{ \App\Support\Periode::label($periode) }}</strong>
                </div>
            </div>
        </div>
    </div>

    <!-- Card Import Excel / CSV -->
    <div class="col-xxl-8 col-xl-8 col-lg-7 col-12">
        <div class="card-soft h-100 shadow-sm border-0">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2 pb-2 border-bottom">
                    <div>
                        <div class="fw-bold fs-6 text-dark d-flex align-items-center gap-2">
                            <i class="bi bi-file-earmark-excel-fill text-success fs-5 me-1"></i>
                            <span>Import Data Penilaian (Excel / CSV)</span>
                            <span class="info-popover-wrapper ms-1">
                                <span class="info-popover-trigger sm" tabindex="0" role="button">!</span>
                                <div class="info-popover-content shadow-lg popover-start" style="width:280px;">
                                    <div class="fw-bold text-dark small mb-1"><i class="bi bi-info-circle text-primary me-1"></i> Format File Import</div>
                                    <div class="text-secondary small" style="font-size:0.75rem;">
                                        Gunakan berkas .xlsx, .xls, atau .csv. Baris pertama wajib memuat kolom: <code>kode_driver</code> lalu kode kriteria (<strong>{{ $criterias->pluck('kode')->implode(', ') }}</strong>).
                                    </div>
                                </div>
                            </span>
                        </div>
                        <div class="text-muted small">Unggah berkas untuk mengisi nilai banyak driver secara sekaligus.</div>
                    </div>
                    <a href="{{ route('nilai.template', ['periode'=>$periode]) }}" class="btn btn-sm btn-outline-success d-inline-flex align-items-center px-3 py-1.5 rounded-3 shadow-xs">
                        <i class="bi bi-download me-2"></i>
                        <span>Unduh Template Excel</span>
                    </a>
                </div>

                <form method="POST" action="{{ route('nilai.import') }}" enctype="multipart/form-data" class="row g-3 align-items-center">
                    @csrf
                    <input type="hidden" name="periode" value="{{ $periode }}">
                    <div class="col-md-7 col-sm-6">
                        <input type="file" name="file" accept=".xlsx,.xls,.csv" class="form-control" required>
                    </div>
                    <div class="col-md-5 col-sm-6">
                        <button type="submit" class="btn btn-accent w-100 d-inline-flex align-items-center justify-content-center py-2 shadow-xs">
                            <i class="bi bi-cloud-arrow-up-fill me-2"></i>
                            <span>Import ke {{ \App\Support\Periode::label($periode) }}</span>
                        </button>
                    </div>
                </form>

                <div class="d-flex align-items-center gap-2 text-muted mt-3" style="font-size:0.76rem;">
                    <i class="bi bi-info-circle-fill text-secondary flex-shrink-0 me-1"></i>
                    <span>Susunan kolom file: <code>kode_driver</code>, lalu kode kriteria (<strong>{{ $criterias->pluck('kode')->implode(', ') ?: 'C1, C2, C3' }}</strong>).</span>
                </div>
            </div>
        </div>
    </div>
</div>

@php
    $getContoh = function($c) {
        $kode = strtoupper($c->kode);
        if ($kode === 'C1' || stripos($c->nama, 'waktu') !== false) return '95.00';
        if ($kode === 'C2' || stripos($c->nama, 'kirim') !== false) return '450';
        if ($kode === 'C3' || stripos($c->nama, 'absen') !== false || stripos($c->nama, 'hadir') !== false) return '100.00';
        if ($kode === 'C4' || stripos($c->nama, 'rating') !== false || stripos($c->nama, 'bintang') !== false) return '4.85';
        if ($kode === 'C5' || stripos($c->nama, 'gagal') !== false || stripos($c->nama, 'pending') !== false) return '2';
        if ($kode === 'C6' || stripos($c->nama, 'komplain') !== false || stripos($c->nama, 'keluhan') !== false) return '0';
        return $c->tipe === 'benefit' ? '85.00' : '0';
    };
@endphp

@if(request('filter') === 'unfilled')
    <!-- Alert Filter Khusus Driver Belum Lengkap -->
    <div class="alert alert-warning border-0 rounded-4 shadow-sm p-3 mb-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-funnel-fill text-warning fs-5"></i>
            <div>
                <strong>Menampilkan Driver yang Belum Lengkap:</strong>
                <span class="text-secondary small ms-1">Hanya menampilkan {{ $drivers->count() }} driver dengan nilai belum lengkap pada periode {{ \App\Support\Periode::label($periode) }}.</span>
            </div>
        </div>
        <a href="{{ route('nilai.index', ['periode'=>$periode]) }}" class="btn btn-sm btn-outline-dark rounded-3 d-inline-flex align-items-center gap-1 shadow-xs">
            <i class="bi bi-people-fill"></i>
            <span>Tampilkan Semua Driver ({{ $totalDriverCount ?? $drivers->count() }})</span>
        </a>
    </div>
@endif

<!-- Banner Panduan & Contoh Pengisian Nilai -->
<div class="card-soft shadow-sm border-0 p-3 mb-3 d-flex align-items-center justify-content-between flex-wrap gap-2" style="border-radius: 14px; background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%); border-left: 4px solid var(--accent) !important;">
    <div class="d-flex align-items-center gap-2">
        <i class="bi bi-lightbulb-fill text-warning fs-5"></i>
        <span class="fw-bold text-dark small">Contoh Nilai Pengisian per Kriteria:</span>
    </div>
    <div class="d-flex align-items-center flex-wrap gap-2">
        @foreach($criterias as $c)
            <span class="badge bg-white text-dark border px-2.5 py-1.5 rounded-3 shadow-2xs font-monospace small d-inline-flex align-items-center gap-1">
                <span class="badge bg-primary-subtle text-primary">{{ $c->kode }}</span>
                <span>{{ $c->nama }}:</span>
                <strong class="text-primary">{{ $getContoh($c) }}</strong>
                <small class="text-muted">{{ $c->satuan }}</small>
            </span>
        @endforeach
    </div>
</div>

@if($criterias->isEmpty() || $drivers->isEmpty())
    <div class="alert alert-warning border-0 rounded-4 shadow-sm p-3 mb-4">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-exclamation-triangle-fill fs-5 text-warning"></i>
            <div>
                Data belum siap dinilai. Silakan tambahkan <a href="{{ route('drivers.index') }}" class="alert-link fw-bold">Data Driver</a> dan <a href="{{ route('criterias.index') }}" class="alert-link fw-bold">Kriteria &amp; Bobot</a> terlebih dahulu.
            </div>
        </div>
    </div>
@else
<div class="card-soft shadow-sm border-0 mb-4">
    <!-- Header Tabel Penilaian -->
    <div class="card-header bg-white py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-3 border-bottom">
        <div>
            <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2 flex-wrap">
                <i class="bi bi-table text-primary"></i>
                <span>Tabel Penilaian Driver · {{ \App\Support\Periode::label($periode) }}</span>
                <!-- Menu kecil bulat tanda seru untuk informasi cara memberikan nilai C1, C2, C3 -->
                <span class="info-popover-wrapper">
                    <span class="info-popover-trigger" tabindex="0" role="button" aria-label="Panduan Cara Penilaian Kriteria">!</span>
                    <div class="info-popover-content popover-start shadow-lg" style="width: 320px;">
                        <div class="d-flex align-items-center gap-2 mb-2 pb-2 border-bottom">
                            <span class="badge bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width:20px;height:20px;font-size:0.7rem;">!</span>
                            <span class="fw-bold text-dark small">Panduan Cara &amp; Contoh Pengisian</span>
                        </div>
                        <div class="small text-muted mb-2" style="font-size:0.76rem;">
                            Ketik capaian kinerja riil driver untuk periode ini sesuai contoh berikut:
                        </div>
                        <div class="d-flex flex-column gap-2" style="max-height: 280px; overflow-y: auto;">
                            @foreach($criterias as $c)
                                <div class="bg-light p-2 rounded-2 border">
                                    <div class="fw-semibold text-dark d-flex align-items-center gap-1.5" style="font-size:0.78rem;">
                                        <span class="badge bg-primary-subtle text-primary font-monospace">{{ $c->kode }}</span>
                                        <span>{{ $c->nama }}</span>
                                        <span class="badge bg-white text-secondary border ms-auto" style="font-size:0.65rem;">{{ $c->satuan ?: 'Poin' }}</span>
                                    </div>
                                    <div class="text-secondary mt-1" style="font-size:0.74rem; line-height:1.4;">
                                        Sifat: <strong>{{ ucfirst($c->tipe) }}</strong> &bull; Contoh input: <strong class="text-primary font-monospace">{{ $getContoh($c) }}</strong>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-2 pt-2 border-top text-muted" style="font-size:0.72rem;">
                            <i class="bi bi-lightbulb text-warning me-1"></i> Nilai dapat diisi langsung di tabel atau diimpor via Excel, lalu klik <strong>Simpan Penilaian</strong>.
                        </div>
                    </div>
                </span>
            </h6>
            <small class="text-muted">Masukkan nilai setiap kriteria sesuai hasil performa driver.</small>
        </div>

        <div class="d-flex align-items-center gap-3 flex-wrap">
            <form method="GET" action="{{ route('nilai.index') }}" class="d-flex gap-2" style="max-width:380px;">
                <input type="hidden" name="periode" value="{{ $periode }}">
                @if(request('filter'))<input type="hidden" name="filter" value="{{ request('filter') }}">@endif
                <input name="q" value="{{ $q ?? '' }}" class="form-control form-control-sm" placeholder="Cari kode, nama, atau wilayah...">
                <button class="btn btn-sm btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
                @if(!empty($q))<a href="{{ route('nilai.index', ['periode'=>$periode, 'filter'=>request('filter')]) }}" class="btn btn-sm btn-light">Reset</a>@endif
            </form>

            <button type="submit" form="formPenilaian" class="btn btn-accent d-inline-flex align-items-center gap-2 px-3 py-2 shadow-xs">
                <i class="bi bi-check2-circle fs-6"></i>
                <span>Simpan Penilaian</span>
            </button>
        </div>
    </div>

    <form id="formPenilaian" method="POST" action="{{ route('nilai.store') }}">
        @csrf
        <input type="hidden" name="periode" value="{{ $periode }}">

        <!-- Tabel Konten -->
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead>
                    <tr>
                        <th style="min-width: 220px;" class="ps-4">Mitra Driver</th>
                        @foreach($criterias as $c)
                            <th class="text-center" style="min-width: 175px;">
                                <div class="fw-bold text-dark d-inline-flex align-items-center gap-1.5 justify-content-center">
                                    <span>{{ $c->kode }} · {{ $c->nama }}</span>
                                    <span class="info-popover-wrapper">
                                        <span class="info-popover-trigger sm" tabindex="0" role="button">!</span>
                                        <div class="info-popover-content shadow-lg" style="width:250px;">
                                            <div class="fw-bold text-dark small mb-1">{{ $c->kode }} - {{ $c->nama }}</div>
                                            <div class="text-secondary small" style="font-size:0.75rem;">
                                                Satuan: <strong>{{ $c->satuan ?: '-' }}</strong><br>
                                                Tipe: <strong>{{ ucfirst($c->tipe) }}</strong><br>
                                                Contoh input: <strong class="text-primary font-monospace">{{ $getContoh($c) }}</strong>
                                            </div>
                                        </div>
                                    </span>
                                </div>
                                <div class="mt-1 d-flex align-items-center justify-content-center gap-1.5 flex-wrap">
                                    @if($c->satuan)
                                        <span class="badge bg-light text-secondary border font-monospace" style="font-size:0.68rem;">{{ $c->satuan }}</span>
                                    @endif
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle font-monospace" style="font-size:0.68rem;" title="Contoh pengisian nilai">
                                        cth: {{ $getContoh($c) }}
                                    </span>
                                </div>
                            </th>
                        @endforeach
                        <th width="140" class="text-center pe-4">Status</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($drivers as $d)
                    @php $lengkap = $criterias->every(fn($c) => isset($nilai[$d->id.'-'.$c->id])); @endphp
                    <tr id="row-driver-{{ $d->kode }}" 
                        data-driver-kode="{{ $d->kode }}" 
                        data-lengkap="{{ $lengkap ? '1' : '0' }}"
                        class="{{ !$lengkap ? 'row-unfilled' : '' }}"
                        style="transition: all 0.3s ease;">
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle d-inline-flex align-items-center justify-content-center fw-bold flex-shrink-0 me-3 bg-light text-secondary border" style="width:38px;height:38px;min-width:38px;font-size:0.95rem;line-height:1;text-align:center;">
                                    {{ strtoupper(substr($d->nama, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark" style="font-size:0.93rem;">{{ $d->nama }}</div>
                                    <div class="small text-muted font-monospace"><i class="bi bi-tag me-1"></i>{{ $d->kode }}</div>
                                </div>
                            </div>
                        </td>
                        @foreach($criterias as $c)
                            @php $v = $nilai[$d->id.'-'.$c->id] ?? ''; @endphp
                            <td class="text-center px-3">
                                <input type="number" step="0.01" min="0" 
                                       class="form-control form-control-sm text-center fw-semibold text-dark shadow-xs mx-auto input-nilai-cell"
                                       style="max-width: 140px; border-radius: 8px; font-size:0.92rem; padding:0.4rem 0.5rem;"
                                       name="nilai[{{ $d->id }}][{{ $c->id }}]" 
                                       value="{{ $v !== '' ? $v : '' }}"
                                       placeholder="cth: {{ $getContoh($c) }}"
                                       title="Contoh pengisian: {{ $getContoh($c) }}">
                            </td>
                        @endforeach
                        <td class="text-center pe-4">
                            @if($lengkap)
                                <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle px-3 py-1.5 fw-semibold d-inline-flex align-items-center gap-1">
                                    <i class="bi bi-check-circle-fill"></i> Lengkap
                                </span>
                            @else
                                <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis border border-warning-subtle px-3 py-1.5 fw-bold d-inline-flex align-items-center gap-1">
                                    <i class="bi bi-exclamation-circle-fill"></i> Belum Lengkap
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($criterias) + 2 }}">
                            <div class="empty py-4 text-center">
                                <i class="bi bi-person-x fs-1 text-muted"></i>
                                @if(!empty($q))
                                    <p class="mb-0 mt-2">Driver dengan pencarian "<strong>{{ $q }}</strong>" tidak ditemukan pada periode ini.</p>
                                    <a href="{{ route('nilai.index', ['periode'=>$periode]) }}" class="btn btn-sm btn-light mt-2 shadow-xs">Reset Pencarian</a>
                                @else
                                    <p class="mb-0 mt-2">Belum ada driver yang sesuai dengan kriteria filter.</p>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <!-- Footer Tombol Aksi Bawah -->
        <div class="card-footer bg-white py-3 px-4 d-flex align-items-center justify-content-between flex-wrap gap-3 border-top">
            <div class="text-muted small d-flex align-items-center gap-2">
                <i class="bi bi-info-circle text-primary"></i>
                <span>Pastikan seluruh kriteria terisi lengkap sebelum melanjutkan ke perhitungan perangkingan.</span>
            </div>
            <div class="d-flex align-items-center gap-3 ms-auto">
                <button type="submit" class="btn btn-accent d-inline-flex align-items-center gap-2 px-3 py-2 shadow-xs">
                    <i class="bi bi-save"></i>
                    <span>Simpan Penilaian</span>
                </button>
                <a href="{{ route('perhitungan.index', ['periode'=>$periode]) }}" class="btn btn-outline-dark d-inline-flex align-items-center gap-2 px-3 py-2 shadow-xs rounded-3">
                    <span>Lanjut ke Perhitungan SAW</span>
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </form>
</div>
@endif
@endsection

@push('scripts')
<style>
    .driver-focus-highlight {
        animation: pulseHighlight 2.5s ease-out;
        box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.45) !important;
        background-color: rgba(254, 243, 199, 0.4) !important;
    }
    @keyframes pulseHighlight {
        0% { background-color: rgba(254, 243, 199, 0.85); }
        50% { background-color: rgba(254, 243, 199, 0.45); }
        100% { background-color: rgba(254, 243, 199, 0.2); }
    }
    .row-unfilled {
        background-color: rgba(254, 243, 199, 0.12);
    }
</style>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const urlParams = new URLSearchParams(window.location.search);
    const focusKode = urlParams.get('focus');
    const filterParam = urlParams.get('filter');

    if (focusKode) {
        const targetRow = document.getElementById('row-driver-' + focusKode);
        if (targetRow) {
            setTimeout(function () {
                targetRow.scrollIntoView({ behavior: 'smooth', block: 'center' });
                targetRow.classList.add('driver-focus-highlight');
                const inputs = targetRow.querySelectorAll('input.input-nilai-cell');
                let emptyInput = null;
                inputs.forEach(function(inp) {
                    if (!emptyInput && (!inp.value || inp.value.trim() === '')) {
                        emptyInput = inp;
                    }
                });
                if (!emptyInput && inputs.length > 0) {
                    emptyInput = inputs[0];
                }
                if (emptyInput) {
                    emptyInput.focus();
                    emptyInput.select();
                }
            }, 300);
        }
    } else if (filterParam === 'unfilled') {
        const firstUnfilled = document.querySelector('tr[data-lengkap="0"]');
        if (firstUnfilled) {
            setTimeout(function () {
                firstUnfilled.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstUnfilled.classList.add('driver-focus-highlight');
                const inputs = firstUnfilled.querySelectorAll('input.input-nilai-cell');
                let emptyInput = null;
                inputs.forEach(function(inp) {
                    if (!emptyInput && (!inp.value || inp.value.trim() === '')) {
                        emptyInput = inp;
                    }
                });
                if (!emptyInput && inputs.length > 0) {
                    emptyInput = inputs[0];
                }
                if (emptyInput) {
                    emptyInput.focus();
                    emptyInput.select();
                }
            }, 300);
        }
    }
});
</script>
@endpush
