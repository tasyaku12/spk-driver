@extends('layouts.spk')
@section('title', 'Kriteria & Bobot')
@section('subtitle', 'Kelola kriteria penilaian, bobot, dan jenisnya (benefit / cost)')
@section('actions')
    <button class="btn btn-accent" data-bs-toggle="modal" data-bs-target="#mTambah"><i class="bi bi-plus-lg"></i> Tambah Kriteria</button>
@endsection

@section('content')
@php $ok = abs($totalBobot - 1) < 0.0001; @endphp
<!-- Summary Cards: Total Bobot & Panduan Sifat Kriteria -->
<div class="row g-4 mb-4">
    <!-- Card Total Bobot -->
    <div class="col-xxl-7 col-xl-7 col-12">
        <div class="card-soft shadow-sm border-0 h-100 p-4" style="border-radius: 18px;">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 pb-3 border-bottom border-light-subtle">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" 
                         style="width: 44px; height: 44px; font-size: 1.25rem; background: {{ $ok ? '#ecfdf5; color: #10b981;' : '#fff1f2; color: #f43f5e;' }}">
                        <i class="bi {{ $ok ? 'bi-pie-chart-fill' : 'bi-exclamation-triangle-fill' }}"></i>
                    </div>
                    <div>
                        <div class="fw-bold fs-6 text-dark d-flex align-items-center gap-1.5">
                            <span>Total Bobot Kriteria</span>
                            <span class="info-popover-wrapper">
                                <span class="info-popover-trigger sm" tabindex="0" role="button">!</span>
                                <div class="info-popover-content shadow-lg popover-start" style="width:280px;">
                                    <div class="fw-bold text-dark small mb-1">Tingkat Prioritas (Bobot)</div>
                                    <div class="text-secondary small" style="font-size:0.75rem;">
                                        Menentukan bobot pengaruh tiap kriteria. Total seluruh bobot harus bernilai <strong>1.00 (100%)</strong> agar hasil perankingan SAW presisi dan valid.
                                    </div>
                                </div>
                            </span>
                        </div>
                        <div class="text-muted small" style="font-size: 0.78rem;">
                            Akumulasi seluruh bobot untuk normalisasi SAW
                        </div>
                    </div>
                </div>

                <div>
                    <span class="badge {{ $ok ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-danger-subtle text-danger border border-danger-subtle' }} px-3 py-2 rounded-pill font-monospace fw-bold fs-6 shadow-2xs">
                        {{ number_format($totalBobot, 4) }} / 1.0000 <span class="fw-normal" style="font-size: 0.78rem;">({{ round($totalBobot * 100, 1) }}%)</span>
                    </span>
                </div>
            </div>

            <!-- Progress Bar dengan Spacing Lega -->
            <div class="py-3 my-1">
                <div class="d-flex align-items-center justify-content-between text-muted small mb-2" style="font-size: 0.78rem;">
                    <span>Distribusi Bobot</span>
                    <span class="font-monospace fw-semibold {{ $ok ? 'text-success' : 'text-danger' }}">
                        {{ round(min($totalBobot, 1) * 100, 1) }}% dari 100%
                    </span>
                </div>
                <div class="progress shadow-2xs" style="height: 12px; border-radius: 999px; background-color: #f1f5f9; overflow: hidden;">
                    <div class="progress-bar" 
                         role="progressbar" 
                         style="width: {{ min($totalBobot, 1) * 100 }}%; border-radius: 999px; background: {{ $ok ? 'linear-gradient(90deg, #10b981 0%, #059669 100%)' : ($totalBobot > 1 ? 'linear-gradient(90deg, #f43f5e 0%, #e11d48 100%)' : 'linear-gradient(90deg, #f59e0b 0%, #d97706 100%)') }}; transition: width 0.6s ease;">
                    </div>
                </div>
            </div>

            <!-- Status Keterangan & Aksi -->
            @if($ok)
                <div class="d-flex align-items-center justify-content-between pt-1 flex-wrap gap-2">
                    <span class="text-success small fw-medium d-inline-flex align-items-center gap-1.5" style="font-size: 0.82rem;">
                        <i class="bi bi-patch-check-fill fs-6 text-success"></i>
                        <span>Total bobot sudah seimbang (1.00). Siap digunakan untuk perhitungan SPK.</span>
                    </span>
                    <span class="badge bg-light text-secondary border px-2.5 py-1 rounded-pill small font-monospace">
                        {{ $criterias->count() }} Kriteria Terdaftar
                    </span>
                </div>
            @else
                <div class="d-flex align-items-center justify-content-between pt-2 border-top border-light-subtle flex-wrap gap-2">
                    <span class="text-danger small fw-medium d-inline-flex align-items-center gap-1.5" style="font-size: 0.8rem;">
                        <i class="bi bi-exclamation-circle-fill fs-6"></i>
                        <span>Total bobot belum 1.00. Normalisasi otomatis dapat diterapkan secara adil.</span>
                    </span>
                    <form method="POST" action="{{ route('criterias.normalisasi') }}" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-primary rounded-3 shadow-xs px-3 d-inline-flex align-items-center gap-1.5">
                            <i class="bi bi-arrow-repeat"></i>
                            <span>Normalisasi ke 1.00</span>
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>

    <!-- Card Panduan Sifat Kriteria -->
    <div class="col-xxl-5 col-xl-5 col-12">
        <div class="card-soft shadow-sm border-0 h-100 p-4 d-flex flex-column justify-content-between" style="border-radius: 18px;">
            <div>
                <div class="d-flex align-items-center gap-3 pb-3 border-bottom border-light-subtle mb-3">
                    <div class="rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; font-size: 1.25rem;">
                        <i class="bi bi-calculator-fill"></i>
                    </div>
                    <div>
                        <div class="fw-bold fs-6 text-dark d-flex align-items-center gap-1.5">
                            <span>Prinsip Sifat Kriteria</span>
                            <span class="info-popover-wrapper">
                                <span class="info-popover-trigger sm" tabindex="0" role="button">!</span>
                                <div class="info-popover-content shadow-lg popover-end" style="width:270px;">
                                    <div class="fw-bold text-dark small mb-1">Metode SAW</div>
                                    <div class="text-secondary small" style="font-size:0.75rem;">
                                        Setiap kriteria memiliki arah nilai yang berbeda dalam rumus normalisasi matriks $R$.
                                    </div>
                                </div>
                            </span>
                        </div>
                        <div class="text-muted small" style="font-size: 0.78rem;">
                            Pedoman normalisasi rumus Simple Additive Weighting
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-column gap-2">
                    <div class="d-flex align-items-center justify-content-between p-2.5 rounded-3 border" style="background-color: #f0fdf4; border-color: #bbf7d0 !important;">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-success text-white px-2.5 py-1 rounded-2 fw-semibold" style="font-size: 0.75rem;">Benefit</span>
                            <span class="small text-dark fw-medium" style="font-size: 0.8rem;">Makin besar nilai makin unggul</span>
                        </div>
                        <code class="text-success fw-bold font-monospace small px-2 py-0.5 rounded bg-white border border-success-subtle">r = x / max(x)</code>
                    </div>

                    <div class="d-flex align-items-center justify-content-between p-2.5 rounded-3 border" style="background-color: #fffbeb; border-color: #fef08a !important;">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-warning text-dark px-2.5 py-1 rounded-2 fw-semibold" style="font-size: 0.75rem;">Cost</span>
                            <span class="small text-dark fw-medium" style="font-size: 0.8rem;">Makin kecil nilai makin unggul</span>
                        </div>
                        <code class="text-warning-emphasis fw-bold font-monospace small px-2 py-0.5 rounded bg-white border border-warning-subtle">r = min(x) / x</code>
                    </div>
                </div>
            </div>

            <div class="text-muted small pt-2 mt-2 border-top border-light-subtle d-flex align-items-center justify-content-between" style="font-size: 0.75rem;">
                <span>Formula Skor Akhir:</span>
                <span class="font-monospace fw-semibold text-dark">V = &Sigma; (bobot &times; r)</span>
            </div>
        </div>
    </div>
