<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') · SPK Driver Blitz</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root { --navy:#0b1b2b; --navy2:#12304a; --accent:#00ADEF; --accent-soft:rgba(0,174,239,.15); }
        body { font-family:'Inter',system-ui,sans-serif; background:#f3f6fa; color:#1e293b; }
        .sidebar { position:fixed; inset:0 auto 0 0; width:260px; height:100vh; z-index:1045; display:flex; flex-direction:column;
                   background:linear-gradient(180deg,var(--navy) 0%,var(--navy2) 100%);
                   transition:transform .28s cubic-bezier(0.4, 0, 0.2, 1);
                   box-shadow:4px 0 24px rgba(11,27,43,.12);
                   overflow-y:auto; overflow-x:hidden; }
        .sidebar::-webkit-scrollbar { width:4px; }
        .sidebar::-webkit-scrollbar-thumb { background:rgba(255,255,255,0.15); border-radius:4px; }
        .brand { padding:20px 20px; color:#fff; font-weight:700; font-size:1.05rem; display:flex; align-items:center; gap:.7rem; }
        .brand .logo { width:38px; height:38px; border-radius:11px; background:var(--accent); color:#fff;
                       display:grid; place-items:center; font-size:1.25rem; flex-shrink:0; }
        .brand small { display:block; font-weight:400; font-size:.7rem; color:#8aa0b5; }
        .nav-label { font-size:.68rem; text-transform:uppercase; letter-spacing:.09em; color:#5f7893; padding:1.1rem 1.6rem .35rem; }
        .sidebar .nav-link { color:#aebfd0; padding:.62rem 1.6rem; display:flex; align-items:center; gap:.8rem;
                             border-left:3px solid transparent; font-size:.93rem; }
        .sidebar .nav-link:hover { color:#fff; background:rgba(255,255,255,.05); }
        .sidebar .nav-link.active { color:#fff; background:var(--accent-soft); border-left-color:var(--accent); font-weight:600; }
        .sidebar .user { margin-top:auto; padding:16px 20px; border-top:1px solid rgba(255,255,255,.08); color:#cbd5e1; font-size:.85rem; }
        .main { margin-left:260px; min-height:100vh; transition:margin-left .28s cubic-bezier(0.4, 0, 0.2, 1); }
        .topbar { background:#fff; padding:14px 28px; display:flex; align-items:center; gap:1rem;
                  box-shadow:0 1px 0 #e5eaf1; position:sticky; top:0; z-index:1020; }
        .content { padding:28px; }
        .card-soft { border:0; border-radius:16px; box-shadow:0 2px 14px rgba(15,23,42,.06); background:#fff; }
        .card-soft .card-header { background:transparent; border-bottom:1px solid #eef2f7; font-weight:600; padding:1rem 1.25rem; }
        .stat { display:flex; align-items:center; gap:1rem; padding:1.2rem 1.3rem; }
        .stat .ico { width:50px; height:50px; border-radius:14px; display:grid; place-items:center; font-size:1.4rem; }
        .stat .lbl { color:#64748b; font-size:.82rem; }
        .stat .val { font-size:1.6rem; font-weight:700; line-height:1.1; }
        .table > :not(caption) > * > * { padding:.7rem .9rem; }
        .table thead th { font-size:.78rem; text-transform:uppercase; letter-spacing:.04em; color:#64748b; background:#f8fafc; border-bottom:1px solid #e5eaf1; }
        .btn-accent { background:var(--accent); color:#fff; border:0; font-weight:600; }
        .btn-accent:hover { background:#0098d3; color:#fff; }
        .rank-badge { width:34px; height:34px; border-radius:50%; display:inline-grid; place-items:center; font-weight:700; background:#eef2f7; }
        .rank-1 { background:#fde68a; color:#92400e; } .rank-2 { background:#e2e8f0; color:#334155; } .rank-3 { background:#fed7aa; color:#9a3412; }
        .podium { display:flex; align-items:flex-end; justify-content:center; gap:14px; padding-top:10px; }
        .podium .p { flex:1; max-width:210px; text-align:center; border-radius:16px 16px 0 0; padding:18px 10px 14px; color:#fff; }
        .podium .p1 { background:linear-gradient(180deg,#f59e0b,#d97706); height:210px; }
        .podium .p2 { background:linear-gradient(180deg,#94a3b8,#64748b); height:170px; }
        .podium .p3 { background:linear-gradient(180deg,#fb923c,#c2410c); height:145px; }
        .podium .medal { font-size:2rem; line-height:1; } .podium .nm { font-weight:700; margin-top:.3rem; } .podium .sc { opacity:.9; font-size:.9rem; }
        .formula { background:#f8fafc; border:1px dashed #cbd5e1; border-radius:12px; padding:.8rem 1rem; font-size:.88rem; }
        .empty { text-align:center; color:#94a3b8; padding:2.5rem 1rem; } .empty i { font-size:2.6rem; }

        /* Pagination Clean Styling */
        .card-footer nav { margin-bottom: 0 !important; }
        .card-footer .pagination { margin-bottom: 0 !important; gap: 4px; }
        .card-footer p.small { margin-bottom: 0 !important; color: #64748b; font-weight: 500; }
        .card-footer .page-link { border-radius: 8px !important; color: #475569; border-color: #e2e8f0; font-size: 0.84rem; padding: 0.38rem 0.75rem; transition: all 0.2s ease; }
        .card-footer .page-item.active .page-link { background-color: var(--accent); border-color: var(--accent); color: #fff; font-weight: 600; box-shadow: 0 2px 6px rgba(0,173,239,0.3); }
        .card-footer .page-link:hover { background-color: #f1f5f9; color: var(--navy); }

        /* Desktop Collapsed State (>= 992px) */
        @media (min-width: 992px) {
            html.sidebar-collapsed .sidebar,
            body.sidebar-collapsed .sidebar {
                transform: translateX(-100%);
            }
            html.sidebar-collapsed .main,
            body.sidebar-collapsed .main {
                margin-left: 0;
            }
        }

        /* Mobile Offcanvas State (< 992px) */
        @media (max-width: 991px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .main {
                margin-left: 0;
            }
            .content {
                padding: 18px;
            }
        }

        /* Mobile Backdrop Overlay */
        .sidebar-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(11, 27, 43, 0.55);
            backdrop-filter: blur(2px);
            z-index: 1040;
            opacity: 0;
            visibility: hidden;
            transition: opacity .25s ease, visibility .25s ease;
        }
        .sidebar-backdrop.show {
            opacity: 1;
            visibility: visible;
        }

        /* Sidebar Toggle & Close Buttons */
        .btn-sidebar-toggle {
            width: 40px;
            height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            background: #fff;
            color: #475569;
            transition: all .2s ease;
            box-shadow: 0 1px 3px rgba(0,0,0,.04);
            cursor: pointer;
        }
        .btn-sidebar-toggle:hover {
            background: #f8fafc;
            color: var(--accent);
            border-color: #cbd5e1;
        }
        .sidebar-close-btn {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.08);
            color: #94a3b8;
            border: 0;
            transition: all .2s ease;
            cursor: pointer;
        }
        .sidebar-close-btn:hover {
            background: rgba(255, 255, 255, 0.16);
            color: #fff;
        }

        /* Universal Hover Info Popover */
        .info-popover-wrapper {
            display: inline-flex;
            align-items: center;
            position: relative;
            vertical-align: middle;
        }
        .info-popover-trigger {
            width: 20px;
            height: 20px;
            min-width: 20px;
            border-radius: 50%;
            background: #00ADEF;
            color: #fff;
            font-weight: 700;
            font-size: 0.75rem;
            line-height: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border: 0;
            padding: 0;
            transition: transform .15s ease, background .15s ease, box-shadow .15s ease;
            box-shadow: 0 1px 3px rgba(0, 173, 239, 0.3);
            user-select: none;
        }
        .info-popover-trigger.sm {
            width: 17px;
            height: 17px;
            min-width: 17px;
            font-size: 0.65rem;
            background: #64748b;
        }
        .info-popover-trigger:hover,
        .info-popover-trigger:focus {
            background: #0088cc;
            transform: scale(1.12);
            box-shadow: 0 2px 6px rgba(0, 173, 239, 0.45);
        }
        .info-popover-trigger.sm:hover,
        .info-popover-trigger.sm:focus {
            background: #00ADEF;
        }
        .info-popover-content {
            display: none;
            position: absolute;
            top: calc(100% + 6px);
            left: 50%;
            transform: translateX(-50%);
            width: 320px;
            max-width: 85vw;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 14px;
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.16), 0 8px 10px -6px rgba(15, 23, 42, 0.08);
            z-index: 1070;
            pointer-events: none;
            text-align: left;
            white-space: normal;
            font-weight: normal;
            text-transform: none;
            letter-spacing: normal;
        }
        .info-popover-content.popover-start {
            left: 0;
            transform: none;
        }
        .info-popover-content.popover-end {
            left: auto;
            right: 0;
            transform: none;
        }
        .info-popover-trigger:hover + .info-popover-content,
        .info-popover-trigger:focus + .info-popover-content {
            display: block;
            animation: fadeInPopover 0.18s cubic-bezier(0.16, 1, 0.3, 1);
        }
        @keyframes fadeInPopover {
            from { opacity: 0; transform: translateY(-4px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .info-popover-content.popover-start {
            animation: fadeInPopoverStart 0.18s cubic-bezier(0.16, 1, 0.3, 1);
        }
        @keyframes fadeInPopoverStart {
            from { opacity: 0; transform: translateY(-4px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .info-popover-content.popover-end {
            animation: fadeInPopoverEnd 0.18s cubic-bezier(0.16, 1, 0.3, 1);
        }
        @keyframes fadeInPopoverEnd {
            from { opacity: 0; transform: translateY(-4px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
    <script>
        // Restore sidebar state before render to prevent layout jump on desktop
        if (window.innerWidth >= 992 && localStorage.getItem('spk_sidebar_collapsed') === 'true') {
            document.documentElement.classList.add('sidebar-collapsed');
        }
    </script>
    @stack('styles')
</head>
<body>
<aside class="sidebar" id="sidebar">
    <div class="brand justify-content-between">
        <div class="d-flex align-items-center gap-2">
            @if(file_exists(public_path('images/logo.png')))
                <img src="{{ asset('images/logo.png') }}" alt="Logo" style="max-height: 38px; max-width: 38px; object-fit: contain; border-radius: 8px;">
            @elseif(file_exists(public_path('images/logo.jpg')))
                <img src="{{ asset('images/logo.jpg') }}" alt="Logo" style="max-height: 38px; max-width: 38px; object-fit: contain; border-radius: 8px;">
            @else
                <span class="logo"><i class="bi bi-lightning-charge-fill"></i></span>
            @endif
            <div>
                <span style="line-height:1.2; display:block;">SPK Driver</span>
                <small>Blitz Electric Mobility</small>
            </div>
        </div>
        <button type="button" class="sidebar-close-btn" id="sidebarCloseBtn" aria-label="Tutup Sidebar" title="Tutup Menu Sidebar">
            <i class="bi bi-chevron-left"></i>
        </button>
    </div>
    <nav class="nav flex-column">
        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <div class="nav-label">Data Master</div>
        <a class="nav-link {{ request()->routeIs('drivers.*') ? 'active' : '' }}" href="{{ route('drivers.index') }}"><i class="bi bi-person-badge"></i> Data Driver</a>
        <a class="nav-link {{ request()->routeIs('criterias.*') ? 'active' : '' }}" href="{{ route('criterias.index') }}"><i class="bi bi-sliders"></i> Kriteria &amp; Bobot</a>
        <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}"><i class="bi bi-people"></i> Master User</a>
        <div class="nav-label">Proses SPK</div>
        <a class="nav-link {{ request()->routeIs('nilai.*') ? 'active' : '' }}" href="{{ route('nilai.index') }}"><i class="bi bi-pencil-square"></i> Input Penilaian</a>
        <a class="nav-link {{ request()->routeIs('perhitungan.*') ? 'active' : '' }}" href="{{ route('perhitungan.index') }}"><i class="bi bi-calculator"></i> Perhitungan SAW</a>
        <div class="nav-label">Laporan</div>
        <a class="nav-link {{ request()->routeIs('riwayat.*') ? 'active' : '' }}" href="{{ route('riwayat.index') }}"><i class="bi bi-graph-up-arrow"></i> Riwayat Ranking</a>
        <a class="nav-link {{ request()->routeIs('activity-logs.*') ? 'active' : '' }}" href="{{ route('activity-logs.index') }}"><i class="bi bi-shield-check"></i> Log Aktivitas</a>
    </nav>
    <div class="user mt-auto">
        <div class="mb-2 text-truncate" title="{{ auth()->user()->name }}"><i class="bi bi-person-circle"></i> {{ auth()->user()->name }}</div>
        <form method="POST" action="{{ route('logout') }}">@csrf
            <button class="btn btn-sm btn-outline-light w-100"><i class="bi bi-box-arrow-right"></i> Logout</button></form>
    </div>
</aside>

<!-- Backdrop overlay for mobile screen -->
<div class="sidebar-backdrop" id="sidebarBackdrop"></div>

<div class="main">
    <div class="topbar">
        <button type="button" class="btn-sidebar-toggle" id="sidebarToggle" aria-label="Buka/Tutup Sidebar" title="Buka / Tutup Sidebar">
            <i class="bi bi-list fs-5"></i>
        </button>
        <div>
            <h5 class="mb-0 fw-bold">@yield('title', 'Dashboard')</h5>
            <small class="text-muted">@yield('subtitle', 'Sistem Pendukung Keputusan Pemilihan Driver Terbaik · Metode SAW')</small>
        </div>
        <div class="ms-auto d-flex align-items-center gap-3">
            @yield('actions')
            <div class="d-none d-sm-flex align-items-center gap-2 border-start ps-3 ms-2">
                <span class="small fw-semibold text-secondary d-flex align-items-center gap-1">
                    <i class="bi bi-person-circle text-primary"></i> {{ auth()->user()->name }}
                </span>
                <form method="POST" action="{{ route('logout') }}" class="d-inline mb-0">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1 py-1 px-2.5" style="font-size: 0.8rem; border-radius: 8px;" title="Keluar / Logout">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div class="content">
        @if(session('ok'))   <div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle"></i> {{ session('ok') }}<button class="btn-close" data-bs-dismiss="alert"></button></div> @endif
        @if(session('warn')) <div class="alert alert-warning alert-dismissible fade show"><i class="bi bi-exclamation-triangle"></i> {{ session('warn') }}<button class="btn-close" data-bs-dismiss="alert"></button></div> @endif
        @if(isset($errors) && $errors->any())
            <div class="alert alert-danger alert-dismissible fade show"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul><button class="btn-close" data-bs-dismiss="alert"></button></div>
        @endif
        @yield('content')
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    (function () {
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarCloseBtn = document.getElementById('sidebarCloseBtn');
        const backdrop = document.getElementById('sidebarBackdrop');

        // Sync body class with html class on load
        if (document.documentElement.classList.contains('sidebar-collapsed')) {
            document.body.classList.add('sidebar-collapsed');
        }

        function triggerWindowResize() {
            setTimeout(function () {
                window.dispatchEvent(new Event('resize'));
            }, 300);
        }

        function toggleSidebar() {
            if (window.innerWidth >= 992) {
                // Desktop: collapse / expand
                const isCollapsed = document.body.classList.toggle('sidebar-collapsed');
                document.documentElement.classList.toggle('sidebar-collapsed', isCollapsed);
                try {
                    localStorage.setItem('spk_sidebar_collapsed', isCollapsed ? 'true' : 'false');
                } catch(e) {}
                triggerWindowResize();
            } else {
                // Mobile: drawer show / hide
                const isOpen = sidebar.classList.toggle('show');
                if (backdrop) {
                    backdrop.classList.toggle('show', isOpen);
                }
            }
        }

        function closeSidebar() {
            if (window.innerWidth >= 992) {
                document.body.classList.add('sidebar-collapsed');
                document.documentElement.classList.add('sidebar-collapsed');
                try {
                    localStorage.setItem('spk_sidebar_collapsed', 'true');
                } catch(e) {}
                triggerWindowResize();
            } else {
                sidebar.classList.remove('show');
                if (backdrop) {
                    backdrop.classList.remove('show');
                }
            }
        }

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', toggleSidebar);
        }
        if (sidebarCloseBtn) {
            sidebarCloseBtn.addEventListener('click', closeSidebar);
        }
        if (backdrop) {
            backdrop.addEventListener('click', closeSidebar);
        }

        // Close mobile drawer when clicking menu links on mobile
        const navLinks = sidebar ? sidebar.querySelectorAll('.nav-link') : [];
        navLinks.forEach(function (link) {
            link.addEventListener('click', function () {
                if (window.innerWidth < 992) {
                    closeSidebar();
                }
            });
        });

        // Close mobile sidebar if resized to desktop
        window.addEventListener('resize', function () {
            if (window.innerWidth >= 992) {
                sidebar.classList.remove('show');
                if (backdrop) {
                    backdrop.classList.remove('show');
                }
            }
        });
    })();
</script>
@stack('scripts')
</body>
</html>
