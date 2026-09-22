<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#15803D">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name', 'ECOCASH') }}</title>
    <link rel="manifest" href="/manifest.json">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @livewireStyles
    <style>
        @font-face {
            font-family: 'Dear Grandma';
            src: url('/font/Dear%20Grandma.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
            font-display: swap;
        }

        :root {
            --eco:        #15803d;
            --eco-hover:  #14532d;
            --eco-light:  #22c55e;
            --eco-pale:   #dcfce7;
            --ink:        #17211b;
            --ink-muted:  #64748b;
            --paper:      #f7faf8;
            --white:      #ffffff;
            --border:     #e2e8f0;
            --border-eco: #dce8df;
            --amber:      #f59e0b;
            --danger:     #dc2626;
            --sidebar-w:  256px;
            --font-grandma: 'Dear Grandma', cursive, sans-serif;
            --font-rubik: 'Rubik', system-ui, -apple-system, sans-serif;
            --shadow-subtle: 0 4px 16px rgba(21, 128, 61, 0.05);
            --shadow-hover: 0 8px 24px rgba(21, 128, 61, 0.12);
        }
        *, *::before, *::after { box-sizing: border-box; }
        body {
            background: var(--paper);
            color: var(--ink);
            font-family: var(--font-rubik);
            font-size: 0.9375rem;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        /* ── Desktop Sidebar ─────────────────────── */
        .eco-sidebar {
            width: var(--sidebar-w);
            position: fixed; top: 0; left: 0; bottom: 0;
            background: var(--white);
            border-right: 1px solid var(--border);
            z-index: 200;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }
        .eco-sidebar-brand {
            padding: 1.25rem 1.5rem;
            font-weight: 800;
            font-size: 1.2rem;
            color: var(--eco);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            letter-spacing: -0.02em;
        }
        .eco-sidebar-brand:hover { color: var(--eco-hover); }
        .eco-nav-section {
            padding: 0.75rem 1.25rem 0.25rem;
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--ink-muted);
            margin-top: 0.25rem;
        }
        .eco-nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.65rem 1rem;
            margin: 3px 0.75rem;
            border-radius: 10px;
            color: var(--ink);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.18s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .eco-nav-link i { font-size: 1.15rem; width: 1.35rem; text-align: center; flex-shrink: 0; color: var(--ink-muted); transition: transform 0.18s ease; }
        .eco-nav-link:hover { background: #f0fdf4; color: var(--eco); transform: translateX(3px); }
        .eco-nav-link:hover i { color: var(--eco); transform: scale(1.1); }
        .eco-nav-link.active { background: var(--eco-pale); color: var(--eco); font-weight: 600; box-shadow: 0 2px 8px rgba(21, 128, 61, 0.08); }
        .eco-nav-link.active i { color: var(--eco); }
        .eco-sidebar-footer {
            margin-top: auto;
            padding: 1rem;
            border-top: 1px solid var(--border);
            background: #fafcfb;
        }

        /* ── Main Layout ─────────────────────────── */
        .eco-main {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .eco-topbar-desktop {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border);
            padding: 0 1.75rem;
            height: 62px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
            transition: box-shadow 0.2s ease;
        }
        .eco-content { padding: 1.75rem; flex: 1; }

        /* ── Mobile Topbar & Bottom Nav ──────────── */
        .eco-topbar-mobile {
            display: none;
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            padding: 0 1.25rem;
            height: 62px;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 200;
        }
        .eco-topbar-mobile .brand {
            font-weight: 800;
            font-size: 1.15rem;
            color: var(--eco);
            letter-spacing: -0.02em;
            text-decoration: none;
            flex-shrink: 0;
        }
        .eco-topbar-mobile .dropdown-toggle::after {
            display: none !important;
        }
        .mobile-wallet-pill {
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-radius: 9999px;
            padding: 5px 12px;
            text-decoration: none;
            transition: all 0.2s ease;
            white-space: nowrap;
            line-height: 1;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .mobile-wallet-pill:hover, .mobile-wallet-pill:active {
            background: #ecfdf5;
            border-color: #86efac;
            transform: translateY(-1px);
        }
        .mobile-icon-btn {
            width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            color: #334155;
            text-decoration: none;
            transition: background 0.15s ease, color 0.15s ease;
        }
        .mobile-icon-btn:hover, .mobile-icon-btn:active {
            background: #f1f5f9;
            color: var(--eco);
        }
        .mobile-avatar-btn {
            padding: 0;
            border: 0;
            background: transparent;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.15s ease;
        }
        .mobile-avatar-btn:hover {
            transform: scale(1.05);
        }
        .mobile-avatar-btn:active {
            transform: scale(0.95);
        }
        .mobile-avatar-circle {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, #15803d 0%, #166534 100%);
            color: #ffffff;
            font-weight: 700;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.12);
            border: 2px solid #ffffff;
        }
        .eco-bottom-nav {
            display: none;
            position: fixed;
            bottom: 0; left: 0; right: 0;
            background: #ffffff;
            border-top: 1px solid var(--border);
            z-index: 300;
            height: 64px;
            padding: 0 0.5rem;
            padding-bottom: env(safe-area-inset-bottom);
            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.04);
        }
        .eco-bottom-nav a {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: var(--ink-muted);
            font-size: 0.72rem;
            font-weight: 500;
            padding: 6px 0;
            transition: color 0.15s ease;
        }
        .eco-bottom-nav a i {
            font-size: 1.25rem;
            line-height: 1;
            margin-bottom: 3px;
            transition: transform 0.15s ease;
        }
        .eco-bottom-nav a:hover i { transform: translateY(-1px); }
        .eco-bottom-nav a.active {
            color: var(--eco);
            font-weight: 600;
        }

        /* ── Modern Premium Floating Action Button (Scan) ── */
        .eco-bottom-nav a.scan-tab {
            position: relative;
            top: -12px;
            padding: 0;
            overflow: visible;
        }
        .eco-bottom-nav a.scan-tab .scan-icon-wrap {
            width: 48px;
            height: 48px;
            min-width: 48px;
            min-height: 48px;
            aspect-ratio: 1 / 1;
            background: linear-gradient(135deg, #10b981 0%, #059669 50%, #047857 100%);
            color: #ffffff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 2px;
            border: 3.5px solid #ffffff;
            box-shadow: 0 6px 16px rgba(5, 150, 105, 0.35), 0 2px 6px rgba(0, 0, 0, 0.06);
            transition: all 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .eco-bottom-nav a.scan-tab .scan-icon-wrap i {
            font-size: 1.35rem;
            margin-bottom: 0;
            color: #ffffff;
            filter: drop-shadow(0 1px 2px rgba(0, 0, 0, 0.2));
            transition: transform 0.2s ease;
        }
        .eco-bottom-nav a.scan-tab:hover .scan-icon-wrap {
            transform: translateY(-2px) scale(1.06);
            box-shadow: 0 8px 20px rgba(5, 150, 105, 0.45);
        }
        .eco-bottom-nav a.scan-tab:active .scan-icon-wrap {
            transform: translateY(1px) scale(0.95);
        }
        .eco-bottom-nav a.scan-tab.active .scan-icon-wrap {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.25), 0 6px 18px rgba(5, 150, 105, 0.4);
        }
        .eco-bottom-nav a.scan-tab span {
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.01em;
            color: var(--eco);
            line-height: 1;
        }

        @media (max-width: 767.98px) {
            .eco-sidebar        { display: none; }
            .eco-topbar-desktop { display: none; }
            .eco-main           { margin-left: 0; }
            .eco-content        { padding: 1rem 1rem 92px; }
            .eco-bottom-nav     { display: flex; }
            .eco-topbar-mobile  { display: flex; }
        }

        /* ── Design Tokens & Elements ─────────────── */
        .btn-eco {
            background: linear-gradient(135deg, var(--eco) 0%, #166534 100%);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-family: inherit;
            padding: 0.55rem 1.25rem;
            box-shadow: 0 3px 10px rgba(21, 128, 61, 0.2);
            transition: all 0.18s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .btn-eco:hover {
            background: linear-gradient(135deg, var(--eco-hover) 0%, #14532d 100%);
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 5px 16px rgba(21, 128, 61, 0.3);
        }
        .btn-eco:active { transform: translateY(0); }
        .btn-eco:focus-visible { outline: 3px solid #22c55e; outline-offset: 2px; }
        .btn-eco:disabled { opacity: 0.65; cursor: not-allowed; }

        .btn-outline-eco {
            background: transparent;
            color: var(--eco);
            border: 1.5px solid var(--eco);
            border-radius: 10px;
            font-weight: 600;
            font-family: inherit;
            padding: 0.55rem 1.25rem;
            transition: all 0.18s ease;
        }
        .btn-outline-eco:hover {
            background: var(--eco-pale);
            color: var(--eco-dark);
            transform: translateY(-1px);
        }

        .btn-amber {
            background: linear-gradient(135deg, var(--amber) 0%, #d97706 100%);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            box-shadow: 0 3px 10px rgba(245, 158, 11, 0.25);
            transition: all 0.18s ease;
        }
        .btn-amber:hover {
            transform: translateY(-1px);
            box-shadow: 0 5px 16px rgba(245, 158, 11, 0.35);
            color: #fff;
        }

        .eco-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 14px;
            box-shadow: var(--shadow-subtle);
            transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.2s ease;
        }
        .eco-card:hover {
            box-shadow: var(--shadow-hover);
        }

        .bg-eco { background-color: var(--eco) !important; }
        .text-eco { color: var(--eco) !important; }

        .badge-pending  { background:#fef3c7; color:#92400e; font-weight:600; padding:4px 10px; border-radius:8px; font-size:0.78rem; display:inline-block; border:1px solid rgba(245,158,11,0.25); }
        .badge-verified { background:#dcfce7; color:#15803d; font-weight:600; padding:4px 10px; border-radius:8px; font-size:0.78rem; display:inline-block; border:1px solid rgba(21,128,61,0.25); }
        .badge-rejected { background:#fee2e2; color:#991b1b; font-weight:600; padding:4px 10px; border-radius:8px; font-size:0.78rem; display:inline-block; border:1px solid rgba(220,38,38,0.25); }
        .badge-draft    { background:#f1f5f9; color:#475569; font-weight:600; padding:4px 10px; border-radius:8px; font-size:0.78rem; display:inline-block; border:1px solid rgba(100,116,139,0.25); }

        .form-control, .form-select {
            border-radius: 10px;
            border: 1px solid var(--border);
            font-family: inherit;
            font-size: 0.9rem;
            padding: 0.65rem 0.95rem;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--eco);
            box-shadow: 0 0 0 3px rgba(21,128,61,0.18);
        }
        .form-label { font-size: 0.875rem; font-weight: 600; color: var(--ink); margin-bottom: 0.35rem; }

        a:focus-visible, button:focus-visible, [tabindex]:focus-visible {
            outline: 3px solid var(--eco);
            outline-offset: 2px;
        }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body>

    {{-- Mobile Top Bar --}}
    <header class="eco-topbar-mobile">
        <a href="{{ route('dashboard') }}" class="brand">
            <i class="bi bi-recycle me-1"></i>ECOCASH
        </a>
        <div class="d-flex align-items-center gap-2 gap-sm-3">
            {{-- Info Saldo Dompet Mobile --}}
            @auth
                <a href="{{ route('wallet') }}" class="mobile-wallet-pill shadow-2xs" title="Buka Dompet">
                    <i class="bi bi-wallet2 text-success" style="font-size: 0.9rem;"></i>
                    <span class="fw-bold text-dark" style="font-size: 0.8rem; letter-spacing: -0.01em;">
                        Rp{{ number_format(auth()->user()->available_balance ?? 0, 0, ',', '.') }}
                    </span>
                </a>
            @endauth

            {{-- Lonceng Notifikasi --}}
            <a href="{{ route('notifications') }}" class="mobile-icon-btn position-relative" aria-label="Lihat notifikasi">
                <i class="bi bi-bell fs-5"></i>
                @php
                    $unreadCount = auth()->check() ? auth()->user()->inAppNotifications()->where('is_read', false)->count() : 0;
                @endphp
                @if($unreadCount > 0)
                    <span class="position-absolute top-1 start-75 translate-middle p-1 bg-danger border border-light rounded-circle">
                        <span class="visually-hidden">Notifikasi baru</span>
                    </span>
                @endif
            </a>

            {{-- Avatar Profile Bersih & Interaktif --}}
            <div class="dropdown d-flex align-items-center">
                <button class="mobile-avatar-btn shadow-none" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Menu pengguna" style="outline: none;">
                    <div class="mobile-avatar-circle">
                        {{ strtoupper(substr(auth()->user()?->name ?? 'U', 0, 1)) }}
                    </div>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow border mt-2.5 py-2" style="min-width: 220px; border-radius: 14px; font-size: 0.88rem; transform: translateY(4px);">
                    <li class="px-3 py-2 border-bottom mb-1 bg-light rounded-top">
                        <div class="fw-bold text-truncate" style="color: var(--ink);">{{ auth()->user()?->name }}</div>
                        <div class="small text-muted text-truncate" style="font-size: 0.75rem;">{{ auth()->user()?->email }}</div>
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center gap-2.5 py-2" href="{{ route('profile.edit') }}">
                            <i class="bi bi-person-circle text-muted"></i> Profil Saya
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center gap-2.5 py-2" href="{{ route('wallet') }}">
                            <i class="bi bi-wallet2 text-muted"></i> Dompet & Saldo
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center gap-2.5 py-2" href="{{ route('reward') }}">
                            <i class="bi bi-award text-muted"></i> ECOPOINT & Level
                        </a>
                    </li>
                    <li><hr class="dropdown-divider my-1"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}" class="m-0">
                            @csrf
                            <button type="submit" class="dropdown-item d-flex align-items-center gap-2.5 text-danger py-2 fw-semibold">
                                <i class="bi bi-box-arrow-right"></i> Keluar (Logout)
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    {{-- Desktop Sidebar --}}
    <aside class="eco-sidebar" aria-label="Navigasi utama">
        <a href="{{ route('dashboard') }}" class="eco-sidebar-brand">
            <i class="bi bi-recycle fs-4"></i>
            <span>ECOCASH</span>
        </a>

        <nav class="py-3 flex-grow-1">
            <div class="eco-nav-section">Utama</div>
            <a href="{{ route('dashboard') }}" class="eco-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2"></i> <span>Dashboard</span>
            </a>
            <a href="{{ route('scanner') }}" class="eco-nav-link {{ request()->routeIs('scanner') ? 'active' : '' }}">
                <i class="bi bi-camera"></i> <span>Scan Sampah</span>
            </a>
            <a href="{{ route('setoran') }}" class="eco-nav-link {{ request()->routeIs('setoran*') ? 'active' : '' }}">
                <i class="bi bi-clock-history"></i> <span>Riwayat Setoran</span>
            </a>

            <div class="eco-nav-section">Keuangan & Reward</div>
            <a href="{{ route('wallet') }}" class="eco-nav-link {{ request()->routeIs('wallet*') ? 'active' : '' }}">
                <i class="bi bi-wallet2"></i> <span>Dompet & Saldo</span>
            </a>
            <a href="{{ route('reward') }}" class="eco-nav-link {{ request()->routeIs('reward*') ? 'active' : '' }}">
                <i class="bi bi-award"></i> <span>ECOPOINT & Level</span>
            </a>
            <a href="{{ route('missions') }}" class="eco-nav-link {{ request()->routeIs('missions*') ? 'active' : '' }}">
                <i class="bi bi-trophy"></i> <span>Misi Lingkungan</span>
            </a>

            <div class="eco-nav-section">Wawasan</div>
            <a href="{{ route('education') }}" class="eco-nav-link {{ request()->routeIs('education*') ? 'active' : '' }}">
                <i class="bi bi-book"></i> <span>Edukasi Pilah</span>
            </a>
            <a href="{{ route('profile.edit') }}" class="eco-nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <i class="bi bi-person-circle"></i> <span>Profil & Akun</span>
            </a>

            @auth
                @if(auth()->user()->isRole('bank_partner'))
                    <div class="eco-nav-section">Panel Mitra</div>
                    <a href="{{ route('partner.dashboard') }}" class="eco-nav-link {{ request()->routeIs('partner.*') ? 'active' : '' }}">
                        <i class="bi bi-patch-check"></i> <span>Verifikasi Setoran</span>
                    </a>
                @endif

                @if(auth()->user()->isRole('admin'))
                    <div class="eco-nav-section">Panel Admin</div>
                    <a href="{{ route('admin.dashboard') }}" class="eco-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i> <span>Ikhtisar Sistem</span>
                    </a>
                    <a href="{{ route('admin.withdrawals') }}" class="eco-nav-link {{ request()->routeIs('admin.withdrawals*') ? 'active' : '' }}">
                        <i class="bi bi-cash-stack"></i> <span>Kelola Penarikan</span>
                    </a>
                @endif
            @endauth
        </nav>

        <div class="eco-sidebar-footer">
            <div class="d-flex align-items-center gap-2 mb-2">
                <div class="rounded-circle bg-eco text-white d-flex align-items-center justify-content-center fw-bold" style="width: 36px; height: 36px; font-size: 0.9rem;">
                    {{ strtoupper(substr(auth()->user()?->name ?? 'U', 0, 1)) }}
                </div>
                <div class="overflow-hidden">
                    <div class="fw-bold text-truncate" style="font-size:0.875rem; color:var(--ink);">{{ auth()->user()?->name }}</div>
                    <div class="small text-muted text-truncate" style="font-size:0.75rem;">{{ auth()->user()?->email }}</div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-sm w-100 mt-1 d-flex align-items-center justify-content-center gap-2"
                        style="border:1px solid var(--border); background:var(--white); color:var(--ink); border-radius:6px; font-size:0.8rem; padding: 0.4rem;">
                    <i class="bi bi-box-arrow-right"></i>Keluar
                </button>
            </form>
        </div>
    </aside>

    {{-- Page Wrapper --}}
    <div class="eco-main">
        {{-- Desktop Top Bar --}}
        <div class="eco-topbar-desktop">
            <div class="d-flex align-items-center gap-2">
                <span class="fw-bold" style="font-size: 1rem; color: var(--ink);">
                    @if(request()->routeIs('dashboard'))        Dashboard
                    @elseif(request()->routeIs('scanner'))      Scan Sampah
                    @elseif(request()->routeIs('setoran*'))     Riwayat Setoran
                    @elseif(request()->routeIs('wallet*'))      Dompet & Penarikan
                    @elseif(request()->routeIs('reward*'))      ECOPOINT & Level
                    @elseif(request()->routeIs('missions*'))    Misi Lingkungan
                    @elseif(request()->routeIs('education*'))   Edukasi Lingkungan
                    @elseif(request()->routeIs('profile.*'))    Profil Saya
                    @elseif(request()->routeIs('notifications')) Notifikasi
                    @elseif(request()->routeIs('partner.*'))    Verifikasi Setoran
                    @elseif(request()->routeIs('admin.*'))      Panel Admin
                    @else {{ config('app.name') }}
                    @endif
                </span>
            </div>

            <div class="d-flex align-items-center gap-3">
                {{-- Saldo Ringkas --}}
                <a href="{{ route('wallet') }}" class="text-decoration-none text-dark d-none d-lg-flex align-items-center gap-2 px-3 py-1 bg-light rounded-pill border">
                    <i class="bi bi-wallet2 text-success"></i>
                    <span class="small fw-semibold">Rp{{ number_format(auth()->user()?->available_balance ?? 0, 0, ',', '.') }}</span>
                </a>

                {{-- Lonceng Notifikasi --}}
                <a href="{{ route('notifications') }}" class="position-relative text-dark text-decoration-none p-2 rounded-circle hover-bg-light" aria-label="Lihat notifikasi">
                    <i class="bi bi-bell fs-5"></i>
                    @if($unreadCount > 0)
                        <span class="position-absolute top-1 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem;">
                            {{ $unreadCount }}
                        </span>
                    @endif
                </a>

                <div class="border-start ps-3 py-1">
                    <span class="small fw-semibold text-muted">{{ auth()->user()?->name }}</span>
                </div>
            </div>
        </div>

        {{-- Main Page Content Slot --}}
        <div class="eco-content">
            {{ $slot }}
        </div>
    </div>

    {{-- Mobile Bottom Navigation --}}
    <nav class="eco-bottom-nav" aria-label="Navigasi bawah">
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2{{ request()->routeIs('dashboard') ? '-fill' : '' }}"></i>
            <span>Beranda</span>
        </a>
        <a href="{{ route('setoran') }}" class="{{ request()->routeIs('setoran*') ? 'active' : '' }}">
            <i class="bi bi-clock-history"></i>
            <span>Setoran</span>
        </a>
        <a href="{{ route('scanner') }}" class="scan-tab {{ request()->routeIs('scanner') ? 'active' : '' }}" aria-label="Scan sampah">
            <div class="scan-icon-wrap">
                <i class="bi bi-camera-fill"></i>
            </div>
            <span>Scan</span>
        </a>
        <a href="{{ route('reward') }}" class="{{ request()->routeIs('reward*') ? 'active' : '' }}">
            <i class="bi bi-award{{ request()->routeIs('reward*') ? '-fill' : '' }}"></i>
            <span>Reward</span>
        </a>
        <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">
            <i class="bi bi-person{{ request()->routeIs('profile.*') ? '-fill' : '' }}"></i>
            <span>Profil</span>
        </a>
    </nav>

    @livewireScripts

    <script>
        // PWA Service Worker Registration
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js').catch(() => {});
        }
    </script>
</body>
</html>
