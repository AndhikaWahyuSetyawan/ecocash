<x-guest-layout>
    <div class="mb-4">
        <h1 class="h5 fw-bold mb-1" style="color: var(--ink);">Daftar Akun ECOCASH</h1>
        <p class="text-muted small mb-0">Mulai langkah kecil mengubah sampah rumah tangga menjadi nilai nyata.</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        {{-- Name --}}
        <div class="mb-3">
            <label for="name" class="form-label small fw-semibold">Nama Lengkap</label>
            <input id="name" class="form-control @error('name') is-invalid @enderror"
                   type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                   placeholder="Contoh: Budi Santoso">
            @error('name')
                <div class="invalid-feedback small">{{ $message }}</div>
            @enderror
        </div>

        {{-- Email Address --}}
        <div class="mb-3">
            <label for="email" class="form-label small fw-semibold">Alamat Email</label>
            <input id="email" class="form-control @error('email') is-invalid @enderror"
                   type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                   placeholder="nama@email.com">
            @error('email')
                <div class="invalid-feedback small">{{ $message }}</div>
            @enderror
        </div>

        {{-- Phone / WhatsApp --}}
        <div class="mb-3">
            <label for="phone" class="form-label small fw-semibold">Nomor WhatsApp (Opsional)</label>
            <input id="phone" class="form-control @error('phone') is-invalid @enderror"
                   type="tel" name="phone" value="{{ old('phone') }}"
                   placeholder="Contoh: 081234567890">
            @error('phone')
                <div class="invalid-feedback small">{{ $message }}</div>
            @enderror
            <div class="form-text text-muted" style="font-size: 0.75rem;">
                Digunakan untuk kemudahan koordinasi penjemputan atau konfirmasi mitra.
            </div>
        </div>

        {{-- Password --}}
        <div class="mb-3">
            <label for="password" class="form-label small fw-semibold">Kata Sandi</label>
            <input id="password" class="form-control @error('password') is-invalid @enderror"
                   type="password" name="password" required autocomplete="new-password"
                   placeholder="Minimal 8 karakter">
            @error('password')
                <div class="invalid-feedback small">{{ $message }}</div>
            @enderror
        </div>

        {{-- Confirm Password --}}
        <div class="mb-4">
            <label for="password_confirmation" class="form-label small fw-semibold">Ulangi Kata Sandi</label>
            <input id="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror"
                   type="password" name="password_confirmation" required autocomplete="new-password"
                   placeholder="Ketik ulang kata sandi">
            @error('password_confirmation')
                <div class="invalid-feedback small">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn-eco mb-3">
            Daftar Sekarang
        </button>

        <div class="text-center pt-2 border-top">
            <span class="text-muted small">Sudah punya akun?</span>
            <a href="{{ route('login') }}" class="small fw-semibold text-success text-decoration-none ms-1">
                Masuk di Sini
            </a>
        </div>
    </form>
</x-guest-layout>
