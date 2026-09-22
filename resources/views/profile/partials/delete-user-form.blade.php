<section>
    <div class="d-flex align-items-center gap-2 mb-1">
        <div class="p-2 rounded-3 bg-danger-subtle text-danger d-inline-flex">
            <i class="bi bi-exclamation-triangle-fill fs-5"></i>
        </div>
        <div>
            <h2 class="fw-bold mb-0" style="font-size: 1.05rem; color: #991b1b;">Zona Bahaya (Hapus Akun)</h2>
            <p class="text-muted small mb-0">Setelah akun dihapus, seluruh riwayat setoran, saldo dompet, dan poin akan dihapus secara permanen.</p>
        </div>
    </div>

    <hr class="my-3" style="border-color: #fee2e2;">

    <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-3">
        <div class="small text-muted" style="max-width: 500px;">
            Pastikan kamu telah menarik semua sisa saldo tunai di dompet sebelum menghapus akun ini.
        </div>
        <button type="button"
                class="btn btn-danger btn-sm px-3 py-2 fw-semibold d-inline-flex align-items-center gap-2 text-nowrap"
                style="border-radius: 8px;"
                data-bs-toggle="modal"
                data-bs-target="#deleteAccountModal">
            <i class="bi bi-trash3"></i>
            <span>Hapus Akun Saya</span>
        </button>
    </div>
</section>

{{-- Confirmation Modal --}}
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; border: 1px solid #fecaca; overflow: hidden;">
            <div class="modal-header" style="background: #fef2f2; border-bottom: 1px solid #fee2e2;">
                <h5 class="modal-title fw-bold d-flex align-items-center gap-2" id="deleteAccountModalLabel" style="color: #991b1b; font-size: 1.05rem;">
                    <i class="bi bi-exclamation-octagon-fill text-danger"></i> Konfirmasi Penghapusan Akun
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Batal"></button>
            </div>
            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')
                <div class="modal-body p-4">
                    <p class="small text-secondary mb-3">
                        Tindakan ini bersifat <strong>permanen dan tidak dapat dibatalkan</strong>. Silakan masukkan kata sandi kamu saat ini untuk melanjutkan konfirmasi penghapusan akun.
                    </p>
                    <label for="delete_password" class="form-label small fw-semibold text-dark">Kata Sandi Saat Ini</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-muted border-end-0">
                            <i class="bi bi-key"></i>
                        </span>
                        <input id="delete_password"
                               name="password"
                               type="password"
                               class="form-control border-start-0 @error('password', 'userDeletion') is-invalid @enderror"
                               placeholder="Ketikkan kata sandi kamu"
                               autocomplete="current-password"
                               required>
                    </div>
                    @error('password', 'userDeletion')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="modal-footer bg-light px-4 py-3" style="border-top: 1px solid #f1f5f9;">
                    <button type="button"
                            class="btn btn-sm btn-outline-secondary px-3 py-2 fw-semibold"
                            style="border-radius: 8px;"
                            data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-sm btn-danger px-3 py-2 fw-semibold d-inline-flex align-items-center gap-2" style="border-radius: 8px;">
                        <i class="bi bi-trash"></i>
                        <span>Ya, Hapus Permanen</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@if($errors->userDeletion->isNotEmpty())
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var modal = new bootstrap.Modal(document.getElementById('deleteAccountModal'));
        modal.show();
    });
</script>
@endif

