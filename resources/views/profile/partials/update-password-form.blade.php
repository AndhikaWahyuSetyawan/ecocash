<section>
    <div class="d-flex align-items-center gap-2 mb-1">
        <div class="p-2 rounded-3 bg-light text-primary d-inline-flex">
            <i class="bi bi-shield-lock-fill fs-5"></i>
        </div>
        <div>
            <h2 class="fw-bold mb-0" style="font-size: 1.05rem; color: var(--ink);">Keamanan & Kata Sandi</h2>
            <p class="text-muted small mb-0">Perbarui kata sandi secara berkala untuk menjaga akun tetap aman.</p>
        </div>
    </div>

    <hr class="my-3" style="border-color: #f1f5f9;">

    <form method="post" action="{{ route('password.update') }}">
        @csrf
        @method('put')

        <div class="row g-3">
            <div class="col-12">
                <label for="current_password" class="form-label small fw-semibold text-dark">Kata Sandi Saat Ini</label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted border-end-0" style="border-radius: 10px 0 0 10px;">
                        <i class="bi bi-key"></i>
                    </span>
                    <input id="current_password"
                           name="current_password"
                           type="password"
                           class="form-control border-start-0 @error('current_password', 'updatePassword') is-invalid @enderror"
                           style="border-radius: 0 10px 10px 0;"
                           autocomplete="current-password"
                           placeholder="Masukkan kata sandi lama">
                </div>
                @error('current_password', 'updatePassword')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 col-md-6">
                <label for="password" class="form-label small fw-semibold text-dark">Kata Sandi Baru</label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted border-end-0" style="border-radius: 10px 0 0 10px;">
                        <i class="bi bi-lock"></i>
                    </span>
                    <input id="password"
                           name="password"
                           type="password"
                           class="form-control border-start-0 @error('password', 'updatePassword') is-invalid @enderror"
                           style="border-radius: 0 10px 10px 0;"
                           autocomplete="new-password"
                           placeholder="Minimal 8 karakter">
                </div>
                @error('password', 'updatePassword')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 col-md-6">
                <label for="password_confirmation" class="form-label small fw-semibold text-dark">Konfirmasi Kata Sandi Baru</label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted border-end-0" style="border-radius: 10px 0 0 10px;">
                        <i class="bi bi-lock-fill"></i>
                    </span>
                    <input id="password_confirmation"
                           name="password_confirmation"
                           type="password"
                           class="form-control border-start-0 @error('password_confirmation', 'updatePassword') is-invalid @enderror"
                           style="border-radius: 0 10px 10px 0;"
                           autocomplete="new-password"
                           placeholder="Ulangi kata sandi baru">
                </div>
                @error('password_confirmation', 'updatePassword')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 pt-2 text-end">
                <button type="submit" class="btn btn-eco px-4 py-2 d-inline-flex align-items-center gap-2">
                    <i class="bi bi-shield-check"></i>
                    <span>Simpan Kata Sandi</span>
                </button>
            </div>
        </div>
    </form>
</section>

