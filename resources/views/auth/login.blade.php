<x-guest-layout>
    <div class="mb-4">
        <h1 class="h5 fw-bold mb-1" style="color: var(--ink);">Masuk ke ECOCASH</h1>
        <p class="text-muted small mb-0">Lanjutkan pemilahan sampah dan pantau saldo kakak.</p>
    </div>

    @if (session('status'))
        <div class="alert alert-success small mb-4 py-2" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        {{-- Email Address --}}
        <div class="mb-3">
            <label for="email" class="form-label small fw-semibold">Alamat Email</label>
            <input id="email" class="form-control @error('email') is-invalid @enderror"
                   type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                   placeholder="nama@email.com">
            @error('email')
                <div class="invalid-feedback small">{{ $message }}</div>
            @enderror
        </div>

        {{-- Password --}}
        <div class="mb-3">
            <div class="d-flex align-items-center justify-content-between mb-1">
                <label for="password" class="form-label small fw-semibold mb-0">Kata Sandi</label>
                @if (Route::has('password.request'))
                    <a class="text-decoration-none small text-muted" href="{{ route('password.request') }}">
                        Lupa kata sandi?
                    </a>
                @endif
            </div>
            <input id="password" class="form-control @error('password') is-invalid @enderror"
                   type="password" name="password" required autocomplete="current-password"
                   placeholder="••••••••">
            @error('password')
                <div class="invalid-feedback small">{{ $message }}</div>
            @enderror
        </div>

        {{-- Remember Me --}}
        <div class="form-check mb-4">
            <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
            <label for="remember_me" class="form-check-label small text-muted">
                Ingat saya di perangkat ini
            </label>
        </div>

        <button type="submit" class="btn-eco mb-3">
            Masuk Sekarang
        </button>

        <div class="text-center pt-2 border-top">
            <span class="text-muted small">Belum punya akun?</span>
            <a href="{{ route('register') }}" class="small fw-semibold text-success text-decoration-none ms-1">
                Daftar Akun Baru
            </a>
        </div>
    </form>
</x-guest-layout>
