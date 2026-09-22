@props([
    'balance',
    'pendingBalance' => 0,
    'totalWithdrawn' => 0,
])

<div class="wallet-card-container p-4 rounded-3 text-white position-relative overflow-hidden mb-4 shadow-sm"
     style="background: linear-gradient(135deg, #14532d 0%, #15803d 100%);">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div class="d-flex align-items-center gap-2">
            <div class="wallet-chip d-flex align-items-center justify-content-center rounded-2"
                 style="background: rgba(255,255,255,0.2); width: 34px; height: 34px;">
                <i class="bi bi-wallet2 fs-5 text-white"></i>
            </div>
            <span class="fw-semibold text-white-50 small text-uppercase tracking-wider">Dompet ECOCASH</span>
        </div>
        <span class="badge px-2 py-1 rounded-pill" style="background: rgba(255,255,255,0.15); font-size: 0.75rem;">
            Rupiah Riil
        </span>
    </div>

    <div class="my-2">
        <div class="text-white-50 small mb-1">Saldo Tersedia (Dapat Ditarik)</div>
        <div class="display-6 fw-bold text-white tracking-tight">
            Rp{{ number_format($balance, 0, ',', '.') }}
        </div>
    </div>

    <div class="row g-2 mt-3 pt-3 border-top border-white-10">
        <div class="col-6">
            <div class="text-white-50 small" style="font-size: 0.78rem;">Saldo Tertunda</div>
            <div class="fw-semibold text-white">
                Rp{{ number_format($pendingBalance, 0, ',', '.') }}
            </div>
        </div>
        <div class="col-6 text-end">
            <div class="text-white-50 small" style="font-size: 0.78rem;">Total Ditarik</div>
            <div class="fw-semibold text-white">
                Rp{{ number_format($totalWithdrawn, 0, ',', '.') }}
            </div>
        </div>
    </div>

    <div class="mt-3 pt-2 d-flex gap-2">
        <a href="{{ route('wallet.withdraw') }}" class="btn btn-light fw-bold text-success px-3 py-2 flex-grow-1 shadow-sm">
            <i class="bi bi-arrow-down-circle me-1"></i> Tarik Saldo
        </a>
        <a href="{{ route('wallet') }}" class="btn btn-outline-light px-3 py-2">
            <i class="bi bi-clock-history me-1"></i> Riwayat
        </a>
    </div>
</div>
