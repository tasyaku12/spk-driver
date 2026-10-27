<?php

use App\Http\Controllers\{
    ActivityLogController,
    CriteriaController,
    DashboardController,
    DriverController,
    DriverPortalController,
    LaporanController,
    NilaiController,
    PerhitunganController,
    ProfileController,
    RiwayatController,
    UserController
};
use Illuminate\Support\Facades\Route;

// Redirect awal sesuai status login & role
Route::get('/', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }
    return auth()->user()->isDriver()
        ? redirect()->route('driver.dashboard')
        : redirect()->route('dashboard');
});

// Route Khusus Admin (Full akses SPK)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('drivers', DriverController::class)->except(['show', 'create', 'edit']);
    Route::resource('users', UserController::class)->except(['show', 'create', 'edit']);

    Route::post('/criterias/normalisasi', [CriteriaController::class, 'normalisasi'])->name('criterias.normalisasi');
    Route::resource('criterias', CriteriaController::class)->except(['show', 'create', 'edit'])
        ->parameters(['criterias' => 'criteria']);

    Route::get('/nilai', [NilaiController::class, 'index'])->name('nilai.index');
    Route::post('/nilai', [NilaiController::class, 'store'])->name('nilai.store');
    Route::get('/nilai/template', [NilaiController::class, 'template'])->name('nilai.template');
    Route::post('/nilai/import', [NilaiController::class, 'import'])->name('nilai.import');

    Route::get('/perhitungan', [PerhitunganController::class, 'index'])->name('perhitungan.index');
    Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat.index');

    Route::get('/laporan/pdf', [LaporanController::class, 'pdf'])->name('laporan.pdf');
    Route::get('/laporan/excel', [LaporanController::class, 'excel'])->name('laporan.excel');

    // Audit Trail: Log Aktivitas Sistem
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::delete('/activity-logs/bulk-delete', [ActivityLogController::class, 'bulkDelete'])->name('activity-logs.bulk-delete');
    Route::delete('/activity-logs/{id}', [ActivityLogController::class, 'destroy'])->name('activity-logs.destroy');
});

// Route Khusus Driver (Tampilan Mobile-Friendly)
Route::middleware(['auth', 'role:driver'])->prefix('driver')->name('driver.')->group(function () {
    Route::get('/dashboard', [DriverPortalController::class, 'dashboard'])->name('dashboard');
    Route::get('/leaderboard', [DriverPortalController::class, 'leaderboard'])->name('leaderboard');
    Route::get('/laporan-pdf', [DriverPortalController::class, 'pdf'])->name('pdf');
    Route::get('/profile', [DriverPortalController::class, 'profile'])->name('profile');
    Route::post('/profile', [DriverPortalController::class, 'updateProfile'])->name('profile.update');
    Route::post('/password', [DriverPortalController::class, 'updatePassword'])->name('password.update');
});

// Route Bersama (Profile)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