</div>

<div class="card-soft shadow-sm border-0" style="border-radius: 18px; overflow: hidden;">
    <div class="table-responsive"><table class="table table-hover mb-0 align-middle">
    <thead class="bg-light">
        <tr>
            <th width="100">Kode</th>
            <th>Nama Kriteria</th>
            <th>Satuan</th>
            <th>Bobot</th>
            <th>
                <span>Tipe</span>
                <span class="info-popover-wrapper ms-1">
                    <span class="info-popover-trigger sm" tabindex="0" role="button">!</span>
                    <div class="info-popover-content shadow-lg popover-end" style="width:260px;">
                        <div class="fw-bold text-dark small mb-1">Sifat Kriteria (Tipe)</div>
                        <div class="text-secondary small" style="font-size:0.74rem;">
                            <strong>• Benefit</strong>: Makin tinggi nilainya makin bagus.<br>
                            <strong>• Cost</strong>: Makin rendah nilainya makin bagus.
                        </div>
                    </div>
                </span>
            </th>
            <th width="130" class="text-end">Aksi</th>
        </tr>
    </thead>
    <tbody>
    @forelse($criterias as $c)
        <tr>
            <td><span class="badge bg-secondary-subtle text-secondary border font-monospace px-2.5 py-1 rounded-2">{{ $c->kode }}</span></td>
            <td class="fw-bold text-dark">{{ $c->nama }}</td>
            <td>{{ $c->satuan ?: '-' }}</td>
            <td>
                <span class="fw-bold font-monospace">{{ $c->bobot }}</span> 
                <small class="text-muted">({{ rtrim(rtrim(number_format($c->bobot*100,2),'0'),'.') }}%)</small>
            </td>
            <td>
                @if($c->tipe == 'benefit')
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 rounded-pill fw-semibold">Benefit</span>
                @else
                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2.5 py-1 rounded-pill fw-semibold">Cost</span>
                @endif
            </td>
            <td class="text-end">
                <button class="btn btn-sm btn-outline-primary rounded-2" data-bs-toggle="modal" data-bs-target="#mEdit{{ $c->id }}" title="Edit Kriteria"><i class="bi bi-pencil"></i></button>
                <form method="POST" action="{{ route('criterias.destroy', $c) }}" class="d-inline" onsubmit="return confirm('Hapus kriteria {{ $c->nama }}? Seluruh nilai pada kriteria ini ikut terhapus.')">@csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger rounded-2" title="Hapus Kriteria"><i class="bi bi-trash"></i></button></form></td></tr>
    @empty
        <tr><td colspan="6"><div class="empty py-4"><i class="bi bi-sliders"></i><p class="mb-0 mt-2">Belum ada kriteria.</p></div></td></tr>
    @endforelse
    </tbody>
