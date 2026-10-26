@extends('layouts.spk')
@section('title', 'Data Driver')
@section('subtitle', 'Kelola data driver yang akan dinilai')
@section('actions')
    <button class="btn btn-accent" data-bs-toggle="modal" data-bs-target="#mTambah"><i class="bi bi-plus-lg"></i> Tambah Driver</button>
@endsection

@section('content')
<div class="card-soft shadow-sm border-0">
    <div class="card-header bg-white py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2 border-bottom">
        <form method="GET" class="d-flex gap-2" style="max-width:420px">
            <input name="q" value="{{ $q }}" class="form-control form-control-sm" placeholder="Cari kode, nama, atau wilayah...">
            <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-search"></i></button>
            @if($q)<a href="{{ route('drivers.index') }}" class="btn btn-sm btn-light">Reset</a>@endif
        </form>
        <span class="badge text-bg-light border text-muted small">{{ $drivers->total() }} Mitra Terdaftar</span>
    </div>
    <div class="table-responsive"><table class="table mb-0 align-middle">
        <thead>
            <tr>
                <th width="120">
                    <span>Kode</span>
                    <span class="info-popover-wrapper ms-1">
                        <span class="info-popover-trigger sm" tabindex="0" role="button">!</span>
                        <div class="info-popover-content shadow-lg popover-start" style="width:260px;">
                            <div class="fw-bold text-dark small mb-1">Kode Driver (Alternatif)</div>
                            <div class="text-secondary small" style="font-size:0.75rem;">
                                ID unik driver (contoh: <code>D001</code>) yang digunakan sistem untuk menghubungkan profil dengan penilaian dan perhitungan ranking SAW.
                            </div>
                        </div>
                    </span>
                </th>
                <th>Nama Driver</th>
                <th>No HP</th>
                <th>
                    <span>Wilayah Hub</span>
                    <span class="info-popover-wrapper ms-1">
                        <span class="info-popover-trigger sm" tabindex="0" role="button">!</span>
                        <div class="info-popover-content shadow-lg popover-end" style="width:250px;">
                            <div class="fw-bold text-dark small mb-1">Wilayah Hub Operasional</div>
                            <div class="text-secondary small" style="font-size:0.75rem;">
                                Lokasi pangkalan (hub) armada Blitz tempat driver bertugas dan mengambil unit kendaraan.
                            </div>
                        </div>
                    </span>
                </th>
                <th width="130" class="text-end">Aksi</th>
            </tr>
        </thead>
        <tbody>
        @forelse($drivers as $d)
            <tr>
                <td><span class="badge bg-secondary-subtle text-secondary border font-monospace">{{ $d->kode }}</span></td>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center fw-bold flex-shrink-0 me-3 bg-light text-secondary border" style="width:36px;height:36px;min-width:36px;font-size:0.9rem;line-height:1;text-align:center;">
                            {{ strtoupper(substr($d->nama, 0, 1)) }}
                        </div>
                        <div class="fw-bold text-dark">{{ $d->nama }}</div>
                    </div>
                </td>
                <td>{{ $d->no_hp ?: '-' }}</td>
                <td>{{ $d->wilayah ?: '-' }}</td>
                <td class="text-end">
                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#mEdit{{ $d->id }}" title="Edit Driver"><i class="bi bi-pencil"></i></button>
                    <form method="POST" action="{{ route('drivers.destroy', $d) }}" class="d-inline" onsubmit="return confirm('Hapus driver {{ $d->nama }}? Data penilaiannya ikut terhapus.')">@csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger" title="Hapus Driver"><i class="bi bi-trash"></i></button></form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5"><div class="empty"><i class="bi bi-person-x"></i><p class="mb-0 mt-2">Data driver tidak ditemukan.</p></div></td></tr>
        @endforelse
        </tbody>
    </table></div>
    @if($drivers->hasPages())
        <div class="card-footer bg-white border-top border-light-subtle py-3 px-4" style="border-bottom-left-radius: 16px; border-bottom-right-radius: 16px;">
            <div class="px-2">
                {{ $drivers->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @endif
</div>

{{-- Modal tambah --}}
<div class="modal fade" id="mTambah" tabindex="-1"><div class="modal-dialog"><form method="POST" action="{{ route('drivers.store') }}" class="modal-content">@csrf
    <div class="modal-header"><h6 class="modal-title">Tambah Driver</h6><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body">
        <div class="mb-3">
            <label class="form-label d-flex align-items-center justify-content-between">
                <span class="d-flex align-items-center gap-1">
                    <span>Kode Driver</span>
                    <span class="info-popover-wrapper">
                        <span class="info-popover-trigger sm" tabindex="0" role="button">!</span>
                        <div class="info-popover-content shadow-lg popover-start" style="width:260px;">
                            <div class="fw-bold text-dark small mb-1">Kode Driver (Alternatif)</div>
                            <div class="text-secondary small" style="font-size:0.74rem;">
                                ID unik pengenal driver yang digenerate otomatis secara berurutan oleh sistem.
                            </div>
                        </div>
                    </span>
                </span>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2 py-0.5" style="font-size: 0.7rem;">
                    <i class="bi bi-magic me-1"></i>Otomatis
                </span>
            </label>
            <div class="input-group">
                <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-hash"></i></span>
                <input name="kode" value="{{ $nextKode }}" class="form-control bg-light font-monospace fw-bold text-dark border-start-0" readonly required>
            </div>
            <div class="form-text text-muted mt-1" style="font-size:0.75rem;">
                <i class="bi bi-check-circle-fill text-success me-1"></i>Kode terisi otomatis berurutan dari data driver terakhir.
            </div>
        </div>
        
        <label class="form-label">Nama Lengkap</label>
        <input name="nama" class="form-control mb-2" required placeholder="Nama driver">
        
        <label class="form-label">No HP</label>
        <input name="no_hp" class="form-control mb-2" placeholder="08xxxxxxxxxx">
        
        <label class="form-label d-flex align-items-center gap-1">
            <span>Wilayah Hub</span>
            <span class="info-popover-wrapper">
                <span class="info-popover-trigger sm" tabindex="0" role="button">!</span>
                <div class="info-popover-content shadow-lg popover-start" style="width:260px;">
                    <div class="fw-bold text-dark small mb-1">Wilayah Hub Operasional</div>
                    <div class="text-secondary small" style="font-size:0.74rem;">
                        Pangkalan (hub) penugasan driver armada (contoh: <code>Hub Jakarta Selatan</code>).
                    </div>
                </div>
            </span>
        </label>
        <input name="wilayah" class="form-control" placeholder="Hub Jakarta Selatan">
    </div>
    <div class="modal-footer"><button class="btn btn-accent">Simpan</button></div>
</form></div></div>

{{-- Modal edit per baris --}}
@foreach($drivers as $d)
<div class="modal fade" id="mEdit{{ $d->id }}" tabindex="-1"><div class="modal-dialog"><form method="POST" action="{{ route('drivers.update', $d) }}" class="modal-content">@csrf @method('PUT')
    <div class="modal-header"><h6 class="modal-title">Edit Driver</h6><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body">
        <label class="form-label d-flex align-items-center gap-1">
            <span>Kode</span>
            <span class="info-popover-wrapper">
                <span class="info-popover-trigger sm" tabindex="0" role="button">!</span>
                <div class="info-popover-content shadow-lg popover-start" style="width:260px;">
                    <div class="fw-bold text-dark small mb-1">Kode Driver (Alternatif)</div>
                    <div class="text-secondary small" style="font-size:0.74rem;">
                        ID unik pengenal driver (contoh: <code>{{ $d->kode }}</code>).
                    </div>
                </div>
            </span>
        </label>
        <input name="kode" value="{{ $d->kode }}" class="form-control mb-2" required>
        
        <label class="form-label">Nama Lengkap</label>
        <input name="nama" value="{{ $d->nama }}" class="form-control mb-2" required>
        
        <label class="form-label">No HP</label>
        <input name="no_hp" value="{{ $d->no_hp }}" class="form-control mb-2">
        
        <label class="form-label d-flex align-items-center gap-1">
            <span>Wilayah Hub</span>
            <span class="info-popover-wrapper">
                <span class="info-popover-trigger sm" tabindex="0" role="button">!</span>
                <div class="info-popover-content shadow-lg popover-start" style="width:260px;">
                    <div class="fw-bold text-dark small mb-1">Wilayah Hub Operasional</div>
                    <div class="text-secondary small" style="font-size:0.74rem;">
                        Pangkalan (hub) penugasan driver armada.
                    </div>
                </div>
            </span>
        </label>
        <input name="wilayah" value="{{ $d->wilayah }}" class="form-control">
    </div>
    <div class="modal-footer"><button class="btn btn-accent">Simpan Perubahan</button></div>
</form></div></div>
@endforeach
@endsection
