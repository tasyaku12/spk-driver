@extends('layouts.driver')
@section('title', 'Pengaturan Akun Driver')

@section('content')
<!-- Header Page -->
<div class="mb-3">
    <div class="text-muted small">Kelola Profil &amp; Keamanan</div>
    <h5 class="fw-bold mb-0 text-dark">Pengaturan Akun</h5>
</div>

<!-- Alerts -->
@if(session('ok'))
    <div class="alert alert-success border-0 rounded-4 shadow-sm mb-3 d-flex align-items-center gap-2 py-2 px-3">
        <i class="bi bi-check-circle-fill text-success fs-5"></i>
        <div class="small fw-semibold">{{ session('ok') }}</div>
    </div>
@endif

@if(isset($errors) && $errors->any())
    <div class="alert alert-danger border-0 rounded-4 shadow-sm mb-3 py-2 px-3">
        <div class="d-flex align-items-center gap-2 mb-1">
            <i class="bi bi-exclamation-circle-fill text-danger fs-5"></i>
            <span class="small fw-bold">Terdapat kendala:</span>
        </div>
        <ul class="mb-0 small ps-3">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Hero Profile Card -->
<div class="driver-hero-card mb-3">
    <div class="d-flex align-items-center gap-3">
        <div style="width: 60px; height: 60px; background: rgba(0, 174, 239, 0.2); border: 2px solid var(--accent); border-radius: 20px; display: grid; place-items: center; color: var(--accent); font-size: 1.8rem; box-shadow: 0 4px 15px rgba(0,174,239,0.3);">
            <i class="bi bi-person-fill"></i>
        </div>
        <div>
            <div class="d-flex align-items-center gap-2">
                <h5 class="fw-bold text-white mb-0">{{ $user->name }}</h5>
                <span class="badge bg-white text-dark rounded-pill px-2 py-0.5 small fw-bold font-monospace">
                    {{ $driver ? $driver->kode : 'DRIVER' }}
                </span>
            </div>
            <div class="small text-white-50 mt-1">
                <i class="bi bi-geo-alt-fill text-warning me-1"></i>{{ $driver ? ($driver->wilayah ?: 'Hub Operasional') : 'Hub Blitz' }}
            </div>
            <div class="small mt-1 text-emerald-400" style="color: #4ade80;">
                <i class="bi bi-patch-check-fill me-1"></i> Mitra Driver Motor Listrik Aktif
            </div>
        </div>
    </div>
</div>

<!-- Card 1: Ubah Data Profil -->
<div class="card-app mb-3">
    <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
        <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width: 32px; height: 32px;">
            <i class="bi bi-person-lines-fill"></i>
        </div>
        <div>
            <h6 class="fw-bold mb-0">Informasi Driver</h6>
            <div class="text-muted" style="font-size: 0.72rem;">Perbarui kontak dan data identitas Anda</div>
        </div>
    </div>

    <form method="POST" action="{{ route('driver.profile.update') }}">
        @csrf

        <!-- Kode Driver (Readonly) -->
        <div class="mb-3">
            <label class="form-label small fw-semibold text-muted mb-1">ID Kode Driver</label>
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light border-0"><i class="bi bi-badge-ad"></i></span>
                <input type="text" class="form-control bg-light border-0 fw-bold font-monospace" value="{{ $driver ? $driver->kode : '-' }}" readonly>
            </div>
            <small class="text-muted" style="font-size: 0.7rem;">ID Driver dikelola secara terpusat oleh Admin SPK.</small>
        </div>

        <!-- Nama Lengkap -->
        <div class="mb-3">
            <label for="name" class="form-label small fw-semibold text-dark mb-1">Nama Lengkap</label>
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light border-0"><i class="bi bi-person"></i></span>
                <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required
                       class="form-control border-slate-200">
            </div>
        </div>

        <!-- Email -->
        <div class="mb-3">
            <label for="email" class="form-label small fw-semibold text-dark mb-1">Alamat Email</label>
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light border-0"><i class="bi bi-envelope"></i></span>
                <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required
                       class="form-control border-slate-200">
            </div>
        </div>

        <!-- No HP / WA -->
        <div class="mb-3">
            <label for="no_hp" class="form-label small fw-semibold text-dark mb-1">Nomor WhatsApp / HP</label>
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light border-0"><i class="bi bi-telephone"></i></span>
                <input id="no_hp" type="text" name="no_hp" value="{{ old('no_hp', $driver ? $driver->no_hp : '') }}"
                       placeholder="0812xxxxxxxx"
                       class="form-control border-slate-200">
            </div>
        </div>

        <!-- Wilayah Hub -->
        <div class="mb-3">
            <label for="wilayah" class="form-label small fw-semibold text-dark mb-1">Wilayah / Hub Operasional</label>
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light border-0"><i class="bi bi-geo-alt"></i></span>
                <input id="wilayah" type="text" name="wilayah" value="{{ old('wilayah', $driver ? $driver->wilayah : '') }}"
                       placeholder="Contoh: Hub Jakarta Selatan"
                       class="form-control border-slate-200">
            </div>
        </div>

        <button type="submit" class="btn btn-sm btn-accent w-100 rounded-pill py-2 fw-semibold">
            <i class="bi bi-check2-circle me-1"></i> Simpan Perubahan Profil
        </button>
    </form>
</div>

<!-- Card 2: Keamanan & Ganti Password -->
<div class="card-app mb-3">
    <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
        <div class="rounded-circle bg-warning bg-opacity-10 text-warning d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width: 32px; height: 32px;">
            <i class="bi bi-shield-lock-fill"></i>
        </div>
        <div>
            <h6 class="fw-bold mb-0">Keamanan Kata Sandi</h6>
            <div class="text-muted" style="font-size: 0.72rem;">Ganti password akun login Anda secara berkala (minimal 8 karakter)</div>
        </div>
    </div>

    <form method="POST" action="{{ route('driver.password.update') }}">
        @csrf

        <div class="mb-2.5">
            <label for="current_password" class="form-label small fw-semibold text-dark mb-1">Password Saat Ini</label>
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light border-0"><i class="bi bi-key"></i></span>
                <input id="current_password" type="password" name="current_password" required
                       placeholder="Masukkan password lama"
                       class="form-control border-slate-200">
            </div>
        </div>

        <div class="mb-2.5">
            <label for="new_password" class="form-label small fw-semibold text-dark mb-1">Password Baru</label>
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light border-0"><i class="bi bi-lock"></i></span>
                <input id="new_password" type="password" name="password" required minlength="8"
                       placeholder="Minimal 8 karakter"
                       class="form-control border-slate-200">
            </div>
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label small fw-semibold text-dark mb-1">Konfirmasi Password Baru</label>
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light border-0"><i class="bi bi-shield-check"></i></span>
                <input id="password_confirmation" type="password" name="password_confirmation" required minlength="8"
                       placeholder="Ulangi password baru"
                       class="form-control border-slate-200">
            </div>
        </div>

        <button type="submit" class="btn btn-sm btn-outline-dark w-100 rounded-pill py-2 fw-semibold">
            <i class="bi bi-arrow-repeat me-1"></i> Perbarui Kata Sandi
        </button>
    </form>
</div>

<!-- Card 3: Info Akun & Keluar -->
<div class="card-app text-center">
    <div class="small text-muted mb-2">
        Terdaftar sejak: <strong>{{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}</strong>
    </div>
    <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#logoutModal">
        <i class="bi bi-box-arrow-right me-1"></i> Keluar dari Aplikasi
    </button>
</div>
@endsection
