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

    <style>
        :root {
            --eco:        #15803d;
            --eco-hover:  #14532d;
            --eco-pale:   #dcfce7;
            --ink:        #17211b;
            --paper:      #f7faf8;
            --border:     #e2e8f0;
        }
        *, *::before, *::after { box-sizing: border-box; }
        body {
            background: var(--paper);
            color: var(--ink);
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }
        .auth-container {
            width: 100%;
            max-width: 440px;
        }
        .auth-card {
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 2.25rem 2rem;
            box-shadow: 0 4px 16px rgba(0,0,0,0.04);
        }
        .brand-logo {
            font-weight: 800;
            font-size: 1.4rem;
            color: var(--eco);
            text-decoration: none;
            letter-spacing: -0.02em;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .btn-eco {
            background: var(--eco);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            padding: 0.75rem;
            width: 100%;
            transition: background 0.12s;
        }
        .btn-eco:hover { background: var(--eco-hover); color: #fff; }
        .form-control {
            border-radius: 8px;
            border: 1px solid var(--border);
            padding: 0.65rem 0.85rem;
            font-size: 0.925rem;
        }
        .form-control:focus {
            border-color: var(--eco);
            box-shadow: 0 0 0 3px rgba(21,128,61,0.15);
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="text-center mb-4">
            <a href="/" class="brand-logo">
                <i class="bi bi-recycle fs-3"></i>
                <span>ECOCASH</span>
            </a>
            <p class="text-muted small mt-1 mb-0">Platform konversi sampah terpilah menjadi nilai dan dampak.</p>
        </div>

        <div class="auth-card">
            {{ $slot }}
        </div>

        <div class="text-center mt-4">
            <a href="/" class="text-muted small text-decoration-none">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Beranda
            </a>
        </div>
    </div>
</body>
</html>
