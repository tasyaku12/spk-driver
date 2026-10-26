@extends('layouts.spk')
@section('title', 'Log Aktivitas Sistem')
@section('subtitle', 'Audit trail resmi pencatatan seluruh operasional, perubahan data, dan aktivitas sistem')

@section('actions')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('activity-logs.index') }}" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-3 shadow-xs" title="Muat ulang log aktivitas">
            <i class="bi bi-arrow-clockwise"></i>
            <span>Refresh</span>
        </a>
    </div>
@endsection

@section('content')
<!-- Banner Info Audit Trail -->
<div class="alert alert-info border-0 shadow-sm rounded-4 p-3 mb-4 d-flex align-items-center gap-3" style="background: linear-gradient(135deg, #e0f2fe 0%, #f0f9ff 100%); border-left: 4px solid #00ADEF !important;">
    <div class="rounded-circle bg-white text-primary d-flex align-items-center justify-content-center shadow-xs flex-shrink-0" style="width: 44px; height: 44px; font-size: 1.3rem;">
        <i class="bi bi-shield-lock-fill text-primary"></i>
    </div>
    <div class="flex-grow-1">
        <div class="fw-bold text-dark mb-0.5" style="font-size: 0.95rem;">
            Audit Trail &bull; Pencatatan Aktivitas Sistem
        </div>
        <div class="text-secondary small" style="font-size: 0.82rem; line-height: 1.4;">
            Seluruh jejak aktivitas operasional, autentikasi, serta perubahan data SPK dicatat secara otomatis. Admin berwenang dapat <strong>menghapus</strong> log yang sudah tidak diperlukan untuk menjaga kebersihan data.
        </div>
    </div>
</div>

