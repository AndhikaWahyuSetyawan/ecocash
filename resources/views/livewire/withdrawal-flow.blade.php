<div>
    <x-ui.page-header
        title="Tarik Saldo ECOCASH"
        subtitle="Cairkan saldo hasil setoran sampah ke rekening bank atau dompet digital kakak."
    >
        <x-slot:actions>
            <a href="{{ route('wallet') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Batal & Kembali
            </a>
        </x-slot:actions>
    </x-ui.page-header>

    <div class="row g-4 justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            {{-- Informasi Saldo Saat Ini --}}
            <div class="eco-card p-3 mb-4 bg-light border-success-subtle d-flex align-items-center justify-content-between">
                <div>
                    <span class="small text-muted d-block" style="font-size: 0.78rem;">Saldo Tersedia Saat Ini</span>
                    <span class="fs-4 fw-bold text-success">
                        Rp{{ number_format($balance, 0, ',', '.') }}
                    </span>
                </div>
                <i class="bi bi-shield-check fs-2 text-success"></i>
            </div>

            @if($errorMessage)
                <div class="alert alert-danger small mb-4 d-flex align-items-center gap-2" role="alert">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    <div>{{ $errorMessage }}</div>
                </div>
            @endif

            {{-- Formulir Penarikan --}}
            <div class="eco-card p-4">
                <form wire:submit.prevent="submit">
                    {{-- Pilihan Metode --}}
                    <div class="mb-3">
                        <label for="methodId" class="form-label">Metode Pencairan <span class="text-danger">*</span></label>
                        <select id="methodId" class="form-select @error('methodId') is-invalid @enderror" wire:model.live="methodId">
                            <option value="">Pilih Bank atau E-Wallet</option>
                            @foreach($methods as $m)
                                <option value="{{ $m->id }}">
                                    {{ $m->name }} (Min. Rp{{ number_format($m->min_amount, 0, ',', '.') }})
                                </option>
                            @endforeach
                        </select>
                        @error('methodId')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Nama Pemilik Akun --}}
                    <div class="mb-3">
                        <label for="accountName" class="form-label">Nama Pemilik Rekening / Akun <span class="text-danger">*</span></label>
                        <input id="accountName" type="text" class="form-control @error('accountName') is-invalid @enderror"
                               wire:model="accountName" placeholder="Contoh: Budi Santoso">
                        @error('accountName')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Nomor Rekening / E-Wallet --}}
                    <div class="mb-3">
                        <label for="accountNumber" class="form-label">Nomor Rekening / No. HP E-Wallet <span class="text-danger">*</span></label>
                        <input id="accountNumber" type="text" class="form-control @error('accountNumber') is-invalid @enderror"
                               wire:model="accountNumber" placeholder="Contoh: 1234567890 atau 08123456789">
                        @error('accountNumber')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Nominal Penarikan --}}
                    <div class="mb-4">
                        <label for="amount" class="form-label">Nominal Penarikan (Rp) <span class="text-danger">*</span></label>
                        <input id="amount" type="number" step="1000" min="10000" max="{{ $balance }}"
                               class="form-control @error('amount') is-invalid @enderror"
                               wire:model="amount" placeholder="Minimal Rp10.000">
                        @error('amount')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                        <div class="form-text small text-muted">
                            Biaya transfer: <strong>Rp0 (Gratis)</strong>.
                        </div>
                    </div>

                    <button type="submit"
                            wire:loading.attr="disabled"
                            class="btn btn-eco w-100 py-3 d-flex align-items-center justify-content-center gap-2"
                            @if($balance < 10000) disabled @endif>
                        <span wire:loading.remove>
                            <i class="bi bi-check-circle me-1"></i> Ajukan Penarikan Sekarang
                        </span>
                        <span wire:loading>
                            <i class="bi bi-arrow-repeat spinner-border spinner-border-sm me-1"></i> Memproses...
                        </span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