</table></div></div>

{{-- Modal tambah --}}
<div class="modal fade" id="mTambah" tabindex="-1"><div class="modal-dialog"><form method="POST" action="{{ route('criterias.store') }}" class="modal-content">@csrf
    <div class="modal-header"><h6 class="modal-title">Tambah Kriteria</h6><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body">
        <div class="row g-2">
            <div class="col-4">
                <label class="form-label d-flex align-items-center gap-1">
                    <span>Kode</span>
                    <span class="info-popover-wrapper">
                        <span class="info-popover-trigger sm" tabindex="0" role="button">!</span>
                        <div class="info-popover-content shadow-lg popover-start" style="width:250px;">
                            <div class="fw-bold text-dark small mb-1">Kode Kriteria</div>
                            <div class="text-secondary small" style="font-size:0.74rem;">
                                Simbol identitas kriteria pada rumus SAW (contoh: <code>C1</code>, <code>C2</code>, <code>C4</code>).
                            </div>
                        </div>
                    </span>
                </label>
                <input name="kode" class="form-control" required placeholder="C4">
            </div>
            <div class="col-8">
                <label class="form-label">Nama Kriteria</label>
                <input name="nama" class="form-control" required placeholder="Rating Pelanggan">
            </div>
            <div class="col-4">
                <label class="form-label d-flex align-items-center gap-1">
                    <span>Satuan</span>
                    <span class="info-popover-wrapper">
                        <span class="info-popover-trigger sm" tabindex="0" role="button">!</span>
                        <div class="info-popover-content shadow-lg popover-start" style="width:250px;">
                            <div class="fw-bold text-dark small mb-1">Satuan Nilai</div>
                            <div class="text-secondary small" style="font-size:0.74rem;">
                                Satuan ukuran capaian driver (contoh: <code>%</code>, <code>paket</code>, <code>skor 1-5</code>, <code>hari</code>).
                            </div>
                        </div>
                    </span>
                </label>
                <input name="satuan" class="form-control" placeholder="% / paket">
            </div>
            <div class="col-4">
                <label class="form-label d-flex align-items-center gap-1">
                    <span>Bobot (0-1)</span>
                    <span class="info-popover-wrapper">
                        <span class="info-popover-trigger sm" tabindex="0" role="button">!</span>
                        <div class="info-popover-content shadow-lg popover-start" style="width:260px;">
                            <div class="fw-bold text-dark small mb-1">Bobot Kriteria (0 - 1)</div>
                            <div class="text-secondary small" style="font-size:0.74rem;">
                                Nilai prioritas desimal antara 0 s.d 1 (contoh: <code>0.20</code> untuk 20%). Total seluruh bobot harus pas <strong>1.00 (100%)</strong>.
                            </div>
                        </div>
                    </span>
                </label>
                <input type="number" step="0.01" min="0" max="1" name="bobot" class="form-control" required placeholder="0.20">
            </div>
            <div class="col-4">
                <label class="form-label d-flex align-items-center gap-1">
                    <span>Tipe</span>
                    <span class="info-popover-wrapper">
                        <span class="info-popover-trigger sm" tabindex="0" role="button">!</span>
                        <div class="info-popover-content shadow-lg popover-end" style="width:260px;">
                            <div class="fw-bold text-dark small mb-1">Sifat Kriteria (Tipe)</div>
                            <div class="text-secondary small" style="font-size:0.74rem;">
                                <strong>• Benefit</strong>: Makin tinggi nilainya makin bagus (misal: pengiriman, on-time).<br>
                                <strong>• Cost</strong>: Makin kecil nilainya makin bagus (misal: komplain, terlambat).
                            </div>
                        </div>
                    </span>
                </label>
                <select name="tipe" class="form-select">
                    <option value="benefit">Benefit</option>
                    <option value="cost">Cost</option>
                </select>
            </div>
        </div>
    </div>
    <div class="modal-footer"><button class="btn btn-accent">Simpan</button></div>
