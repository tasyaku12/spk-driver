@extends('layouts.spk')
@section('title', 'Perhitungan SAW')
@section('subtitle', 'Simple Additive Weighting · langkah demi langkah')
@section('actions')
    @if(!empty($hasil['matrix']))
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('laporan.pdf', ['periode'=>$periode]) }}" class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-3 shadow-xs">
                <i class="bi bi-file-earmark-pdf-fill"></i>
                <span>Unduh PDF</span>
            </a>
            <a href="{{ route('laporan.excel', ['periode'=>$periode]) }}" class="btn btn-sm btn-outline-success d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-3 shadow-xs">
                <i class="bi bi-file-earmark-excel-fill"></i>
                <span>Unduh Excel</span>
            </a>
        </div>
    @endif
@endsection

@section('content')
<!-- Filter Periode & Action Card -->
<div class="card-soft shadow-sm border-0 mb-4">
    <div class="card-body p-4">
        <form method="GET" class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="d-flex flex-wrap align-items-center gap-3">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-primary-subtle text-primary p-2 rounded-3">
                        <i class="bi bi-calendar-event fs-5"></i>
                    </span>
                    <div>
                        <label class="form-label fw-bold mb-0 text-dark" style="font-size:0.92rem;">Pilih Periode Penilaian</label>
                        <div class="text-muted small" style="font-size:0.75rem;">Pilih bulan evaluasi untuk proses kalkulasi SAW</div>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <input type="month" name="periode" value="{{ $periode }}" class="form-control shadow-xs" style="min-width: 200px;">
                    <button type="submit" class="btn btn-accent d-inline-flex align-items-center gap-2 px-3.5 py-2 shadow-xs">
                        <i class="bi bi-calculator-fill fs-6"></i>
                        <span>Hitung &amp; Simpan Ranking</span>
                    </button>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-light text-secondary border px-3 py-2 rounded-pill d-inline-flex align-items-center gap-2 shadow-2xs">
                    <i class="bi bi-clock-history text-primary"></i>
                    <span>Periode Aktif: <strong>{{ \App\Support\Periode::label($periode) }}</strong></span>
                </span>
            </div>
        </form>
    </div>
</div>

@if($hasil['criterias']->isEmpty())
    <div class="alert alert-warning border-0 rounded-4 shadow-sm p-4 mb-4">
        <div class="d-flex align-items-center gap-3">
            <i class="bi bi-exclamation-triangle-fill fs-3 text-warning"></i>
            <div>
                <div class="fw-bold fs-6">Kriteria Penilaian Belum Tersedia</div>
                <div class="text-secondary small">Silakan tambahkan data kriteria dan bobot terlebih dahulu di menu <a href="{{ route('criterias.index') }}" class="alert-link fw-bold">Kriteria &amp; Bobot</a>.</div>
            </div>
        </div>
    </div>
@elseif(empty($hasil['matrix']))
    <div class="card-soft shadow-sm border-0 mb-4">
        <div class="empty py-5 text-center">
            <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-3" style="width:72px;height:72px;">
                <i class="bi bi-inbox fs-1 text-muted"></i>
            </div>
            <h5 class="fw-bold text-dark mb-2">Belum Ada Data Penilaian Lengkap</h5>
            <p class="text-muted small mb-4" style="max-width:450px; margin: 0 auto;">
                Untuk menjalankan perhitungan SAW periode <strong>{{ \App\Support\Periode::label($periode) }}</strong>, pastikan seluruh nilai driver telah diisi lengkap.
            </p>
            <a href="{{ route('nilai.index', ['periode'=>$periode]) }}" class="btn btn-accent d-inline-flex align-items-center gap-2 px-4 py-2 rounded-3 shadow-xs">
                <i class="bi bi-pencil-square"></i>
                <span>Isi Data Penilaian Sekarang</span>
            </a>
        </div>
    </div>