<!-- KPI Summary Cards -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card-soft shadow-sm border-0 h-100 p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px; font-size: 1.4rem;">
                    <i class="bi bi-journal-text"></i>
                </div>
                <div>
                    <div class="text-muted small fw-medium">Total Aktivitas</div>
                    <div class="fs-5 fw-bold text-dark lh-sm font-monospace">{{ number_format($stats['total']) }} Log</div>
                    <div class="text-muted" style="font-size: 0.75rem;">Sejak sistem diinisialisasi</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card-soft shadow-sm border-0 h-100 p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-3 bg-info-subtle text-info d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px; font-size: 1.4rem;">
                    <i class="bi bi-calendar-check"></i>
                </div>
                <div>
                    <div class="text-muted small fw-medium">Aktivitas Hari Ini</div>
                    <div class="fs-5 fw-bold text-dark lh-sm font-monospace">{{ number_format($stats['today']) }} Log</div>
                    <div class="text-muted" style="font-size: 0.75rem;">{{ now()->translatedFormat('d F Y') }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card-soft shadow-sm border-0 h-100 p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-3 bg-success-subtle text-success d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px; font-size: 1.4rem;">
                    <i class="bi bi-box-arrow-in-right"></i>
                </div>
                <div>
                    <div class="text-muted small fw-medium">Autentikasi / Sesi</div>
                    <div class="fs-5 fw-bold text-dark lh-sm font-monospace">{{ number_format($stats['logins']) }} Login</div>
                    <div class="text-muted" style="font-size: 0.75rem;">Riwayat akses akun</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card-soft shadow-sm border-0 h-100 p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-3 bg-secondary-subtle text-secondary d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px; font-size: 1.4rem;">
                    <i class="bi bi-gear-wide-connected"></i>
                </div>
                <div>
                    <div class="text-muted small fw-medium">Operasional SPK</div>
                    <div class="fs-5 fw-bold text-dark lh-sm font-monospace">{{ number_format($stats['operations']) }} Aksi</div>
                    <div class="text-muted" style="font-size: 0.75rem;">Perubahan data & kalkulasi</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter & Search Card -->
<div class="card-soft shadow-sm border-0 mb-4">
    <div class="card-body p-3 p-md-4">
        <form method="GET" action="{{ route('activity-logs.index') }}" class="row g-3 align-items-end">
            <!-- Search Keyword -->
            <div class="col-md-4">
                <label class="form-label small fw-bold text-secondary mb-1">Cari Aktivitas / Pengguna / IP</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" name="q" value="{{ $q }}" class="form-control border-start-0" placeholder="Ketik kata kunci pencarian...">
                </div>
            </div>

            <!-- Filter Modul -->
            <div class="col-sm-6 col-md-2">
                <label class="form-label small fw-bold text-secondary mb-1">Modul</label>
                <select name="module" class="form-select form-select-sm">
                    <option value="">Semua Modul</option>
                    @foreach($availableModules as $m)
                        <option value="{{ $m }}" @selected($module === $m)>{{ $m }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Aksi -->
            <div class="col-sm-6 col-md-2">
                <label class="form-label small fw-bold text-secondary mb-1">Jenis Aksi</label>
                <select name="action" class="form-select form-select-sm">
                    <option value="">Semua Aksi</option>
                    @foreach($availableActions as $a)
                        <option value="{{ $a }}" @selected($action === $a)>{{ $a }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Tanggal -->
            <div class="col-sm-6 col-md-2">
                <label class="form-label small fw-bold text-secondary mb-1">Tanggal</label>
                <input type="date" name="date" value="{{ $date }}" class="form-control form-control-sm">
            </div>

            <!-- Tombol Submit & Reset -->
            <div class="col-sm-6 col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-primary w-100 d-inline-flex align-items-center justify-content-center gap-1.5 py-1.5 shadow-xs">
                    <i class="bi bi-funnel-fill"></i>
                    <span>Filter</span>
                </button>
                @if($q || $module || $action || $date)
                    <a href="{{ route('activity-logs.index') }}" class="btn btn-sm btn-light border d-inline-flex align-items-center justify-content-center gap-1 py-1.5 text-secondary shadow-xs" title="Reset Filter">
                        <i class="bi bi-x-circle text-danger"></i>
                        <span>Reset</span>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Tabel Log Aktivitas -->
<div class="card-soft shadow-sm border-0 mb-4">
    <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-primary-subtle text-primary p-2 rounded-3">
                <i class="bi bi-clock-history fs-6"></i>
            </span>
            <h6 class="fw-bold mb-0 text-dark">Daftar Rekaman Aktivitas Sistem</h6>
        </div>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <!-- Tombol Hapus Terpilih (muncul saat ada checkbox tercentang) -->
            <button type="button" class="btn btn-sm btn-outline-danger d-none align-items-center gap-1 px-3 py-1.5 rounded-3" id="bulkDeleteBtn" title="Hapus log yang dipilih">
                <i class="bi bi-trash3"></i>
                <span>Hapus Terpilih (<span id="selectedCount">0</span>)</span>
            </button>
            <span class="badge bg-light text-secondary border px-2.5 py-1 small rounded-pill">
                Menampilkan {{ $logs->firstItem() ?? 0 }} - {{ $logs->lastItem() ?? 0 }} dari {{ $logs->total() }} Log
            </span>
        </div>
    </div>

    <!-- Form untuk bulk delete -->
    <form method="POST" action="{{ route('activity-logs.bulk-delete') }}" id="bulkDeleteForm">
        @csrf
        @method('DELETE')

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th style="width: 42px;" class="py-3 px-3 text-center">
                            <input type="checkbox" class="form-check-input" id="checkAll" title="Pilih semua" style="cursor: pointer;">
                        </th>
                        <th style="min-width: 170px;" class="py-3 px-3">Waktu &amp; Tanggal</th>
                        <th style="min-width: 180px;" class="py-3 px-3">Pelaku / Pengguna</th>
                        <th style="min-width: 120px;" class="py-3 px-3">Modul</th>
                        <th style="min-width: 110px;" class="py-3 px-3 text-center">Aksi</th>
                        <th style="min-width: 260px;" class="py-3 px-3">Deskripsi Aktivitas</th>
                        <th style="min-width: 120px;" class="py-3 px-3 text-center">Alamat IP</th>
                        <th style="width: 60px;" class="py-3 px-3 text-center">Hapus</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <!-- Checkbox -->
                            <td class="py-3 px-3 text-center">
                                <input type="checkbox" class="form-check-input log-checkbox" name="ids[]" value="{{ $log->id }}" style="cursor: pointer;">
                            </td>

                            <!-- Waktu -->
                            <td class="py-3 px-3">
                                <div class="fw-semibold text-dark" style="font-size: 0.85rem;">
                                    {{ $log->created_at->format('d/m/Y H:i:s') }}
                                </div>
                                <div class="text-muted small" style="font-size: 0.75rem;">
                                    <i class="bi bi-clock me-1"></i>{{ $log->created_at->diffForHumans() }}
                                </div>
                            </td>

                            <!-- Pelaku -->
                            <td class="py-3 px-3">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center fw-bold flex-shrink-0 bg-primary-subtle text-primary border border-primary-subtle me-3" style="width:34px;height:34px;min-width:34px;font-size:0.85rem;">
                                        {{ strtoupper(substr($log->user_name ?? 'S', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark" style="font-size: 0.88rem;">{{ $log->user_name ?? 'Sistem' }}</div>
                                        <div class="text-muted small" style="font-size: 0.72rem;">
                                            @if($log->role === 'admin')
                                                <span class="badge bg-primary text-white" style="font-size: 0.65rem;">Admin SPK</span>
                                            @elseif($log->role === 'driver')
                                                <span class="badge bg-info text-white" style="font-size: 0.65rem;">Mitra Driver</span>
                                            @else
                                                <span class="badge bg-secondary text-white" style="font-size: 0.65rem;">{{ ucfirst($log->role ?? 'Sistem') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Modul -->
                            <td class="py-3 px-3">
                                <span class="badge bg-light text-dark border px-2.5 py-1.5 fw-semibold" style="font-size: 0.78rem;">
                                    {{ $log->module }}
                                </span>
                            </td>

                            <!-- Aksi -->
                            <td class="py-3 px-3 text-center">
                                @php
                                    $act = strtoupper($log->action);
                                    $badgeClass = match($act) {
                                        'LOGIN'      => 'bg-success-subtle text-success border border-success-subtle',
                                        'LOGOUT'     => 'bg-secondary-subtle text-secondary border border-secondary-subtle',
                                        'CREATE'     => 'bg-success-subtle text-success border border-success-subtle',
                                        'UPDATE'     => 'bg-warning-subtle text-warning-emphasis border border-warning-subtle',
                                        'DELETE'     => 'bg-danger-subtle text-danger border border-danger-subtle',
                                        'CALCULATE'  => 'bg-primary-subtle text-primary border border-primary-subtle',
                                        'IMPORT'     => 'bg-info-subtle text-info-emphasis border border-info-subtle',
                                        'EXPORT'     => 'bg-purple-subtle text-purple border border-purple-subtle',
                                        default      => 'bg-light text-dark border',
                                    };
                                @endphp
                                <span class="badge rounded-pill px-2.5 py-1 fw-bold font-monospace {{ $badgeClass }}" style="font-size: 0.75rem;">
                                    {{ $act }}
                                </span>
                            </td>

                            <!-- Deskripsi -->
                            <td class="py-3 px-3">
                                <div class="text-dark fw-medium" style="font-size: 0.86rem; line-height: 1.45;">
                                    {{ $log->description }}
                                </div>
                                @if(!empty($log->properties))
                                    <div class="mt-1">
                                        <button class="btn btn-xs btn-link p-0 text-decoration-none text-primary small" type="button" data-bs-toggle="collapse" data-bs-target="#prop-{{ $log->id }}" style="font-size: 0.74rem;">
                                            <i class="bi bi-chevron-down"></i> Rincian Data
                                        </button>
                                        <div class="collapse mt-1" id="prop-{{ $log->id }}">
                                            <pre class="bg-light p-2 rounded border small mb-0 font-monospace" style="font-size: 0.7rem; max-height: 120px; overflow-y: auto;">{{ json_encode($log->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                        </div>
                                    </div>
                                @endif
                            </td>

                            <!-- Alamat IP -->
                            <td class="py-3 px-3 text-center">
                                <div class="font-monospace text-secondary fw-semibold" style="font-size: 0.8rem;">
                                    <i class="bi bi-geo-alt me-1 text-muted"></i>{{ $log->ip_address ?? '-' }}
                                </div>
                            </td>

                            <!-- Tombol Hapus Individual -->
                            <td class="py-3 px-3 text-center">
                                <button type="button" class="btn btn-sm btn-outline-danger border-0 p-1 d-inline-flex align-items-center justify-content-center rounded-2 btn-delete-single"
                                    data-id="{{ $log->id }}"
                                    data-desc="{{ Str::limit($log->description, 60) }}"
                                    title="Hapus log ini"
                                    style="width: 30px; height: 30px;">
                                    <i class="bi bi-trash3" style="font-size: 0.85rem;"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <div class="py-4">
                                    <i class="bi bi-shield-check fs-1 text-muted opacity-50 d-block mb-2"></i>
                                    <h6 class="fw-bold text-dark mb-1">Belum Ada Rekaman Log Aktivitas</h6>
                                    <p class="text-muted small mb-3">Tidak ditemukan aktivitas yang cocok dengan filter pencarian Anda.</p>
                                    @if($q || $module || $action || $date)
                                        <a href="{{ route('activity-logs.index') }}" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1 px-3 py-1.5 rounded-3">
                                            <i class="bi bi-arrow-clockwise"></i>
                                            <span>Reset Filter</span>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </form>

    @if($logs->hasPages())
        <div class="card-footer bg-white py-3 px-4 border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
            <span class="text-muted small">
                Halaman {{ $logs->currentPage() }} dari {{ $logs->lastPage() }}
            </span>
            <div>
                {{ $logs->links() }}
            </div>
        </div>
    @endif
</div>

<!-- Modal Konfirmasi Hapus Satuan -->
<div class="modal fade" id="deleteSingleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content rounded-4 border-0 shadow-lg p-2">
            <div class="modal-body text-center pt-4">
                <div class="text-danger mb-3">
                    <i class="bi bi-exclamation-triangle-fill" style="font-size: 2.8rem;"></i>
                </div>
                <h6 class="fw-bold mb-1">Hapus Log Ini?</h6>
                <p class="text-muted small mb-1" id="deleteSingleDesc" style="font-size: 0.82rem;"></p>
                <p class="text-danger small mb-3" style="font-size: 0.75rem;">Data yang dihapus tidak dapat dikembalikan.</p>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-light rounded-pill w-100" data-bs-dismiss="modal">Batal</button>
                    <form method="POST" id="deleteSingleForm" class="w-100">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger rounded-pill w-100">Ya, Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus Massal -->
<div class="modal fade" id="deleteBulkModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content rounded-4 border-0 shadow-lg p-2">
            <div class="modal-body text-center pt-4">
                <div class="text-danger mb-3">
                    <i class="bi bi-trash3-fill" style="font-size: 2.8rem;"></i>
                </div>
                <h6 class="fw-bold mb-1">Hapus <span id="bulkCountModal">0</span> Log Aktivitas?</h6>
                <p class="text-muted small mb-1" style="font-size: 0.82rem;">Semua log yang dicentang akan dihapus secara permanen.</p>
                <p class="text-danger small mb-3" style="font-size: 0.75rem;">Tindakan ini tidak dapat dibatalkan.</p>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-light rounded-pill w-100" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger rounded-pill w-100" id="confirmBulkDelete">Ya, Hapus Semua</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function() {
    const checkAll      = document.getElementById('checkAll');
    const checkboxes    = () => document.querySelectorAll('.log-checkbox');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
    const selectedCount = document.getElementById('selectedCount');
    const bulkDeleteForm = document.getElementById('bulkDeleteForm');

    function updateBulkUI() {
        const checked = document.querySelectorAll('.log-checkbox:checked');
        const count = checked.length;
        selectedCount.textContent = count;
        if (count > 0) {
            bulkDeleteBtn.classList.remove('d-none');
            bulkDeleteBtn.classList.add('d-inline-flex');
        } else {
            bulkDeleteBtn.classList.add('d-none');
            bulkDeleteBtn.classList.remove('d-inline-flex');
        }
        // Update "check all" state
        const all = checkboxes();
        checkAll.checked = all.length > 0 && checked.length === all.length;
        checkAll.indeterminate = checked.length > 0 && checked.length < all.length;
    }

    // Check All toggle
    checkAll.addEventListener('change', function() {
        checkboxes().forEach(cb => cb.checked = this.checked);
        updateBulkUI();
    });

    // Individual checkbox change
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('log-checkbox')) {
            updateBulkUI();
        }
    });

    // Bulk delete button → open modal
    bulkDeleteBtn.addEventListener('click', function() {
        const checked = document.querySelectorAll('.log-checkbox:checked');
        document.getElementById('bulkCountModal').textContent = checked.length;
        new bootstrap.Modal(document.getElementById('deleteBulkModal')).show();
    });

    // Confirm bulk delete → submit form
    document.getElementById('confirmBulkDelete').addEventListener('click', function() {
        bulkDeleteForm.submit();
    });

    // Single delete buttons → open modal
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-delete-single');
        if (!btn) return;
        e.preventDefault();
        const id   = btn.dataset.id;
        const desc = btn.dataset.desc;
        document.getElementById('deleteSingleDesc').textContent = '"' + desc + '"';
        document.getElementById('deleteSingleForm').action = '/activity-logs/' + id;
        new bootstrap.Modal(document.getElementById('deleteSingleModal')).show();
    });
})();
</script>
@endpush
