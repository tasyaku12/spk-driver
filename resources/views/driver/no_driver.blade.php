@extends('layouts.driver')
@section('title', 'Data Driver Belum Terhubung')

@section('content')
<div class="card-app text-center py-5">
    <div class="mb-3 text-warning">
        <i class="bi bi-exclamation-circle" style="font-size: 3.5rem;"></i>
    </div>
    <h5 class="fw-bold mb-2">Profil Driver Belum Ditautkan</h5>
    <p class="text-muted small mb-4">Akun login Anda belum terhubung dengan data Master Driver. Silakan hubungi Admin Blitz untuk menghubungkan akun Anda.</p>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-outline-danger rounded-pill px-4 btn-sm">Keluar</button>
    </form>
</div>
@endsection