@else
    @php
        $cr = $hasil['criterias'];
        $dr = $hasil['drivers'];
        $first = array_key_first($hasil['pref']);
    @endphp

    <!-- Alert Driver Tidak Lengkap -->
    @if($hasil['belumLengkap']->count())
        <div class="alert alert-warning border-0 rounded-4 shadow-sm p-4 mb-4">
            <div class="d-flex align-items-start justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-start gap-3">
                    <div class="badge bg-warning text-dark p-2 rounded-circle fs-5 flex-shrink-0">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                    <div>
                        <div class="fw-bold text-dark mb-1">
                            {{ $hasil['belumLengkap']->count() }} Driver Tidak Ikut Dihitung
                        </div>
                        <div class="text-secondary small mb-2">
                            Driver berikut memiliki nilai yang belum lengkap pada periode ini, sehingga dieksklusikan dari perhitungan:
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($hasil['belumLengkap'] as $bl)
                                <a href="{{ route('nilai.index', ['periode'=>$periode, 'focus'=>$bl->kode]) }}" 
                                   class="badge bg-white text-dark border px-2.5 py-1.5 rounded-pill shadow-2xs d-inline-flex align-items-center gap-1.5 text-decoration-none hover-shadow"
                                   title="Klik untuk langsung mengisi nilai driver {{ $bl->nama }}">
                                    <span class="badge bg-warning-subtle text-warning-emphasis font-monospace" style="font-size:0.68rem;">{{ $bl->kode }}</span>
                                    <span class="fw-semibold">{{ $bl->nama }}</span>
                                    <i class="bi bi-arrow-right-short text-warning fs-6 ms-0.5"></i>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
                <a href="{{ route('nilai.index', ['periode'=>$periode, 'filter'=>'unfilled']) }}" class="btn btn-warning text-dark fw-bold d-inline-flex align-items-center gap-2 px-3 py-2 rounded-3 shadow-xs">
                    <i class="bi bi-pencil-square"></i>
                    <span>Lengkapi Data Penilaian</span>
                </a>
            </div>
        </div>
    @endif

    <!-- Hero Card Driver Terbaik (Peringkat 1) -->
    <div class="card-soft shadow-sm mb-4 border-0" style="background: linear-gradient(135deg, #fffbeb 0%, #ffffff 100%); border-left: 5px solid #f59e0b !important;">
        <div class="card-body p-4 d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-4 d-flex align-items-center justify-content-center shadow-xs flex-shrink-0" style="width: 58px; height: 58px; background: #fef3c7; border: 1px solid #fde68a; font-size: 2rem;">
                    🏆
                </div>
                <div>
                    <div class="text-uppercase fw-bold text-warning-emphasis small mb-1" style="font-size:0.75rem; letter-spacing: 0.05em;">
                        <i class="bi bi-award-fill me-1"></i> Driver Terbaik · {{ \App\Support\Periode::label($periode) }}
                    </div>
                    <div class="fs-4 fw-bold text-dark d-flex align-items-center gap-2 flex-wrap">
                        <span>{{ $dr[$first]->nama }}</span>
                        <span class="badge bg-warning text-dark font-monospace fw-bold px-2.5 py-1 rounded-pill" style="font-size:0.78rem;">
                            {{ $dr[$first]->kode }}
                        </span>
                        @if($dr[$first]->wilayah)
                            <span class="badge bg-light text-muted border fw-normal" style="font-size:0.75rem;">
                                <i class="bi bi-geo-alt-fill text-secondary me-1"></i>{{ $dr[$first]->wilayah }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="d-flex align-items-center gap-3 bg-white p-2.5 px-4 rounded-4 border shadow-2xs">
                <div class="text-end">
                    <div class="text-muted small" style="font-size:0.75rem;">Skor Preferensi SAW (V)</div>
                    <div class="fs-3 fw-bold text-warning-emphasis font-monospace" style="line-height:1.1;">
                        {{ number_format($hasil['pref'][$first], 4) }}
                    </div>
                </div>
                <div class="badge bg-warning bg-opacity-20 text-warning-emphasis border border-warning-subtle px-3 py-2 rounded-pill fw-bold" style="font-size:0.8rem;">
                    Juara #1 Terbaik
                </div>
            </div>
        </div>
    </div>

    <!-- Main SAW Calculation Card -->
    <div class="card-soft shadow-sm border-0 mb-4">
        <!-- Header Tahapan SAW: 1 2 3 Berurutan -->
        <div class="card-header bg-white py-3 px-4 border-bottom">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div>
                    <h6 class="fw-bold mb-1 text-dark d-flex align-items-center gap-2">
                        <i class="bi bi-diagram-3-fill text-primary"></i>
                        <span>Tahapan Kalkulasi Metode SAW</span>
                    </h6>
                    <small class="text-muted">Proses perhitungan terstruktur: Data Mentah &rarr; Normalisasi Matriks &rarr; Perangkingan Akhir</small>
                </div>

                <!-- Tabs 1 2 3 Berurutan dengan Spacing yang Bagus -->
                <ul class="nav nav-pills align-items-center gap-3 flex-wrap mb-0" id="sawTabs" role="tablist">
                    <!-- Tab 1: Matriks Keputusan (X) -->
                    <li class="nav-item d-inline-flex align-items-center bg-light p-1 rounded-pill border gap-1">
                        <button class="nav-link active rounded-pill px-3 py-1.5 fw-semibold d-inline-flex align-items-center gap-1.5 border-0" data-bs-toggle="pill" data-bs-target="#t1" type="button" role="tab">
                            <span class="badge rounded-circle d-inline-flex align-items-center justify-content-center bg-white text-dark shadow-2xs" style="width:20px;height:20px;font-size:0.75rem;">1</span>
                            <span>Matriks Keputusan (X)</span>
                        </button>
                        <span class="info-popover-wrapper pe-1.5">
                            <span class="info-popover-trigger sm" tabindex="0" role="button" aria-label="Informasi Tahap 1: Matriks Keputusan">!</span>
                            <div class="info-popover-content shadow-lg popover-start" style="width:280px;">
                                <div class="fw-bold text-dark small mb-1">Tahap 1: Matriks Keputusan (X)</div>
                                <div class="text-secondary small" style="font-size:0.75rem;">
                                    Kumpulan data mentah capaian kinerja driver (C1, C2, C3) pada periode ini sebelum diproses rumus normalisasi SAW.
                                </div>
                            </div>
                        </span>
                    </li>

                    <!-- Tab 2: Normalisasi (R) -->
                    <li class="nav-item d-inline-flex align-items-center bg-light p-1 rounded-pill border gap-1">
                        <button class="nav-link rounded-pill px-3 py-1.5 fw-semibold d-inline-flex align-items-center gap-1.5 border-0" data-bs-toggle="pill" data-bs-target="#t2" type="button" role="tab">
                            <span class="badge rounded-circle d-inline-flex align-items-center justify-content-center bg-white text-dark shadow-2xs" style="width:20px;height:20px;font-size:0.75rem;">2</span>
                            <span>Normalisasi (R)</span>
                        </button>
                        <span class="info-popover-wrapper pe-1.5">
                            <span class="info-popover-trigger sm" tabindex="0" role="button" aria-label="Informasi Tahap 2: Normalisasi">!</span>
                            <div class="info-popover-content shadow-lg popover-start" style="width:280px;">
                                <div class="fw-bold text-dark small mb-1">Tahap 2: Matriks Normalisasi (R)</div>
                                <div class="text-secondary small" style="font-size:0.75rem;">
                                    Mengubah seluruh nilai ke skala desimal 0 s.d 1 dengan membagi nilai tertinggi (benefit) agar satuan persen (%) dan paket bisa dijumlahkan setara.
                                </div>
                            </div>
                        </span>
                    </li>

                    <!-- Tab 3: Ranking (V) -->
                    <li class="nav-item d-inline-flex align-items-center bg-light p-1 rounded-pill border gap-1">
                        <button class="nav-link rounded-pill px-3 py-1.5 fw-semibold d-inline-flex align-items-center gap-1.5 border-0" data-bs-toggle="pill" data-bs-target="#t3" type="button" role="tab">
                            <span class="badge rounded-circle d-inline-flex align-items-center justify-content-center bg-white text-dark shadow-2xs" style="width:20px;height:20px;font-size:0.75rem;">3</span>
                            <span>Ranking Akhir (V)</span>
                        </button>
                        <span class="info-popover-wrapper pe-1.5">
                            <span class="info-popover-trigger sm" tabindex="0" role="button" aria-label="Informasi Tahap 3: Ranking Akhir">!</span>
                            <div class="info-popover-content shadow-lg popover-end" style="width:280px;">
                                <div class="fw-bold text-dark small mb-1">Tahap 3: Perangkingan Akhir (V)</div>
                                <div class="text-secondary small" style="font-size:0.75rem;">
                                    Skor preferensi total hasil perkalian nilai normalisasi dengan bobot kriteria. <strong>Skor tertinggi = Peringkat 1 (Driver Terbaik)</strong>.
                                </div>
                            </div>
                        </span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="card-body p-4">
            <!-- Search bar matching Data Driver -->
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 pb-3 mb-4 border-bottom">
                <form method="GET" action="{{ route('perhitungan.index') }}" class="d-flex gap-2" style="max-width:420px; width: 100%;">
                    <input type="hidden" name="periode" value="{{ $periode }}">
                    <input name="q" value="{{ $q ?? '' }}" class="form-control form-control-sm" placeholder="Cari nama, kode driver, atau wilayah...">
                    <button class="btn btn-sm btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
                    @if(!empty($q))<a href="{{ route('perhitungan.index', ['periode' => $periode]) }}" class="btn btn-sm btn-light">Reset</a>@endif
                </form>
                <div class="text-muted small">
                    Menampilkan <strong>{{ count($hasil['matrixDisplay']) }}</strong> dari {{ count($hasil['matrix']) }} driver dinilai
                </div>
            </div>

            <div class="tab-content">
                <!-- TAB 1: Matriks Keputusan (X) -->
                <div class="tab-pane fade show active" id="t1" role="tabpanel">
                    <div class="formula p-3 rounded-3 mb-3 bg-light border d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <i class="bi bi-info-circle-fill text-primary me-2"></i>
                            <strong>Matriks Keputusan (X)</strong>: Kumpulan nilai mentah performa driver sesuai hasil capaian riil pada setiap kriteria.
                        </div>
                        <div class="text-muted small">
                            Total Alternatif: <strong>{{ count($hasil['matrix']) }} Driver</strong>
                        </div>
                    </div>

                    @php
                        $colMax = [];
                        $colMin = [];
                        foreach ($cr as $c) {
                            $vals = array_column($hasil['matrix'], $c->id);
                            $colMax[$c->id] = !empty($vals) ? max($vals) : 0;
                            $colMin[$c->id] = !empty($vals) ? min($vals) : 0;
                        }
                    @endphp

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4" style="min-width: 220px;">Mitra Driver</th>
                                    @foreach($cr as $c)
                                        <th class="text-center" style="min-width: 160px;">
                                            <div class="fw-bold text-dark">{{ $c->kode }} · {{ $c->nama }}</div>
                                            <div class="d-flex align-items-center justify-content-center gap-1 mt-1">
                                                <span class="badge {{ $c->tipe == 'benefit' ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-warning-subtle text-warning-emphasis border border-warning-subtle' }}" style="font-size:0.68rem;">
                                                    {{ ucfirst($c->tipe) }}
                                                </span>
                                                @if($c->satuan)
                                                    <span class="badge bg-white text-secondary border font-monospace" style="font-size:0.68rem;">{{ $c->satuan }}</span>
                                                @endif
                                            </div>
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($hasil['matrixDisplay'] as $did => $vals)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle d-inline-flex align-items-center justify-content-center fw-bold flex-shrink-0 me-3 bg-light text-secondary border" style="width:36px;height:36px;font-size:0.9rem;">
                                                    {{ strtoupper(substr($dr[$did]->nama, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark">{{ $dr[$did]->nama }}</div>
                                                    <small class="text-muted font-monospace">{{ $dr[$did]->kode }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        @foreach($cr as $c)
                                            <td class="text-center font-monospace fw-semibold" style="font-size:0.92rem;">
                                                {{ $vals[$c->id] }}
                                            </td>
                                        @endforeach
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ count($cr) + 1 }}">
                                            <div class="empty py-4 text-center">
                                                <i class="bi bi-person-x fs-1 text-muted"></i>
                                                @if(!empty($q))
                                                    <p class="mb-0 mt-2">Driver dengan pencarian "<strong>{{ $q }}</strong>" tidak ditemukan pada periode ini.</p>
                                                    <a href="{{ route('perhitungan.index', ['periode' => $periode]) }}" class="btn btn-sm btn-light mt-2 shadow-xs">Reset Pencarian</a>
                                                @else
                                                    <p class="mb-0 mt-2">Belum ada data matriks keputusan.</p>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr class="table-light border-top">
                                    <td class="ps-4 fw-bold text-success">
                                        <i class="bi bi-arrow-up-circle-fill me-1.5"></i> Nilai Maksimum (Max X)
                                    </td>
                                    @foreach($cr as $c)
                                        <td class="text-center font-monospace fw-bold text-success" style="font-size:0.92rem;">
                                            {{ $colMax[$c->id] }}
                                        </td>
                                    @endforeach
                                </tr>
                                <tr class="table-light">
                                    <td class="ps-4 fw-bold text-secondary">
                                        <i class="bi bi-arrow-down-circle-fill me-1.5"></i> Nilai Minimum (Min X)
                                    </td>
                                    @foreach($cr as $c)
                                        <td class="text-center font-monospace fw-bold text-secondary" style="font-size:0.92rem;">
                                            {{ $colMin[$c->id] }}
                                        </td>
                                    @endforeach
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="mt-4 pt-3 border-top d-flex justify-content-end align-items-center">
                        <button type="button" class="btn btn-outline-primary d-inline-flex align-items-center gap-2 px-3.5 py-2 rounded-3 shadow-xs" onclick="switchSawTab('t2')">
                            <span>Lanjut ke Tahap 2: Normalisasi (R)</span>
                            <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>
                </div>

                <!-- TAB 2: Normalisasi (R) -->
                <div class="tab-pane fade" id="t2" role="tabpanel">
                    <div class="formula p-3 rounded-3 mb-3 bg-light border d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <i class="bi bi-calculator text-primary me-2"></i>
                            <strong>Rumus Normalisasi (R)</strong>:
                            <span class="badge bg-success-subtle text-success border border-success-subtle mx-1">Benefit: r_ij = x_ij / Max(x_j)</span>
                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle mx-1">Cost: r_ij = Min(x_j) / x_ij</span>
                        </div>
                        <div class="text-muted small">
                            Skala Nilai: <strong>0.0000 s.d 1.0000</strong>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4" style="min-width: 220px;">Mitra Driver</th>
                                    @foreach($cr as $c)
                                        <th class="text-center" style="min-width: 160px;">
                                            <div class="fw-bold text-dark">{{ $c->kode }} · {{ $c->nama }}</div>
                                            <div class="d-flex align-items-center justify-content-center gap-1 mt-1">
                                                <span class="badge {{ $c->tipe == 'benefit' ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning-emphasis' }}" style="font-size:0.68rem;">
                                                    {{ ucfirst($c->tipe) }}
                                                </span>
                                                <span class="badge bg-light text-primary border" style="font-size:0.68rem;">
                                                    Bobot: {{ $c->bobot }} ({{ $c->bobot * 100 }}%)
                                                </span>
                                            </div>
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($hasil['normDisplay'] as $did => $rs)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle d-inline-flex align-items-center justify-content-center fw-bold flex-shrink-0 me-3 bg-light text-secondary border" style="width:36px;height:36px;font-size:0.9rem;">
                                                    {{ strtoupper(substr($dr[$did]->nama, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark">{{ $dr[$did]->nama }}</div>
                                                    <small class="text-muted font-monospace">{{ $dr[$did]->kode }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        @foreach($cr as $c)
                                            <td class="text-center font-monospace fw-bold text-dark" style="font-size:0.92rem;">
                                                {{ number_format($rs[$c->id], 4) }}
                                            </td>
                                        @endforeach
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ count($cr) + 1 }}">
                                            <div class="empty py-4 text-center">
                                                <i class="bi bi-person-x fs-1 text-muted"></i>
                                                @if(!empty($q))
                                                    <p class="mb-0 mt-2">Driver dengan pencarian "<strong>{{ $q }}</strong>" tidak ditemukan pada periode ini.</p>
                                                    <a href="{{ route('perhitungan.index', ['periode' => $periode]) }}" class="btn btn-sm btn-light mt-2 shadow-xs">Reset Pencarian</a>
                                                @else
                                                    <p class="mb-0 mt-2">Belum ada data normalisasi.</p>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr class="table-light border-top">
                                    <td class="ps-4 fw-bold text-primary">
                                        <i class="bi bi-sliders me-1.5"></i> Bobot Kriteria (w_j)
                                    </td>
                                    @foreach($cr as $c)
                                        <td class="text-center">
                                            <span class="badge bg-primary text-white font-monospace px-2.5 py-1">{{ $c->bobot }}</span>
                                        </td>
                                    @endforeach
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="mt-4 pt-3 border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <button type="button" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2 px-3.5 py-2 rounded-3 shadow-xs" onclick="switchSawTab('t1')">
                            <i class="bi bi-arrow-left"></i>
                            <span>Kembali ke Tahap 1: Matriks (X)</span>
                        </button>
                        <button type="button" class="btn btn-outline-primary d-inline-flex align-items-center gap-2 px-3.5 py-2 rounded-3 shadow-xs" onclick="switchSawTab('t3')">
                            <span>Lanjut ke Tahap 3: Ranking (V)</span>
                            <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>
                </div>

                <!-- TAB 3: Ranking Akhir (V) -->
                <div class="tab-pane fade" id="t3" role="tabpanel">
                    <div class="formula p-3 rounded-3 mb-3 bg-light border d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <i class="bi bi-trophy-fill text-warning me-2"></i>
                            <strong>Rumus Nilai Preferensi (V)</strong>: <code>V_i = Σ (w_j × r_ij)</code>
                            <span class="text-muted ms-2">(Penjumlahan perkalian bobot kriteria dengan nilai normalisasi)</span>
                        </div>
                        <div class="text-muted small">
                            Total Bobot Dipakai: <strong>{{ round($hasil['totalBobot'], 4) }}</strong>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th width="90" class="ps-4 text-center">Rank</th>
                                    <th width="120">Kode</th>
                                    <th>Mitra Driver</th>
                                    <th style="min-width: 180px;">Visual Skor</th>
                                    <th class="text-end pe-4" style="min-width: 180px;">
                                        <span>Nilai V (Skor Akhir)</span>
                                        <span class="info-popover-wrapper ms-1">
                                            <span class="info-popover-trigger sm" tabindex="0" role="button">!</span>
                                            <div class="info-popover-content shadow-lg popover-end" style="width:260px;">
                                                <div class="fw-bold text-dark small mb-1">Nilai Preferensi (V)</div>
                                                <div class="text-secondary small" style="font-size:0.74rem;">
                                                    Nilai akumulasi total SAW antara 0.0000 s.d 1.0000. Skor tertinggi menempati Peringkat 1 sebagai driver terbaik.
                                                </div>
                                            </div>
                                        </span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($hasil['prefDisplay'] as $did => $v)
                                    @php $rk = $hasil['ranks'][$did]; @endphp
                                    <tr class="{{ $rk == 1 ? 'table-warning bg-opacity-25' : '' }}">
                                        <td class="ps-4 text-center">
                                            @if($rk == 1)
                                                <span class="badge bg-warning text-dark rounded-circle d-inline-flex align-items-center justify-content-center shadow-xs fw-bold" style="width:34px;height:34px;font-size:1.05rem;">
                                                    🥇
                                                </span>
                                            @elseif($rk == 2)
                                                <span class="badge bg-secondary-subtle text-secondary rounded-circle d-inline-flex align-items-center justify-content-center shadow-2xs fw-bold" style="width:34px;height:34px;font-size:1.05rem;">
                                                    🥈
                                                </span>
                                            @elseif($rk == 3)
                                                <span class="badge bg-warning-subtle text-warning-emphasis rounded-circle d-inline-flex align-items-center justify-content-center shadow-2xs fw-bold" style="width:34px;height:34px;font-size:1.05rem;">
                                                    🥉
                                                </span>
                                            @else
                                                <span class="badge bg-light text-secondary border rounded-circle d-inline-flex align-items-center justify-content-center fw-bold" style="width:32px;height:32px;font-size:0.85rem;">
                                                    {{ $rk }}
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary-subtle text-secondary border font-monospace">{{ $dr[$did]->kode }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle d-inline-flex align-items-center justify-content-center fw-bold flex-shrink-0 me-3 {{ $rk == 1 ? 'bg-warning text-dark' : 'bg-light text-secondary border' }}" style="width:36px;height:36px;font-size:0.9rem;">
                                                    {{ strtoupper(substr($dr[$did]->nama, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark d-flex align-items-center gap-2">
                                                        <span>{{ $dr[$did]->nama }}</span>
                                                        @if($rk == 1)
                                                            <span class="badge bg-warning text-dark rounded-pill fw-bold" style="font-size:0.65rem;">Terbaik</span>
                                                        @endif
                                                    </div>
                                                    @if($dr[$did]->wilayah)
                                                        <small class="text-muted"><i class="bi bi-geo-alt me-0.5"></i>{{ $dr[$did]->wilayah }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="progress flex-grow-1" style="height: 8px;">
                                                    <div class="progress-bar {{ $rk == 1 ? 'bg-warning' : 'bg-primary' }}" style="width: {{ min(max($v * 100, 0), 100) }}%;"></div>
                                                </div>
                                                <span class="text-muted small font-monospace" style="font-size:0.75rem;">{{ round($v * 100, 1) }}%</span>
                                            </div>
                                        </td>
                                        <td class="text-end pe-4">
                                            <span class="fs-5 fw-bold font-monospace {{ $rk == 1 ? 'text-warning-emphasis' : 'text-dark' }}">
                                                {{ number_format($v, 4) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5">
                                            <div class="empty py-4 text-center">
                                                <i class="bi bi-person-x fs-1 text-muted"></i>
                                                @if(!empty($q))
                                                    <p class="mb-0 mt-2">Driver dengan pencarian "<strong>{{ $q }}</strong>" tidak ditemukan pada periode ini.</p>
                                                    <a href="{{ route('perhitungan.index', ['periode' => $periode]) }}" class="btn btn-sm btn-light mt-2 shadow-xs">Reset Pencarian</a>
                                                @else
                                                    <p class="mb-0 mt-2">Belum ada data perankingan.</p>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 pt-3 border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <button type="button" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2 px-3.5 py-2 rounded-3 shadow-xs" onclick="switchSawTab('t2')">
                            <i class="bi bi-arrow-left"></i>
                            <span>Kembali ke Tahap 2: Normalisasi (R)</span>
                        </button>
                        <div class="d-flex align-items-center gap-2">
                            <a href="{{ route('laporan.pdf', ['periode'=>$periode]) }}" class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1.5 px-3 py-2 rounded-3 shadow-xs">
                                <i class="bi bi-file-earmark-pdf-fill"></i>
                                <span>Cetak PDF</span>
                            </a>
                            <a href="{{ route('laporan.excel', ['periode'=>$periode]) }}" class="btn btn-sm btn-outline-success d-inline-flex align-items-center gap-1.5 px-3 py-2 rounded-3 shadow-xs">
                                <i class="bi bi-file-earmark-excel-fill"></i>
                                <span>Export Excel</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection

@push('styles')
<style>
    /* Styling Pills Tab Khusus Perhitungan SAW */
    #sawTabs .nav-link {
        color: #475569;
        font-size: 0.86rem;
        transition: all 0.2s ease;
    }
    #sawTabs .nav-link:hover {
        color: #0b1b2b;
        background: rgba(0, 0, 0, 0.04);
    }
    #sawTabs .nav-link.active {
        background-color: #00ADEF !important;
        color: #ffffff !important;
        box-shadow: 0 2px 8px rgba(0, 173, 239, 0.35);
    }
    #sawTabs .nav-link.active .badge {
        background-color: rgba(255, 255, 255, 0.25) !important;
        color: #ffffff !important;
    }
</style>
@endpush

@push('scripts')
<script>
    function switchSawTab(tabId) {
        const triggerEl = document.querySelector('[data-bs-target="#' + tabId + '"]');
        if (triggerEl) {
            bootstrap.Tab.getOrCreateInstance(triggerEl).show();
            const headerEl = document.getElementById('sawTabs');
            if (headerEl) {
                headerEl.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        }
    }
</script>
@endpush
