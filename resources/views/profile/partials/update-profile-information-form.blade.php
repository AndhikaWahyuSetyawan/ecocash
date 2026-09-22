<section>
    <div class="d-flex align-items-center gap-2 mb-1">
        <div class="p-2 rounded-3 bg-light text-success d-inline-flex">
            <i class="bi bi-person-lines-fill fs-5"></i>
        </div>
        <div>
            <h2 class="fw-bold mb-0" style="font-size: 1.05rem; color: var(--ink);">Informasi Data Pribadi</h2>
            <p class="text-muted small mb-0">Kelola nama lengkap, kontak WhatsApp, dan alamat email aktif kamu.</p>
        </div>
    </div>

    <hr class="my-3" style="border-color: #f1f5f9;">

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">@csrf</form>

    <form method="post" action="{{ route('profile.update') }}">
        @csrf
        @method('patch')

        <div class="row g-3">
            <div class="col-12 col-md-6">
                <label for="name" class="form-label small fw-semibold text-dark">Nama Lengkap <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted border-end-0" style="border-radius: 10px 0 0 10px;">
                        <i class="bi bi-person"></i>
                    </span>
                    <input id="name"
                           name="name"
                           type="text"
                           class="form-control border-start-0 @error('name') is-invalid @enderror"
                           style="border-radius: 0 10px 10px 0;"
                           value="{{ old('name', $user->name) }}"
                           required
                           autofocus
                           autocomplete="name"
                           placeholder="Nama lengkap kamu">
                </div>
                @error('name')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 col-md-6">
                <label for="phone" class="form-label small fw-semibold text-dark">Nomor WhatsApp</label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted border-end-0" style="border-radius: 10px 0 0 10px;">
                        <i class="bi bi-whatsapp"></i>
                    </span>
                    <input id="phone"
                           name="phone"
                           type="tel"
                           class="form-control border-start-0 @error('phone') is-invalid @enderror"
                           style="border-radius: 0 10px 10px 0;"
                           value="{{ old('phone', $user->phone) }}"
                           placeholder="Contoh: 081234567890">
                </div>
                <div class="form-text text-muted" style="font-size: 0.76rem;">Digunakan untuk konfirmasi setoran bank sampah.</div>
                @error('phone')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12">
                <label for="email" class="form-label small fw-semibold text-dark">Alamat Email <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted border-end-0" style="border-radius: 10px 0 0 10px;">
                        <i class="bi bi-envelope"></i>
                    </span>
                    <input id="email"
                           name="email"
                           type="email"
                           class="form-control border-start-0 @error('email') is-invalid @enderror"
                           style="border-radius: 0 10px 10px 0;"
                           value="{{ old('email', $user->email) }}"
                           required
                           autocomplete="username"
                           placeholder="email@domain.com">
                </div>
                @error('email')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="p-3 mt-3 rounded-3 bg-warning-subtle border border-warning-subtle d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-exclamation-triangle-fill text-warning-emphasis fs-5"></i>
                            <span class="small text-warning-emphasis">Alamat email kamu belum terverifikasi.</span>
                        </div>
                        <button form="send-verification"
                                class="btn btn-sm btn-warning text-dark fw-semibold"
                                style="font-size: 0.8rem; border-radius: 8px;">
                            Kirim Ulang Verifikasi
                        </button>
                    </div>
                @endif
            </div>

            <div class="col-12 pt-2 text-end">
                <button type="submit" class="btn btn-eco px-4 py-2 d-inline-flex align-items-center gap-2">
                    <i class="bi bi-floppy"></i>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </div>
    </form>
</section>