</form></div></div>

@foreach($criterias as $c)
<div class="modal fade" id="mEdit{{ $c->id }}" tabindex="-1"><div class="modal-dialog"><form method="POST" action="{{ route('criterias.update', $c) }}" class="modal-content">@csrf @method('PUT')
    <div class="modal-header"><h6 class="modal-title">Edit Kriteria {{ $c->kode }}</h6><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body">
        <div class="row g-2">
            <div class="col-4">
                <label class="form-label d-flex align-items-center gap-1">
                    <span>Kode</span>
                    <span class="info-popover-wrapper">
                        <span class="info-popover-trigger sm" tabindex="0" role="button">!</span>
                        <div class="info-popover-content shadow-lg popover-start" style="width:250px;">
                            <div class="fw-bold text-dark small mb-1">Kode Kriteria</div>
                            <div class="text-secondary small" style="font-size:0.74rem;">
                                Simbol kriteria pada rumus SAW (contoh: <code>{{ $c->kode }}</code>).
                            </div>
                        </div>
                    </span>
                </label>
                <input name="kode" value="{{ $c->kode }}" class="form-control" required>
            </div>
            <div class="col-8">
                <label class="form-label">Nama Kriteria</label>
                <input name="nama" value="{{ $c->nama }}" class="form-control" required>
            </div>
            <div class="col-4">
                <label class="form-label d-flex align-items-center gap-1">
                    <span>Satuan</span>
                    <span class="info-popover-wrapper">
                        <span class="info-popover-trigger sm" tabindex="0" role="button">!</span>
                        <div class="info-popover-content shadow-lg popover-start" style="width:250px;">
                            <div class="fw-bold text-dark small mb-1">Satuan Nilai</div>
                            <div class="text-secondary small" style="font-size:0.74rem;">
                                Satuan ukuran capaian driver (contoh: <code>%</code>, <code>paket</code>, <code>hari</code>).
                            </div>
                        </div>
                    </span>
                </label>
                <input name="satuan" value="{{ $c->satuan }}" class="form-control">
            </div>
            <div class="col-4">
                <label class="form-label d-flex align-items-center gap-1">
                    <span>Bobot (0-1)</span>
                    <span class="info-popover-wrapper">
                        <span class="info-popover-trigger sm" tabindex="0" role="button">!</span>
                        <div class="info-popover-content shadow-lg popover-start" style="width:260px;">
                            <div class="fw-bold text-dark small mb-1">Bobot Kriteria (0 - 1)</div>
                            <div class="text-secondary small" style="font-size:0.74rem;">
                                Nilai prioritas desimal (0 - 1). Total seluruh bobot kriteria harus pas <strong>1.00 (100%)</strong>.
                            </div>
                        </div>
                    </span>
                </label>
                <input type="number" step="0.01" min="0" max="1" name="bobot" value="{{ $c->bobot }}" class="form-control" required>
            </div>
            <div class="col-4">
                <label class="form-label d-flex align-items-center gap-1">
                    <span>Tipe</span>
                    <span class="info-popover-wrapper">
                        <span class="info-popover-trigger sm" tabindex="0" role="button">!</span>
                        <div class="info-popover-content shadow-lg popover-end" style="width:260px;">
                            <div class="fw-bold text-dark small mb-1">Sifat Kriteria (Tipe)</div>
                            <div class="text-secondary small" style="font-size:0.74rem;">
                                <strong>• Benefit</strong>: Makin tinggi nilainya makin bagus.<br>
                                <strong>• Cost</strong>: Makin kecil nilainya makin bagus.
                            </div>
                        </div>
                    </span>
                </label>
                <select name="tipe" class="form-select">
                    <option value="benefit" @selected($c->tipe=='benefit')>Benefit</option>
                    <option value="cost" @selected($c->tipe=='cost')>Cost</option>
                </select>
            </div>
        </div>
    </div>
    <div class="modal-footer"><button class="btn btn-accent">Simpan Perubahan</button></div>
</form></div></div>
@endforeach
@endsection
