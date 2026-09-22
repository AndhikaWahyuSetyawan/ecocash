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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @livewireStyles
    <style>
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
            --sidebar-w:  252px;
        }
        *, *::before, *::after { box-sizing: border-box; }
        body {
            background: var(--paper);
            color: var(--ink);
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            font-size: 0.9375rem;
            line-height: 1.6;
        }
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
            font-weight: 700;
            font-size: 1.125rem;
            color: var(--eco);
            border-bottom: 1px solid var(--border);
            display: block;
            text-decoration: none;
            letter-spacing: -0.02em;
        }
        .eco-sidebar-brand:hover { color: var(--eco-hover); }
        .eco-nav-section {
            padding: 0.625rem 1rem 0.25rem;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            color: var(--ink-muted);
            margin-top: 0.5rem;
        }
        .eco-nav-link {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            padding: 0.5625rem 1rem;
            margin: 1px 0.625rem;
            border-radius: 6px;
            color: var(--ink);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: background 0.12s, color 0.12s;
        }
        .eco-nav-link i { font-size: 1rem; width: 1.25rem; text-align: center; flex-shrink: 0; }
        .eco-nav-link:hover { background: #f0f7f2; color: var(--eco); }
        .eco-nav-link.active { background: var(--eco-pale); color: var(--eco); font-weight: 600; }
        .eco-sidebar-footer {
            margin-top: auto;
            padding: 0.875rem 1rem;
            border-top: 1px solid var(--border);
            font-size: 0.8125rem;
        }
        .eco-main { margin-left: var(--sidebar-w); min-height: 100vh; display: flex; flex-direction: column; }
        .eco-topbar-desktop {
            background: var(--white);
            border-bottom: 1px solid var(--border);
            padding: 0 1.75rem;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .eco-content { padding: 1.75rem; flex: 1; }
        .eco-topbar-mobile {
            display: none;
            background: var(--white);
            border-bottom: 1px solid var(--border);
            padding: 0 1rem;
            height: 52px;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 200;
        }
        .eco-topbar-mobile .brand {
            font-weight: 700; font-size: 1.0625rem;
            color: var(--eco); letter-spacing: -0.02em; text-decoration: none;
        }
        .eco-bottom-nav {
            display: none;
            position: fixed; bottom: 0; left: 0; right: 0;
            background: var(--white);
            border-top: 1px solid var(--border);
            z-index: 300;
            height: 62px;
        }
        .eco-bottom-nav a {
            flex: 1; display: flex; flex-direction: column;
            align-items: center; justify-content: center; gap: 2px;
            padding: 6px 4px; text-decoration: none;
            color: var(--ink-muted); font-size: 0.65rem; font-weight: 500;
        }
        .eco-bottom-nav a i { font-size: 1.3rem; line-height: 1; }
        .eco-bottom-nav a.active { color: var(--eco); }
        .eco-bottom-nav a.scan-tab { position: relative; top: -10px; }
        .eco-bottom-nav a.scan-tab .scan-icon-wrap {
            background: var(--eco); color: #fff;
            width: 48px; height: 48px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 2px 8px rgba(21,128,61,.35);
        }
        .eco-bottom-nav a.scan-tab i { font-size: 1.4rem; }
        .eco-bottom-nav a.scan-tab span { margin-top: 4px; }
        @media (max-width: 767.98px) {
            .eco-sidebar        { display: none; }
            .eco-topbar-desktop { display: none; }
            .eco-main           { margin-left: 0; }
            .eco-content        { padding: 1rem 1rem 80px; }
            .eco-bottom-nav     { display: flex; }
            .eco-topbar-mobile  { display: flex; }
        }
        .btn-eco { background: var(--eco); color: #fff; border: none; border-radius: 6px; font-weight: 600; font-family: inherit; }
        .btn-eco:hover, .btn-eco:focus { background: var(--eco-hover); color: #fff; }
        .eco-card { background: var(--white); border: 1px solid var(--border); border-radius: 10px; }
        .badge-pending  { background:#fef3c7; color:#92400e; font-weight:600; padding:3px 9px; border-radius:4px; font-size:.78rem; display:inline-block; }
        .badge-verified { background:#dcfce7; color:#15803d; font-weight:600; padding:3px 9px; border-radius:4px; font-size:.78rem; display:inline-block; }
        .badge-rejected { background:#fee2e2; color:#991b1b; font-weight:600; padding:3px 9px; border-radius:4px; font-size:.78rem; display:inline-block; }
        .badge-draft    { background:#f1f5f9; color:#475569; font-weight:600; padding:3px 9px; border-radius:4px; font-size:.78rem; display:inline-block; }
        .form-control, .form-select { border-radius: 6px; border: 1px solid var(--border); font-family: inherit; font-size: .9rem; }
        .form-control:focus, .form-select:focus { border-color: var(--eco); box-shadow: 0 0 0 3px rgba(21,128,61,.15); }
        .form-label { font-size: .875rem; font-weight: 500; color: var(--ink); margin-bottom: .35rem; }
        a:focus-visible, button:focus-visible, [tabindex]:focus-visible { outline: 3px solid var(--eco); outline-offset: 2px; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body>

    <header class="eco-topbar-mobile">
        <a href="{{ route('dashboard') }}" class="brand">ECOCASH</a>
        <span style="color:var(--ink-muted); font-size:.8rem;">{{ auth()->user()?->name }}</span>
    </header>

    <aside class="eco-sidebar" aria-label="Navigasi utama">
        <a href="{{ route('dashboard') }}" class="eco-sidebar-brand">ECOCASH</a>
        <nav class="py-2 flex-grow-1">
            <a href="{{ route('dashboard') }}"
               class="eco-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-house-door"></i> Beranda
            </a>
            <a href="{{ route('scanner') }}"
               class="eco-nav-link {{ request()->routeIs('scanner') ? 'active' : '' }}">
                <i class="bi bi-camera"></i> Scan Sampah
            </a>
            <a href="{{ route('setoran') }}"
               class="eco-nav-link {{ request()->routeIs('setoran') ? 'active' : '' }}">
                <i class="bi bi-clock-history"></i> Riwayat Setoran
            </a>
            <a href="{{ route('education') }}"
               class="eco-nav-link {{ request()->routeIs('education') ? 'active' : '' }}">
                <i class="bi bi-book"></i> Pendidikan
            </a>
            <a href="{{ route('profile.edit') }}"
               class="eco-nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <i class="bi bi-person-circle"></i> Profil
            </a>
            @auth
                @if(auth()->user()->isRole('bank_partner'))
                    <div class="eco-nav-section">Mitra</div>
                    <a href="{{ route('partner.dashboard') }}"
                       class="eco-nav-link {{ request()->routeIs('partner.*') ? 'active' : '' }}">
                        <i class="bi bi-patch-check"></i> Verifikasi Setoran
                    </a>
                @endif
                @if(auth()->user()->isRole('admin'))
                    <div class="eco-nav-section">Admin</div>
                    <a href="{{ route('admin.dashboard') }}"
                       class="eco-nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i> Panel Admin
                    </a>
                @endif
            @endauth
        </nav>
        <div class="eco-sidebar-footer">
            <div style="font-weight:600; font-size:.85rem; color:var(--ink);">{{ auth()->user()?->name }}</div>
            <div style="font-size:.78rem; color:var(--ink-muted); margin-bottom:.5rem;">{{ auth()->user()?->email }}</div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-sm w-100"
                        style="border:1px solid var(--border); background:transparent; color:var(--ink); border-radius:5px; font-size:.8rem;">
                    <i class="bi bi-box-arrow-right me-1"></i>Keluar
                </button>
            </form>
        </div>
    </aside>

    <div class="eco-main">
        <div class="eco-topbar-desktop">
            <span style="font-weight:600; font-size:.9375rem;">
                @if(request()->routeIs('dashboard'))        Beranda
                @elseif(request()->routeIs('scanner'))      Scan Sampah
                @elseif(request()->routeIs('setoran'))      Riwayat Setoran
                @elseif(request()->routeIs('education'))    Pendidikan
                @elseif(request()->routeIs('profile.*'))    Profil
                @elseif(request()->routeIs('partner.*'))    Verifikasi Setoran
                @elseif(request()->routeIs('admin.*'))      Panel Admin
                @else {{ config('app.name') }}
                @endif
            </span>
            <span style="font-size:.8rem; color:var(--ink-muted);">{{ auth()->user()?->name }}</span>
        </div>
        <div class="eco-content">
            {{ $slot }}
        </div>
    </div>

    <nav class="eco-bottom-nav" aria-label="Navigasi bawah">
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-house-door{{ request()->routeIs('dashboard') ? '-fill' : '' }}"></i>
            <span>Beranda</span>
        </a>
        <a href="{{ route('setoran') }}" class="{{ request()->routeIs('setoran') ? 'active' : '' }}">
            <i class="bi bi-clock-history"></i>
            <span>Setoran</span>
        </a>
        <a href="{{ route('scanner') }}" class="scan-tab {{ request()->routeIs('scanner') ? 'active' : '' }}" aria-label="Scan sampah">
            <div class="scan-icon-wrap"><i class="bi bi-camera-fill"></i></div>
            <span>Scan</span>
        </a>
        <a href="{{ route('education') }}" class="{{ request()->routeIs('education') ? 'active' : '' }}">
            <i class="bi bi-book{{ request()->routeIs('education') ? '-fill' : '' }}"></i>
            <span>Belajar</span>
        </a>
        <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">
            <i class="bi bi-person-circle"></i>
            <span>Profil</span>
        </a>
    </nav>

    @livewireScripts
    <script>
        if ('serviceWorker' in navigator) navigator.serviceWorker.register('/sw.js').catch(() => {});
    </script>
</body>
</html>
