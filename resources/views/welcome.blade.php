<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#15803D">
    <meta name="description" content="ECOCASH membantu kamu kenali, pilah, dan setor sampah daur ulang, lalu terima nilai ekonominya.">
    <title>ECOCASH - Sampah ke Nilai</title>
    <link rel="manifest" href="/manifest.json">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Three.js for real-time WebGL ambient particles & 3D eco spheres -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <style>
        @font-face {
            font-family: 'Dear Grandma';
            src: url('/font/Dear%20Grandma.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
            font-display: swap;
        }

        :root {
            --eco: #15803d;
            --eco-dark: #0f5132;
            --eco-hover: #166534;
            --eco-light: #22c55e;
            --eco-pale: #ecfdf5;
            --eco-glow: rgba(34, 197, 94, 0.25);
            --ink: #17211b;
            --ink-muted: #4b6354;
            --paper: #f8faf9;
            --surface: #ffffff;
            --border: #dce8df;
            --border-subtle: #e8f0ea;
            --amber: #f59e0b;
            --amber-soft: #fef3c7;
            --amber-dark: #78350f;
            --shadow-sm: 0 4px 12px rgba(21, 128, 61, 0.06);
            --shadow-md: 0 12px 32px rgba(21, 128, 61, 0.1);
            --shadow-lg: 0 20px 48px rgba(21, 128, 61, 0.14);
            --font-grandma: 'Dear Grandma', cursive, sans-serif;
            --font-rubik: 'Rubik', system-ui, -apple-system, sans-serif;
        }

        *, *::before, *::after {
            box-sizing: border-box;
        }

        html, body {
            max-width: 100%;
            overflow-x: hidden !important;
            scroll-behavior: smooth;
        }

        body {
            background: var(--paper);
            color: var(--ink);
            font-family: var(--font-rubik);
            margin: 0;
            padding: 0;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            position: relative;
        }

        /* ── WebGL Canvas Layer ── */
        #webgl-canvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            pointer-events: none;
            opacity: 0.5;
        }

        /* ── Scrollytelling Scroll-Driven Reveal Animations ── */
        .reveal-on-scroll {
            opacity: 0;
            transform: translateY(24px);
            transition: opacity 0.7s cubic-bezier(0.16, 1, 0.3, 1), transform 0.7s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: opacity, transform;
        }
        .reveal-on-scroll.is-revealed {
            opacity: 1;
            transform: translateY(0);
        }
        .reveal-delay-1 { transition-delay: 0.12s; }
        .reveal-delay-2 { transition-delay: 0.24s; }
        .reveal-delay-3 { transition-delay: 0.36s; }

        /* Floating subtle motion */
        @keyframes floatSlow {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-6px); }
        }
        .anim-float {
            animation: floatSlow 5s ease-in-out infinite;
        }

        /* ── Navbar: Clean, Natural, Non-forced Logo ── */
        .lp-navbar {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-subtle);
            padding: 0.85rem 0;
            position: sticky;
            top: 0;
            z-index: 100;
            transition: all 0.25s ease;
        }
        .lp-navbar.scrolled {
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.04);
            background: rgba(255, 255, 255, 0.98);
        }
        .lp-navbar .brand-group {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            user-select: none;
        }
        .lp-navbar .brand-icon {
            width: 34px;
            height: 34px;
            background: var(--eco-pale);
            border: 1px solid rgba(21, 128, 61, 0.2);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--eco);
            font-size: 1.15rem;
            transition: transform 0.2s ease;
        }
        .lp-navbar .brand-group:hover .brand-icon {
            transform: rotate(20deg);
        }
        .lp-navbar .wordmark {
            font-family: var(--font-rubik);
            font-weight: 800;
            font-size: 1.2rem;
            color: var(--eco);
            letter-spacing: -0.02em;
            margin: 0;
            line-height: 1;
        }
        .lp-navbar .brand-tagline {
            font-family: var(--font-grandma);
            font-size: 1rem;
            color: var(--ink-muted);
            margin-left: 0.35rem;
            line-height: 1;
            align-self: center;
        }

        /* ── Buttons ── */
        .btn-eco {
            background: var(--eco);
            color: #fff;
            border: 1px solid transparent;
            border-radius: 10px;
            padding: 0.55rem 1.25rem;
            font-family: var(--font-rubik);
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            box-shadow: 0 2px 8px rgba(21, 128, 61, 0.18);
            transition: all 0.18s ease;
        }
        .btn-eco:hover {
            background: var(--eco-hover);
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(21, 128, 61, 0.25);
        }
        .btn-eco::after {
            content: '';
            position: absolute;
            top: 0; left: -100%;
            width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.25), transparent);
            transition: left 0.6s ease;
        }
        .btn-eco:hover::after {
            left: 100%;
        }
        .btn-eco:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(21, 128, 61, 0.35);
            color: #fff;
        }
        .btn-eco:active {
            transform: translateY(0);
        }

        .btn-outline-eco {
            background: rgba(255, 255, 255, 0.9);
            color: var(--eco);
            border: 1.5px solid var(--border);
            border-radius: 12px;
            padding: 0.65rem 1.45rem;
            font-family: var(--font-rubik);
            font-weight: 600;
            font-size: 0.95rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .btn-outline-eco:hover {
            background: var(--eco-pale);
            border-color: var(--eco);
            color: var(--eco-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 14px rgba(21, 128, 61, 0.12);
        }

        /* ── Hero ── */
        .lp-hero {
            padding: 5rem 0 4rem;
            position: relative;
            z-index: 1;
        }
        .hero-grandma-tag {
            font-family: var(--font-grandma);
            font-size: 1.45rem;
            color: var(--eco);
            display: inline-block;
            margin-bottom: 0.5rem;
            letter-spacing: 0.03em;
            background: rgba(236, 253, 245, 0.85);
            padding: 0.25rem 0.85rem;
            border-radius: 30px;
            border: 1px solid rgba(21, 128, 61, 0.15);
        }
        .lp-hero h1 {
            font-family: var(--font-rubik);
            font-size: clamp(2.4rem, 5.5vw, 3.6rem);
            font-weight: 800;
            line-height: 1.15;
            letter-spacing: -0.03em;
            color: var(--ink);
            margin-bottom: 1.2rem;
        }
        .lp-hero h1 .text-highlight {
            background: linear-gradient(135deg, var(--eco) 0%, #22c55e 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: inline-block;
        }
        .lp-hero .lead {
            font-size: 1.15rem;
            color: var(--ink-muted);
            max-width: 520px;
            line-height: 1.65;
            font-weight: 400;
        }

        /* 3D Tilt Card Component */
        .tilt-card {
            transform-style: preserve-3d;
            perspective: 1000px;
            transition: transform 0.25s ease-out, box-shadow 0.25s ease-out;
        }

        .hero-flow {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 1.75rem;
            box-shadow: var(--shadow-md);
            position: relative;
            z-index: 1;
        }
        .hero-flow-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 1rem;
            margin-bottom: 0.5rem;
            border-bottom: 1px solid var(--border-subtle);
        }
        .hero-flow-header-title {
            font-weight: 700;
            font-size: 0.95rem;
            color: var(--ink);
        }
        .hero-flow-header-badge {
            font-family: var(--font-grandma);
            font-size: 1.1rem;
            color: var(--eco);
            background: var(--eco-pale);
            padding: 0.2rem 0.65rem;
            border-radius: 20px;
            border: 1px solid rgba(21, 128, 61, 0.15);
        }
        .hero-flow-step {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            padding: 0.85rem 0;
            transition: transform 0.15s ease;
        }
        .hero-flow-step + .hero-flow-step {
            border-top: 1px solid var(--border-subtle);
        }
        .hero-flow-icon {
            width: 42px;
            height: 42px;
            background: var(--eco-pale);
            border: 1px solid rgba(21, 128, 61, 0.12);
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--eco);
            font-size: 1.15rem;
            flex-shrink: 0;
        }
        .hero-flow-text strong {
            display: block;
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--ink);
            margin-bottom: 0.15rem;
        }
        .hero-flow-text span {
            font-size: 0.85rem;
            color: var(--ink-muted);
            line-height: 1.45;
        }

        /* Sections */
        section { padding: 4rem 0; }
        section + section { border-top: 1px solid var(--border); }
        
        .section-label {
            font-family: var(--font-grandma);
            font-size: 1.3rem;
            font-weight: normal;
            color: var(--eco);
            margin-bottom: 0.35rem;
            display: inline-block;
            letter-spacing: 0.03em;
        }
        .section-title {
            font-family: var(--font-rubik);
            font-size: clamp(1.6rem, 3.5vw, 2.2rem);
            font-weight: 700;
            letter-spacing: -0.025em;
            color: var(--ink);
            margin-bottom: 1.15rem;
            line-height: 1.25;
        }

        /* Callout */
        .eco-callout {
            border-left: 4px solid var(--eco-light);
            padding: 1rem 1.35rem;
            background: var(--eco-pale);
            border-radius: 0 10px 10px 0;
            font-size: 0.95rem;
            color: #1e3a29;
            font-weight: 500;
            border-top: 1px solid rgba(21, 128, 61, 0.08);
            border-right: 1px solid rgba(21, 128, 61, 0.08);
            border-bottom: 1px solid rgba(21, 128, 61, 0.08);
        }

        /* Problem section */
        .problem-body {
            max-width: 640px;
            line-height: 1.75;
            color: var(--ink-muted);
            font-size: 1rem;
        }

        /* How it works */
        .step-row {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 1.75rem;
        }
        @media (max-width: 767.98px) {
            .step-row { grid-template-columns: 1fr; gap: 1.5rem; }
        }
        .step-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 1.75rem;
            box-shadow: var(--shadow-sm);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .step-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }
        .step-number {
            font-family: var(--font-grandma);
            font-size: 2.2rem;
            color: var(--eco);
            line-height: 1;
            margin-bottom: 0.65rem;
        }
        .step-name {
            font-family: var(--font-rubik);
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--ink);
            margin-bottom: 0.5rem;
        }
        .step-desc {
            font-size: 0.9rem;
            color: var(--ink-muted);
            line-height: 1.65;
            margin: 0;
        }

        /* Modern interactive feature card */
        .feature-box {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 1.75rem;
            box-shadow: var(--shadow-sm);
        }

        /* Formula block */
        .formula-block {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 1.25rem 1.75rem;
            font-family: var(--font-rubik);
            font-size: 1.05rem;
            color: var(--ink);
            display: inline-block;
            box-shadow: var(--shadow-sm);
        }
        .formula-block .formula-var { color: var(--eco); font-weight: 700; }
        .formula-block .formula-result { color: var(--amber); font-weight: 700; }

        /* Ecopoint section */
        .ecopoint-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--amber-soft);
            color: var(--amber-dark);
            border: 1px solid rgba(245, 158, 11, 0.3);
            border-radius: 20px;
            padding: 0.45rem 1rem;
            font-weight: 700;
            font-size: 0.9rem;
            margin-bottom: 1.25rem;
        }

        /* CTA section */
        .lp-cta {
            background: linear-gradient(135deg, #15803d 0%, #166534 100%);
            color: #fff;
            padding: 5rem 0;
            border-top: none !important;
            position: relative;
            overflow: hidden;
        }
        .lp-cta::before {
            content: "";
            position: absolute;
            top: -40%;
            left: 50%;
            transform: translateX(-50%);
            width: 600px;
            height: 300px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.12) 0%, rgba(255, 255, 255, 0) 70%);
            pointer-events: none;
        }
        .lp-cta .cta-grandma-sub {
            font-family: var(--font-grandma);
            font-size: 1.5rem;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 0.25rem;
            letter-spacing: 0.04em;
        }
        .lp-cta .section-title {
            color: #fff;
            font-size: clamp(2rem, 4.5vw, 2.75rem);
            margin-bottom: 0.85rem;
        }
        .btn-cta-white {
            background: #fff;
            color: var(--eco);
            border: 1px solid #fff;
            border-radius: 12px;
            padding: 0.75rem 2rem;
            font-family: var(--font-rubik);
            font-weight: 700;
            font-size: 1.05rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.15);
            transition: all 0.2s ease;
        }
        .btn-cta-white:hover {
            background: var(--eco-pale);
            color: var(--eco-dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
        }
        .btn-cta-white:active {
            transform: translateY(0);
        }

        /* Footer */
        .lp-footer {
            background: #fff;
            border-top: 1px solid var(--border);
            padding: 2.5rem 0;
            font-size: 0.9rem;
            color: var(--ink-muted);
        }
        .lp-footer a {
            color: var(--ink-muted);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.15s ease;
        }
        .lp-footer a:hover {
            color: var(--eco);
        }
        .footer-tagline {
            font-family: var(--font-grandma);
            font-size: 1.25rem;
            color: var(--eco);
            letter-spacing: 0.02em;
        }

        /* Responsive Media Queries to prevent shaking and overflow on mobile */
        @media (max-width: 767.98px) {
            .lp-navbar {
                position: -webkit-sticky;
                position: sticky;
                top: 0;
                z-index: 1000;
            }
            .lp-navbar .brand-tagline {
                display: none;
            }
            .lp-navbar .brand-icon {
                width: 30px;
                height: 30px;
                font-size: 1rem;
            }
            .lp-navbar .wordmark {
                font-size: 1.05rem;
            }
            .lp-navbar .btn-eco, .lp-navbar .btn-outline-eco {
                padding: 0.38rem 0.8rem;
                font-size: 0.8rem;
            }

            /* Center align Hero on Mobile */
            .lp-hero {
                padding: 2.25rem 0 1.75rem;
                text-align: center;
            }
            .lp-hero .hero-grandma-tag {
                margin-left: auto;
                margin-right: auto;
                font-size: 1.25rem;
            }
            .lp-hero h1 {
                font-size: 1.95rem;
                line-height: 1.25;
                margin-left: auto;
                margin-right: auto;
            }
            .lp-hero .lead {
                margin-left: auto;
                margin-right: auto;
                font-size: 0.98rem;
                line-height: 1.6;
            }
            .lp-hero .hero-actions {
                justify-content: center !important;
                flex-direction: column;
                width: 100%;
                gap: 0.65rem !important;
            }
            .lp-hero .hero-actions .btn-eco,
            .lp-hero .hero-actions .btn-outline-eco {
                width: 100%;
                justify-content: center;
                padding: 0.7rem 1.25rem;
            }

            .hero-flow {
                padding: 1.25rem;
                text-align: left; /* Keep the 4-step checklist easily readable */
            }
            .hero-flow-step {
                gap: 0.75rem;
            }
            .step-card {
                padding: 1.25rem;
            }
            .feature-box {
                padding: 1.25rem;
            }
            .formula-block {
                width: 100%;
                font-size: 0.88rem;
                padding: 1rem;
                word-break: break-word;
            }
            section {
                padding: 2.75rem 0;
            }
            .lp-cta {
                padding: 3.5rem 0;
            }
            .tilt-card {
                transform: none !important; /* Disable 3D tilt on touch screens to eliminate viewport wobble */
            }
        }

        a:focus-visible { outline: 3px solid var(--eco); outline-offset: 2px; }
    </style>
</head>
<body>

    <!-- WebGL Canvas for Real-time 3D Ambient Particles & Floating Eco Elements -->
    <canvas id="webgl-canvas"></canvas>

    {{-- Navbar --}}
    <nav class="lp-navbar" id="mainNavbar">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="{{ route('landing') }}" class="brand-group">
                <div class="brand-icon">
                    <i class="bi bi-recycle"></i>
                </div>
                <div class="d-flex align-items-center">
                    <span class="wordmark">ECOCASH</span>
                    <span class="brand-tagline">peduli bumi & cuan</span>
                </div>
            </a>
            <div class="d-flex gap-2 align-items-center">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-eco">
                        <i class="bi bi-speedometer2"></i>
                        <span>Dashboard</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn-outline-eco">
                        <i class="bi bi-box-arrow-in-right"></i>
                        <span>Masuk</span>
                    </a>
                    <a href="{{ route('register') }}" class="btn-eco">
                        <i class="bi bi-person-plus"></i>
                        <span>Daftar</span>
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- Hero --}}
    <section class="lp-hero" style="border-top:none;">
        <div class="container position-relative" style="z-index: 1;">
            <div class="row align-items-center g-5">
                <div class="col-lg-6 reveal-on-scroll">
                    <span class="hero-grandma-tag anim-float">Gerakan Hijau Berkelanjutan</span>
                    <h1>Ubah Sampah<br>Menjadi <span class="text-highlight">Nilai Nyata.</span></h1>
                    <p class="lead mt-3 mb-4">
                        Kenali material daur ulang lewat pemindai cerdas, setor ke mitra bank sampah terdekat, dan nikmati saldo tunai serta ECOPOINT.
                    </p>
                    <div class="hero-actions d-flex gap-3 flex-wrap align-items-center">
                        @auth
                            <a href="{{ route('scanner') }}" class="btn-eco" style="padding:0.75rem 1.65rem; font-size:1rem;">
                                <i class="bi bi-camera"></i> Mulai Setor Sampah
                            </a>
                            <a href="{{ route('dashboard') }}" class="btn-outline-eco" style="padding:0.75rem 1.65rem; font-size:1rem;">
                                <i class="bi bi-grid"></i> Buka Dashboard
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="btn-eco" style="padding:0.75rem 1.65rem; font-size:1rem;">
                                <i class="bi bi-arrow-right-circle"></i> Mulai Sekarang Gratis
                            </a>
                            <a href="#cara-kerja" class="btn-outline-eco" style="padding:0.75rem 1.65rem; font-size:1rem;">
                                <i class="bi bi-info-circle"></i> Cara Kerjanya
                            </a>
                        @endauth
                    </div>
                </div>
                <div class="col-lg-6 reveal-on-scroll reveal-delay-2">
                    <div class="hero-flow tilt-card" id="heroTiltCard">
                        <div class="hero-flow-header">
                            <span class="hero-flow-header-title">Alur Mudah 4 Langkah</span>
                            <span class="hero-flow-header-badge">praktis & cepat</span>
                        </div>
                        <div class="hero-flow-step">
                            <div class="hero-flow-icon"><i class="bi bi-camera-fill"></i></div>
                            <div class="hero-flow-text">
                                <strong>1. Foto Material</strong>
                                <span>Ambil foto sampah daur ulangmu langsung dari kamera ponsel.</span>
                            </div>
                        </div>
                        <div class="hero-flow-step">
                            <div class="hero-flow-icon"><i class="bi bi-cpu-fill"></i></div>
                            <div class="hero-flow-text">
                                <strong>2. Kenali Kategori</strong>
                                <span>AI memprediksi material. Kamu memiliki kontrol penuh untuk konfirmasi akhir.</span>
                            </div>
                        </div>
                        <div class="hero-flow-step">
                            <div class="hero-flow-icon"><i class="bi bi-geo-alt-fill"></i></div>
                            <div class="hero-flow-text">
                                <strong>3. Setor ke Bank Sampah</strong>
                                <span>Mitra memverifikasi kondisi aktual dan berat timbangan secara transparan.</span>
                            </div>
                        </div>
                        <div class="hero-flow-step">
                            <div class="hero-flow-icon"><i class="bi bi-wallet2"></i></div>
                            <div class="hero-flow-text">
                                <strong>4. Terima Rupiah & ECOPOINT</strong>
                                <span>Saldo langsung tercatat di dompet dan siap kamu cairkan kapan saja.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Problem --}}
    <section id="masalah">
        <div class="container">
            <div class="reveal-on-scroll">
                <div class="section-label">Realitas Lingkungan</div>
                <h2 class="section-title">Sampah bernilai masih sering berakhir di TPA.</h2>
                <div class="problem-body">
                    <p>
                        Di banyak wilayah, sampah anorganik seperti botol plastik, kardus, logam, dan kaca masih sering tercampur begitu saja lalu menumpuk di tempat pembuangan akhir. Potensi ekonomi materialnya hilang, sementara beban lingkungan semakin membesar.
                    </p>
                    <p>
                        Kendalanya bukan ketiadaan niat baik, melainkan kebingungan: belum terbiasa memilah, bingung mencari mitra terdekat, dan belum mengetahui nilai ekonominya. ECOCASH menyederhanakan ketiganya ke dalam satu genggaman.
                    </p>
                </div>
                <div class="eco-callout mt-4" style="max-width:540px;">
                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                    Pemilahan dimulai dari rumah. Skala dampaknya tercipta ketika prosesnya mudah dan saling menguntungkan.
                </div>
            </div>
        </div>
    </section>

    {{-- How it works --}}
    <section id="cara-kerja">
        <div class="container">
            <div class="section-label reveal-on-scroll">Langkah Sederhana</div>
            <h2 class="section-title reveal-on-scroll">Tiga tahap dari foto menjadi nilai.</h2>
            <div class="step-row mt-4">
                <div class="step-card tilt-card reveal-on-scroll reveal-delay-1">
                    <div class="step-number">Langkah 01</div>
                    <div class="step-name">Kenali & Pilah</div>
                    <p class="step-desc">
                        Ambil foto sampah yang ingin kamu pilah. Sistem cerdas memprediksi jenis kategorinya. Kamu menentukan kategori final sebelum setoran diserahkan.
                    </p>
                </div>
                <div class="step-card tilt-card reveal-on-scroll reveal-delay-2">
                    <div class="step-number">Langkah 02</div>
                    <div class="step-name">Bawa ke Mitra</div>
                    <p class="step-desc">
                        Kunjungi bank sampah mitra terdekat. Petugas mitra memverifikasi berat dan kondisi fisik secara terbuka sebagai dasar acuan data yang sah.
                    </p>
                </div>
                <div class="step-card tilt-card reveal-on-scroll reveal-delay-3">
                    <div class="step-number">Langkah 03</div>
                    <div class="step-name">Cairkan Manfaat</div>
                    <p class="step-desc">
                        Setelah disetujui, nominal saldo terhitung otomatis berdasarkan tarif per kilogram. Kamu juga mendapatkan reward ECOPOINT untuk setiap kontribusimu.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- AI Scanner --}}
    <section id="scanner">
        <div class="container">
            <div class="row g-5 align-items-start">
                <div class="col-md-6 reveal-on-scroll">
                    <div class="section-label">Deteksi Otomatis</div>
                    <h2 class="section-title">Kenali material sampah dengan kamera.</h2>
                    <p style="color:var(--ink-muted); line-height:1.75; font-size:1rem;">
                        Fitur scanner memanfaatkan kecerdasan buatan untuk mengklasifikasikan foto material secara instan, mencakup plastik PET/HDPE, kardus, kertas, logam, hingga kaca.
                    </p>
                    <p style="color:var(--ink-muted); line-height:1.75; font-size:1rem;">
                        Prediksi sistem hadir sebagai panduan praktis. Kamu tetap memegang kendali penuh untuk menyetujui atau menyesuaikan jenis material sebelum diserahkan ke bank sampah.
                    </p>
                    <div class="eco-callout">
                        <i class="bi bi-shield-check text-success me-2"></i>
                        AI memprediksi dan memberi rekomendasi. Konfirmasi fisik mitra bank sampah menjamin akurasi timbangan dan harga akhir.
                    </div>
                </div>
                <div class="col-md-6 reveal-on-scroll reveal-delay-2">
                    <div class="feature-box tilt-card">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-camera-video text-success" style="font-size:1.25rem;"></i>
                                <span class="fw-bold" style="font-family:var(--font-rubik); color:var(--ink);">Alur Scan Praktis</span>
                            </div>
                            <span class="hero-flow-header-badge">kamera ponsel</span>
                        </div>
                        <ol style="padding-left:1.25rem; color:var(--ink-muted); line-height:2.1; font-size:0.95rem; margin:0;">
                            <li>Buka menu <strong>Scan</strong> di aplikasi</li>
                            <li>Ambil foto sampah daur ulangmu dengan jelas</li>
                            <li>Lihat estimasi klasifikasi material dan rekomendasi</li>
                            <li>Konfirmasi kategori dan buat tiket setoran</li>
                            <li>Bawa ke mitra terdekat untuk penimbangan nyata</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Nilai & ECOPOINT (Compacted 2-Column Grid on Desktop) --}}
    <section id="nilai" class="py-5">
        <div class="container">
            <div class="row g-4 align-items-stretch">
                {{-- Kolom Nilai Setoran --}}
                <div class="col-lg-6 reveal-on-scroll">
                    <div class="feature-box h-100 p-4 tilt-card d-flex flex-column justify-content-between">
                        <div>
                            <div class="section-label">Konversi Transparan</div>
                            <h2 class="section-title mb-2" style="font-size: 1.45rem;">Bagaimana nilai rupiah dihitung?</h2>
                            <p style="color:var(--ink-muted); line-height:1.65; font-size:0.925rem; margin-bottom:1.25rem;">
                                Setiap material memiliki nilai ekonomi. Tarif per kilogram ditetapkan terbuka oleh mitra bank sampah mengikuti standar pasar lokal.
                            </p>
                            <div class="formula-block mb-3 p-3 w-100 text-center">
                                <span class="formula-var">Berat (kg)</span>
                                <span style="color:#64748b; font-weight:600;"> &times; </span>
                                <span class="formula-var">Tarif/Kg (Rp)</span>
                                <span style="color:#64748b; font-weight:600;"> = </span>
                                <span class="formula-result">Saldo Masuk (Rp)</span>
                            </div>
                        </div>
                        <p style="color:#64748b; font-size:0.82rem; margin:0;">
                            <i class="bi bi-info-circle me-1"></i> Mengacu pada timbangan fisik terverifikasi oleh mitra.
                        </p>
                    </div>
                </div>

                {{-- Kolom ECOPOINT --}}
                <div class="col-lg-6 reveal-on-scroll reveal-delay-1">
                    <div class="feature-box h-100 p-4 tilt-card d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="section-label m-0">Loyalitas Ekologis</div>
                                <div class="ecopoint-badge m-0 px-2 py-1" style="font-size:0.78rem;">
                                    <i class="bi bi-stars"></i> REWARD ECOPOINT
                                </div>
                            </div>
                            <h2 class="section-title mb-2" style="font-size: 1.45rem;">Dapatkan ECOPOINT di setiap setoran.</h2>
                            <p style="color:var(--ink-muted); line-height:1.65; font-size:0.925rem; margin-bottom:1.25rem;">
                                Selain saldo tunai ke dompet, setiap kilogram sampah daur ulang menghasilkan poin loyalitas untuk reward khusus dan peningkatan level.
                            </p>
                        </div>
                        <div class="eco-callout p-3 m-0" style="font-size:0.875rem;">
                            <i class="bi bi-award-fill text-warning me-2"></i>
                            Setiap kilogram sampah yang diselamatkan dari TPA dihitung dan dihargai.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Dampak Lingkungan & Jaringan Mitra (Compacted 2-Column Grid on Desktop) --}}
    <section id="dampak-mitra" class="py-5">
        <div class="container">
            <div class="row g-4 align-items-stretch">
                {{-- Kolom Dampak Lingkungan --}}
                <div class="col-lg-6 reveal-on-scroll">
                    <div class="feature-box h-100 p-4 tilt-card d-flex flex-column justify-content-between">
                        <div>
                            <div class="section-label">Jejak Kebaikan</div>
                            <h2 class="section-title mb-2" style="font-size: 1.45rem;">Estimasi dampak pengurangan CO₂.</h2>
                            <p style="color:var(--ink-muted); line-height:1.65; font-size:0.925rem; margin-bottom:1.25rem;">
                                Daur ulang mengurangi kebutuhan bahan baku baru dan menekan emisi karbon. ECOCASH menampilkan estimasi emisi gas rumah kaca yang kamu cegah.
                            </p>
                        </div>
                        <div class="p-3 bg-light rounded-3 border">
                            <span class="fw-bold d-block mb-1" style="font-size:0.85rem; color:var(--ink);">Metodologi Transparan:</span>
                            <ul style="color:var(--ink-muted); font-size:0.82rem; line-height:1.6; padding-left:1.2rem; margin:0;">
                                <li>Faktor emisi dihitung per kilogram kategori material terverifikasi</li>
                                <li>Memberi gambaran nyata peranmu menjaga kelestarian bumi</li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Kolom Jaringan Mitra --}}
                <div class="col-lg-6 reveal-on-scroll reveal-delay-1">
                    <div class="feature-box h-100 p-4 tilt-card d-flex flex-column justify-content-between">
                        <div>
                            <div class="section-label">Kolaborasi Komunitas</div>
                            <h2 class="section-title mb-2" style="font-size: 1.45rem;">Jaringan Bank Sampah Mitra.</h2>
                            <p style="color:var(--ink-muted); line-height:1.65; font-size:0.925rem; margin-bottom:1.25rem;">
                                Terhubung langsung dengan pengelola bank sampah terdekat untuk penimbangan transparan dan validasi digital.
                            </p>
                        </div>
                        <div class="d-flex flex-column gap-2" style="font-size:0.85rem; color:var(--ink-muted);">
                            <div class="d-flex gap-2">
                                <i class="bi bi-check2-circle text-success"></i>
                                <span>Menerima sampah terpilah langsung di titik layanan</span>
                            </div>
                            <div class="d-flex gap-2">
                                <i class="bi bi-check2-circle text-success"></i>
                                <span>Menimbang secara akurat dan terbuka di depan nasabah</span>
                            </div>
                            <div class="d-flex gap-2">
                                <i class="bi bi-check2-circle text-success"></i>
                                <span>Mengonfirmasi kategori dan mengesahkan saldo dompet</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="lp-cta">
        <div class="container text-center position-relative" style="z-index:1;">
            <div class="cta-grandma-sub reveal-on-scroll">langkah kecil, dampak nyata</div>
            <h2 class="section-title mb-3 reveal-on-scroll">Mulai Gunakan ECOCASH Hari Ini.</h2>
            <p style="color:rgba(255,255,255,0.88); max-width:480px; margin:0 auto 2.25rem; line-height:1.65; font-size:1.05rem;" class="reveal-on-scroll reveal-delay-1">
                Daftar akun gratis, pindai sampah pertamamu, dan ubah kepedulian lingkunganmu menjadi tabungan nyata.
            </p>
            <div class="reveal-on-scroll reveal-delay-2">
                @auth
                    <a href="{{ route('scanner') }}" class="btn-cta-white">
                        <i class="bi bi-camera"></i>
                        <span>Scan Sampah Sekarang</span>
                    </a>
                @else
                    <a href="{{ route('register') }}" class="btn-cta-white">
                        <i class="bi bi-person-plus-fill"></i>
                        <span>Daftar Gratis Sekarang</span>
                    </a>
                @endauth
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="lp-footer">
        <div class="container">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                <div>
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span class="fw-bold" style="font-family:var(--font-rubik); font-size:1.15rem; color:var(--eco);">ECOCASH</span>
                        <span class="footer-tagline">peduli bumi & cuan</span>
                    </div>
                    <div>Sampah &rarr; Nilai &rarr; Dampak Berkelanjutan</div>
                </div>
                <nav class="d-flex gap-4">
                    <a href="{{ route('landing') }}">Beranda</a>
                    @auth
                        <a href="{{ route('scanner') }}">Scan</a>
                        <a href="{{ route('setoran') }}">Setoran</a>
                    @else
                        <a href="{{ route('login') }}">Masuk</a>
                        <a href="{{ route('register') }}">Daftar</a>
                    @endauth
                </nav>
            </div>
        </div>
    </footer>

    {{-- Interactive WebGL, Scrollytelling & 3D Hover Scripts --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // ── 1. WebGL Three.js Real-time Ambient Animation ──
            const canvas = document.getElementById('webgl-canvas');
            if (canvas && typeof THREE !== 'undefined') {
                const scene = new THREE.Scene();
                const camera = new THREE.PerspectiveCamera(60, window.innerWidth / window.innerHeight, 0.1, 1000);
                camera.position.z = 40;

                const renderer = new THREE.WebGLRenderer({ canvas: canvas, alpha: true, antialias: true });
                renderer.setSize(window.innerWidth, window.innerHeight);
                renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));

                // Ambient floating eco particles
                const particleCount = 120;
                const geometry = new THREE.BufferGeometry();
                const positions = new Float32Array(particleCount * 3);
                const colors = new Float32Array(particleCount * 3);

                const colorForest = new THREE.Color(0x15803d);
                const colorEmerald = new THREE.Color(0x22c55e);
                const colorGold = new THREE.Color(0xf59e0b);

                for (let i = 0; i < particleCount; i++) {
                    positions[i * 3] = (Math.random() - 0.5) * 80;
                    positions[i * 3 + 1] = (Math.random() - 0.5) * 80;
                    positions[i * 3 + 2] = (Math.random() - 0.5) * 50;

                    const pickColor = Math.random() > 0.7 ? colorGold : (Math.random() > 0.5 ? colorEmerald : colorForest);
                    colors[i * 3] = pickColor.r;
                    colors[i * 3 + 1] = pickColor.g;
                    colors[i * 3 + 2] = pickColor.b;
                }

                geometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));
                geometry.setAttribute('color', new THREE.BufferAttribute(colors, 3));

                // Canvas texture for smooth glowing dots
                const dotCanvas = document.createElement('canvas');
                dotCanvas.width = 32;
                dotCanvas.height = 32;
                const ctx = dotCanvas.getContext('2d');
                const gradient = ctx.createRadialGradient(16, 16, 0, 16, 16, 16);
                gradient.addColorStop(0, 'rgba(255,255,255,1)');
                gradient.addColorStop(0.5, 'rgba(34,197,94,0.6)');
                gradient.addColorStop(1, 'rgba(34,197,94,0)');
                ctx.fillStyle = gradient;
                ctx.fillRect(0, 0, 32, 32);

                const texture = new THREE.CanvasTexture(dotCanvas);
                const material = new THREE.PointsMaterial({
                    size: 2.2,
                    vertexColors: true,
                    map: texture,
                    transparent: true,
                    opacity: 0.75,
                    blending: THREE.AdditiveBlending,
                    depthWrite: false
                });

                const particles = new THREE.Points(geometry, material);
                scene.add(particles);

                // Mouse interaction for WebGL
                let mouseX = 0;
                let mouseY = 0;
                let targetX = 0;
                let targetY = 0;
                window.addEventListener('mousemove', (e) => {
                    mouseX = (e.clientX / window.innerWidth - 0.5) * 2;
                    mouseY = (e.clientY / window.innerHeight - 0.5) * 2;
                });

                // Window resize
                window.addEventListener('resize', () => {
                    camera.aspect = window.innerWidth / window.innerHeight;
                    camera.updateProjectionMatrix();
                    renderer.setSize(window.innerWidth, window.innerHeight);
                });

                // Animation loop
                let clock = new THREE.Clock();
                function animate() {
                    requestAnimationFrame(animate);
                    const elapsedTime = clock.getElapsedTime();

                    targetX += (mouseX - targetX) * 0.05;
                    targetY += (mouseY - targetY) * 0.05;

                    particles.rotation.y = elapsedTime * 0.04 + targetX * 0.2;
                    particles.rotation.x = Math.sin(elapsedTime * 0.05) * 0.1 - targetY * 0.2;

                    renderer.render(scene, camera);
                }
                animate();
            }

            // ── 2. Scrollytelling: Intersection Observer for Smooth Reveal ──
            const revealElements = document.querySelectorAll('.reveal-on-scroll');
            if ('IntersectionObserver' in window) {
                const observerOptions = {
                    root: null,
                    threshold: 0.15,
                    rootMargin: '0px 0px -40px 0px'
                };
                const observer = new IntersectionObserver((entries, obs) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('is-revealed');
                            obs.unobserve(entry.target);
                        }
                    });
                }, observerOptions);

                revealElements.forEach(el => observer.observe(el));
            } else {
                revealElements.forEach(el => el.classList.add('is-revealed'));
            }

            // ── 3. Navbar Scrolled Glassmorphism State ──
            const navbar = document.getElementById('mainNavbar');
            window.addEventListener('scroll', () => {
                if (window.scrollY > 20) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
            });

            // ── 4. Interactive 3D Tilt Hover Effects on Cards ──
            const tiltCards = document.querySelectorAll('.tilt-card');
            tiltCards.forEach(card => {
                card.addEventListener('mousemove', (e) => {
                    const rect = card.getBoundingClientRect();
                    const x = e.clientX - rect.left;
                    const y = e.clientY - rect.top;
                    const centerX = rect.width / 2;
                    const centerY = rect.height / 2;

                    const rotateX = ((y - centerY) / centerY) * -8;
                    const rotateY = ((x - centerX) / centerX) * 8;

                    card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-4px)`;
                });

                card.addEventListener('mouseleave', () => {
                    card.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg) translateY(0px)';
                });
            });
        });
    </script>
</body>
</html>
