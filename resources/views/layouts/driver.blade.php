<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, viewport-fit=cover">
    <meta name="theme-color" content="#0b1b2b">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

    <title>@yield('title', 'Portal Driver') · Blitz Electric Mobility</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --navy: #0b1b2b;
            --navy2: #12304a;
            --accent: #00ADEF;
            --accent-soft: rgba(0, 174, 239, 0.15);
            --accent-glow: rgba(0, 174, 239, 0.35);
            --bg-app: #f4f7fb;
            --card-radius: 18px;
        }

        * {
            -webkit-tap-highlight-color: transparent;
        }

        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            background-color: var(--bg-app);
            color: #1e293b;
            margin: 0;
            padding: 0;
            padding-bottom: 84px; /* Space for bottom nav on mobile */
            min-height: 100vh;
        }

        /* Top Mobile App Header */
        .app-header {
            background: linear-gradient(135deg, var(--navy) 0%, var(--navy2) 100%);
            color: #fff;
            padding: 16px 20px;
            position: sticky;
            top: 0;
            z-index: 1030;
            box-shadow: 0 4px 20px rgba(11, 27, 43, 0.15);
        }

        .app-header .brand-box {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .app-header .logo-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: var(--accent);
            color: var(--navy);
            display: grid;
            place-items: center;
            font-size: 1.3rem;
            font-weight: 800;
            box-shadow: 0 4px 12px rgba(20, 200, 160, 0.3);
        }

        .app-header .brand-title {
            font-weight: 800;
            font-size: 1.15rem;
            letter-spacing: -0.02em;
            line-height: 1.2;
        }

        .app-header .brand-sub {
            font-size: 0.72rem;
            color: #94a3b8;
            letter-spacing: 0.02em;
        }

        /* Bottom App Navigation Bar (Mobile Native Feel) */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: #ffffff;
            border-top: 1px solid #e2e8f0;
            padding: 8px 16px 12px;
            display: flex;
            justify-content: space-around;
            align-items: center;
            z-index: 1040;
            box-shadow: 0 -4px 20px rgba(15, 23, 42, 0.06);
        }

        .bottom-nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
            color: #64748b;
            font-size: 0.72rem;
            font-weight: 600;
            transition: all 0.2s ease;
            position: relative;
            padding: 4px 12px;
            border-radius: 12px;
        }

        .bottom-nav-item i {
            font-size: 1.35rem;
            margin-bottom: 2px;
            transition: transform 0.2s;
        }

        .bottom-nav-item.active {
            color: var(--navy);
            background: var(--accent-soft);
        }

        .bottom-nav-item.active i {
            color: var(--navy);
            transform: scale(1.1);
        }

        /* App Container Wrapper: Full width on mobile, centered frame on desktop */
        .app-container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 16px;
        }

        @media (min-width: 768px) {
            body {
                background: #e2e8f0;
            }
            .app-container {
                background: var(--bg-app);
                min-height: calc(100vh - 72px);
                box-shadow: 0 0 30px rgba(0, 0, 0, 0.08);
                padding: 24px;
            }
            .bottom-nav {
                max-width: 600px;
                left: 50%;
                transform: translateX(-50%);
                border-radius: 20px 20px 0 0;
            }
        }

        /* Reusable Card Styles */
        .card-app {
            background: #ffffff;
            border: 0;
            border-radius: var(--card-radius);
            padding: 18px;
            box-shadow: 0 2px 14px rgba(15, 23, 42, 0.04);
            margin-bottom: 16px;
        }

        /* Hero Banner */
        .driver-hero-card {
            background: linear-gradient(135deg, var(--navy) 0%, #153a5c 100%);
            color: #fff;
            border-radius: var(--card-radius);
            padding: 22px;
            position: relative;
            overflow: hidden;
            margin-bottom: 18px;
            box-shadow: 0 8px 24px rgba(11, 27, 43, 0.15);
        }

        .driver-hero-card::after {
            content: '';
            position: absolute;
            right: -30px;
            bottom: -30px;
            width: 160px;
            height: 160px;
            background: radial-gradient(circle, var(--accent-glow) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .podium-box {
            display: flex;
            align-items: flex-end;
            justify-content: center;
            gap: 10px;
            padding-top: 10px;
        }
        .podium-box .p-col {
            flex: 1;
            text-align: center;
            border-radius: 14px 14px 0 0;
            padding: 14px 6px 10px;
            color: #fff;
        }
        .podium-box .p1 { background: linear-gradient(180deg, #f59e0b, #d97706); height: 165px; }
        .podium-box .p2 { background: linear-gradient(180deg, #94a3b8, #64748b); height: 135px; }
        .podium-box .p3 { background: linear-gradient(180deg, #fb923c, #c2410c); height: 115px; }
        .podium-box .medal { font-size: 1.8rem; line-height: 1; }
        .podium-box .name { font-weight: 700; font-size: 0.82rem; margin-top: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .podium-box .score { opacity: 0.9; font-size: 0.75rem; }

        /* Pure CSS Exclamation Popover */
        .info-popover-wrapper {
            position: relative;
            display: inline-flex;
            align-items: center;
            vertical-align: middle;
        }
        .info-popover-trigger {
            width: 20px;
            height: 20px;
            min-width: 20px;
            border-radius: 50%;
            background: #00ADEF;
            color: #ffffff;
            font-weight: 800;
            font-size: 0.75rem;
            line-height: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border: 0;
            padding: 0;
            transition: transform .15s ease, background .15s ease, box-shadow .15s ease;
            box-shadow: 0 1px 3px rgba(0, 173, 239, 0.35);
            user-select: none;
        }
        .info-popover-trigger.sm {
            width: 17px;
            height: 17px;
            min-width: 17px;
            font-size: 0.65rem;
            background: rgba(255, 255, 255, 0.4);
            color: #ffffff;
        }
        .info-popover-trigger:hover,
        .info-popover-trigger:focus {
            background: #0088cc;
            transform: scale(1.15);
            box-shadow: 0 2px 6px rgba(0, 173, 239, 0.45);
        }
        .info-popover-trigger.sm:hover,
        .info-popover-trigger.sm:focus {
            background: #00ADEF;
            color: #ffffff;
        }
        .info-popover-content {
            display: none;
            position: absolute;
            top: calc(100% + 6px);
            left: 50%;
            transform: translateX(-50%);
            width: 270px;
            max-width: 82vw;
            background: #ffffff;
            color: #1e293b;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 14px;
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.22);
            z-index: 1070;
            pointer-events: none;
            text-align: left;
            white-space: normal;
            font-weight: normal;
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
    </style>
    @stack('styles')
</head>
<body>

    <!-- Header Aplikasi Mobile -->
    <header class="app-header">
        <div class="d-flex align-items-center justify-content-between">
            <div class="brand-box">
                @if(file_exists(public_path('images/logo.png')))
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" style="max-height: 40px; max-width: 40px; object-fit: contain;">
                @elseif(file_exists(public_path('images/logo.jpg')))
                    <img src="{{ asset('images/logo.jpg') }}" alt="Logo" style="max-height: 40px; max-width: 40px; object-fit: contain;">
                @else
                    <div class="logo-icon">
                        <i class="bi bi-lightning-charge-fill"></i>
                    </div>
                @endif
                <div>
                    <div class="brand-title">Blitz Driver</div>
                    <div class="brand-sub">PT Blitz Electric Mobility</div>
                </div>
            </div>
            <div>
                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1 small">
                    <i class="bi bi-ev-front me-1"></i>Armada Listrik
                </span>
            </div>
        </div>
    </header>

    <!-- Konten Utama (Mobile Container) -->
    <main class="app-container">
        @if(session('ok'))
            <div class="alert alert-success alert-dismissible fade show rounded-4 py-2 px-3 small shadow-sm mb-3">
                <i class="bi bi-check-circle-fill me-1"></i> {{ session('ok') }}
                <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('warn'))
            <div class="alert alert-warning alert-dismissible fade show rounded-4 py-2 px-3 small shadow-sm mb-3">
                <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ session('warn') }}
                <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Bottom Navigation Bar Khusus Tampilan Mobile -->
    <nav class="bottom-nav">
        <a href="{{ route('driver.dashboard') }}" class="bottom-nav-item {{ request()->routeIs('driver.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i>
            <span>Performa</span>
        </a>
        <a href="{{ route('driver.leaderboard') }}" class="bottom-nav-item {{ request()->routeIs('driver.leaderboard') ? 'active' : '' }}">
            <i class="bi bi-trophy"></i>
            <span>Leaderboard</span>
        </a>
        <a href="{{ route('driver.profile') }}" class="bottom-nav-item {{ request()->routeIs('driver.profile*') ? 'active' : '' }}">
            <i class="bi bi-person-gear"></i>
            <span>Pengaturan</span>
        </a>
        <button type="button" class="bottom-nav-item border-0 bg-transparent" data-bs-toggle="modal" data-bs-target="#logoutModal">
            <i class="bi bi-box-arrow-right text-danger"></i>
            <span class="text-danger">Keluar</span>
        </button>
    </nav>

    <!-- Modal Konfirmasi Logout -->
    <div class="modal fade" id="logoutModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content rounded-4 border-0 p-2">
                <div class="modal-body text-center pt-4">
                    <div class="text-danger mb-3">
                        <i class="bi bi-exclamation-circle" style="font-size: 3rem;"></i>
                    </div>
                    <h6 class="fw-bold mb-1">Keluar dari Akun?</h6>
                    <p class="text-muted small mb-4">Apakah Anda yakin ingin keluar dari Portal Driver Blitz?</p>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-light rounded-pill w-100" data-bs-dismiss="modal">Batal</button>
                        <form method="POST" action="{{ route('logout') }}" class="w-100">
                            @csrf
                            <button type="submit" class="btn btn-danger rounded-pill w-100">Ya, Keluar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
