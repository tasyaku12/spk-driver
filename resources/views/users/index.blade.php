@extends('layouts.spk')
@section('title', 'Master User & Akses Role')
@section('subtitle', 'Manajemen akun pengguna sistem, penugasan role (Admin / Driver), dan penambahan user.')
@section('actions')
    <button class="btn btn-accent" data-bs-toggle="modal" data-bs-target="#mTambahUser">
        <i class="bi bi-person-plus-fill me-1"></i> Tambah User Baru
    </button>
@endsection

@section('content')
<!-- Row Statistik User -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card-soft stat">
            <div class="ico bg-primary bg-opacity-10 text-primary">
                <i class="bi bi-people-fill"></i>
            </div>
            <div>
                <div class="lbl">Total Akun Pengguna</div>
                <div class="val text-primary">{{ $stats['total'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-soft stat">
            <div class="ico bg-info bg-opacity-10 text-info">
                <i class="bi bi-shield-lock-fill"></i>
            </div>
            <div>
                <div class="lbl">Staf / Admin SPK</div>
                <div class="val text-dark">{{ $stats['admin'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-soft stat">
            <div class="ico bg-success bg-opacity-10 text-success">
                <i class="bi bi-ev-front-fill"></i>
            </div>
            <div>
                <div class="lbl">Mitra Driver Armada</div>
                <div class="val text-success">{{ $stats['driver'] }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Table Card Master User -->
<div class="card-soft shadow-sm border-0">
    <div class="card-header bg-white py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2 border-bottom">
        <form method="GET" class="d-flex gap-2" style="max-width:420px">
            <input name="q" value="{{ $q ?? '' }}" class="form-control form-control-sm" placeholder="Cari nama, email, role, atau kode driver...">
            <button class="btn btn-sm btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
            @if(!empty($q))<a href="{{ route('users.index') }}" class="btn btn-sm btn-light">Reset</a>@endif
        </form>
        <span class="badge text-bg-light border text-muted small">{{ $users->count() }} Pengguna Terdaftar</span>
    </div>

    <div class="table-responsive">
        <table class="table mb-0 align-middle">
            <thead>
                <tr>
                    <th width="60">No</th>
                    <th>Nama &amp; Email</th>
                    <th>
                        <span>Hak Akses (Role)</span>
                        <span class="info-popover-wrapper ms-1">
                            <span class="info-popover-trigger sm" tabindex="0" role="button">!</span>
                            <div class="info-popover-content shadow-lg popover-start" style="width:280px;">
                                <div class="fw-bold text-dark small mb-1">Peran Pengguna (Role)</div>
                                <div class="text-secondary small" style="font-size:0.75rem;">
                                    <strong>• Admin SPK</strong>: Akses penuh mengelola kriteria, bobot, penilaian, hitung SAW, dan master user.<br>
                                    <strong>• Driver Armada</strong>: Hanya dapat login ke portal khusus driver untuk melihat performa pribadinya.
                                </div>
                            </div>
                        </span>
                    </th>
                    <th>
                        <span>Profil Driver Terhubung</span>
                        <span class="info-popover-wrapper ms-1">
                            <span class="info-popover-trigger sm" tabindex="0" role="button">!</span>
                            <div class="info-popover-content shadow-lg popover-start" style="width:280px;">
                                <div class="fw-bold text-dark small mb-1">Tautan Profil Driver</div>
                                <div class="text-secondary small" style="font-size:0.75rem;">
                                    Menghubungkan akun login pengguna dengan data driver (kode unik). Wajib bagi role Driver agar sistem dapat menampilkan perankingan miliknya.
                                </div>
                            </div>
                        </span>
                    </th>
                    <th>Tanggal Terdaftar</th>
                    <th width="140" class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse($users as $index => $u)
                <tr>
                    <td class="text-muted small">{{ $index + 1 }}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center fw-bold flex-shrink-0 me-3 {{ $u->role === 'admin' ? 'bg-primary-subtle text-primary border border-primary-subtle' : 'bg-light text-secondary border' }}" style="width:38px;height:38px;min-width:38px;font-size:0.95rem;line-height:1;text-align:center;">
                                {{ strtoupper(substr($u->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="fw-bold text-dark" style="font-size:0.93rem;">{{ $u->name }}</div>
                                <div class="small text-muted">{{ $u->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        @if($u->role === 'admin')
                            <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary border border-primary-subtle px-3 py-1">
                                <i class="bi bi-shield-check me-1"></i> Admin SPK
                            </span>
                        @else
                            <span class="badge rounded-pill bg-info bg-opacity-10 text-dark border border-info-subtle px-3 py-1">
                                <i class="bi bi-ev-front me-1 text-info"></i> Driver Armada
                            </span>
                        @endif
                    </td>
                    <td>
                        @if($u->driver)
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-secondary-subtle text-secondary border font-monospace">{{ $u->driver->kode }}</span>
                                <span class="small fw-semibold">{{ $u->driver->nama }}</span>
                            </div>
                            <div class="text-muted" style="font-size:0.75rem;">{{ $u->driver->wilayah ?: 'Hub Operasional' }}</div>
                        @else
                            <span class="text-muted small">-</span>
                        @endif
                    </td>
                    <td class="text-muted small">
                        {{ $u->created_at ? $u->created_at->format('d M Y, H:i') : '-' }}
                    </td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#mEditUser{{ $u->id }}" title="Ubah Data & Role">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        
                        @if(auth()->id() !== $u->id)
                            <form method="POST" action="{{ route('users.destroy', $u) }}" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun {{ $u->name }}?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" title="Hapus User">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        @else
                            <button class="btn btn-sm btn-outline-secondary disabled" title="Akun Anda Saat Ini">
                                <i class="bi bi-lock-fill"></i>
                            </button>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">
                        <div class="empty py-4 text-center">
                            <i class="bi bi-person-x fs-1 text-muted"></i>
                            @if(!empty($q))
                                <p class="mb-0 mt-2">Data pengguna dengan pencarian "<strong>{{ $q }}</strong>" tidak ditemukan.</p>
                                <a href="{{ route('users.index') }}" class="btn btn-sm btn-light mt-2 shadow-xs">Reset Pencarian</a>
                            @else
                                <p class="mb-0 mt-2">Belum ada data user terdaftar.</p>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah User Baru -->
<div class="modal fade" id="mTambahUser" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('users.store') }}" class="modal-content border-0 shadow">
            @csrf
            <div class="modal-header border-bottom">
                <h6 class="modal-title fw-bold"><i class="bi bi-person-plus text-primary me-1"></i> Tambah User Baru</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label fw-semibold small">Nama Lengkap</label>
                    <input name="name" class="form-control" required placeholder="Contoh: Dimas Setiawan">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small">Alamat Email</label>
                    <input name="email" type="email" class="form-control" required placeholder="nama@blitz.test">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small d-flex align-items-center gap-1">
                        <span>Hak Akses (Role)</span>
                        <span class="info-popover-wrapper">
                            <span class="info-popover-trigger sm" tabindex="0" role="button">!</span>
                            <div class="info-popover-content shadow-lg popover-start" style="width:280px;">
                                <div class="fw-bold text-dark small mb-1">Peran Pengguna (Role)</div>
                                <div class="text-secondary small" style="font-size:0.74rem;">
                                    <strong>• Admin</strong>: Akses penuh dashboard web, kriteria, penilaian, dan rumus SPK.<br>
                                    <strong>• Driver</strong>: Akses portal driver mobile/web untuk melihat performa dan perankingan dirinya.
                                </div>
                            </div>
                        </span>
                    </label>
                    <select name="role" id="roleSelectTambah" class="form-select" required onchange="toggleDriverSelect('Tambah')">
                        <option value="driver" selected>Driver (Akses Portal Driver Mobile & Web)</option>
                        <option value="admin">Admin (Akses Penuh Dashboard Web SPK)</option>
                    </select>
                </div>

                <div class="mb-3" id="driverGroupTambah">
                    <label class="form-label fw-semibold small d-flex align-items-center gap-1">
                        <span>Hubungkan ke Data Driver</span>
                        <span class="info-popover-wrapper">
                            <span class="info-popover-trigger sm" tabindex="0" role="button">!</span>
                            <div class="info-popover-content shadow-lg popover-start" style="width:280px;">
                                <div class="fw-bold text-dark small mb-1">Tautan Profil Driver</div>
                                <div class="text-secondary small" style="font-size:0.74rem;">
                                    Pilih data driver yang sudah ada agar terhubung ke akun login ini, atau biarkan kosong untuk dibuatkan otomatis.
                                </div>
                            </div>
                        </span>
                    </label>
                    <select name="driver_id" class="form-select">
                        <option value="">-- Buat Profil Driver Baru Otomatis --</option>
                        @foreach($drivers as $d)
                            <option value="{{ $d->id }}">{{ $d->kode }} - {{ $d->nama }} ({{ $d->wilayah ?: 'Hub' }})</option>
                        @endforeach
                    </select>
                    <small class="text-muted d-block mt-1" style="font-size:0.75rem;">
                        Pilih driver jika akun ini milik driver yang sudah ada, atau biarkan kosong untuk dibuatkan otomatis.
                    </small>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small">Password Akun</label>
                    <input name="password" type="password" class="form-control" required minlength="8" placeholder="Minimal 8 karakter">
                    <small class="text-muted d-block mt-1" style="font-size:0.75rem;">
                        Standar keamanan sistem: minimal 8 karakter.
                    </small>
                </div>
            </div>
            <div class="modal-footer border-top bg-light">
                <button type="button" class="btn btn-sm btn-light border" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-sm btn-accent">Simpan User</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit User & Ubah Role -->
@foreach($users as $u)
<div class="modal fade" id="mEditUser{{ $u->id }}" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('users.update', $u) }}" class="modal-content border-0 shadow">
            @csrf
            @method('PUT')
            <div class="modal-header border-bottom">
                <h6 class="modal-title fw-bold">
                    <i class="bi bi-pencil-square text-primary me-1"></i> Edit User &amp; Ubah Role: {{ $u->name }}
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label fw-semibold small">Nama Lengkap</label>
                    <input name="name" value="{{ $u->name }}" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small">Alamat Email</label>
                    <input name="email" type="email" value="{{ $u->email }}" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small d-flex align-items-center gap-1">
                        <span>Hak Akses (Role)</span>
                        <span class="info-popover-wrapper">
                            <span class="info-popover-trigger sm" tabindex="0" role="button">!</span>
                            <div class="info-popover-content shadow-lg popover-start" style="width:280px;">
                                <div class="fw-bold text-dark small mb-1">Peran Pengguna (Role)</div>
                                <div class="text-secondary small" style="font-size:0.74rem;">
                                    <strong>• Admin</strong>: Akses penuh dashboard web, kriteria, penilaian, dan rumus SPK.<br>
                                    <strong>• Driver</strong>: Akses portal driver mobile/web untuk melihat performa dan perankingan dirinya.
                                </div>
                            </div>
                        </span>
                    </label>
                    <select name="role" id="roleSelectEdit{{ $u->id }}" class="form-select" required onchange="toggleDriverSelect('Edit{{ $u->id }}')">
                        <option value="driver" {{ $u->role === 'driver' ? 'selected' : '' }}>Driver (Akses Portal Driver)</option>
                        <option value="admin" {{ $u->role === 'admin' ? 'selected' : '' }}>Admin (Akses Penuh Dashboard Web SPK)</option>
                    </select>
                    <small class="text-muted d-block mt-1" style="font-size:0.75rem;">
                        Anda dapat mengubah hak akses role akun ini kapan saja.
                    </small>
                </div>

                <div class="mb-3 {{ $u->role === 'admin' ? 'd-none' : '' }}" id="driverGroupEdit{{ $u->id }}">
                    <label class="form-label fw-semibold small d-flex align-items-center gap-1">
                        <span>Hubungkan ke Profil Driver</span>
                        <span class="info-popover-wrapper">
                            <span class="info-popover-trigger sm" tabindex="0" role="button">!</span>
                            <div class="info-popover-content shadow-lg popover-start" style="width:280px;">
                                <div class="fw-bold text-dark small mb-1">Tautan Profil Driver</div>
                                <div class="text-secondary small" style="font-size:0.74rem;">
                                    Menghubungkan user ini dengan kode driver tertentu di master driver.
                                </div>
                            </div>
                        </span>
                    </label>
                    <select name="driver_id" class="form-select">
                        <option value="">-- Buat Profil Driver Baru Otomatis --</option>
                        @foreach($drivers as $d)
                            <option value="{{ $d->id }}" {{ $u->driver_id == $d->id ? 'selected' : '' }}>
                                {{ $d->kode }} - {{ $d->nama }} ({{ $d->wilayah ?: 'Hub' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-2">
                    <label class="form-label fw-semibold small">Password Baru <span class="text-muted fw-normal">(Kosongkan jika tidak diubah)</span></label>
                    <input name="password" type="password" class="form-control" minlength="8" placeholder="Minimal 8 karakter jika ingin diubah">
                </div>
            </div>
            <div class="modal-footer border-top bg-light">
                <button type="button" class="btn btn-sm btn-light border" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-sm btn-accent">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endforeach

@push('scripts')
<script>
    function toggleDriverSelect(suffix) {
        const role = document.getElementById('roleSelect' + suffix).value;
        const group = document.getElementById('driverGroup' + suffix);
        if (group) {
            if (role === 'driver') {
                group.classList.remove('d-none');
            } else {
                group.classList.add('d-none');
            }
        }
    }
</script>
@endpush
@endsection
