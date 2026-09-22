<div>
    <x-ui.page-header
        title="Dompet ECOCASH"
        subtitle="Kelola saldo tunai hasil setoran sampah terverifikasi kakak."
    >
        <x-slot:actions>
            <a href="{{ route('wallet.withdraw') }}" class="btn btn-eco px-3 py-2">
                <i class="bi bi-arrow-down-circle me-1"></i> Tarik Saldo
            </a>
        </x-slot:actions>
    </x-ui.page-header>

    {{-- Kartu Dompet Utama --}}
    <x-ui.wallet-card
        :balance="$account->balance"
        :pendingBalance="$account->pending_balance"
        :totalWithdrawn="$totalWithdrawn"
    />

    {{-- Ringkasan Pendapatan & Penarikan --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-6">
            <x-ui.stat-card
                title="Total Penghasilan Setoran"
                value="Rp{{ number_format($totalEarned, 0, ',', '.') }}"
                subtitle="Akumulasi seluruh setoran sampah yang disetujui"
                icon="bi-arrow-down-left-circle"
                color="eco"
            />
        </div>
        <div class="col-12 col-md-6">
            <x-ui.stat-card
                title="Total Dana Dicairkan"
                value="Rp{{ number_format($totalWithdrawn, 0, ',', '.') }}"
                subtitle="Dana yang telah berhasil masuk ke rekening atau e-wallet"
                icon="bi-check2-circle"
                color="ink"
            />
        </div>
    </div>

    {{-- Riwayat Mutasi Saldo --}}
    <div class="eco-card p-4">
        <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
            <h2 class="h6 fw-bold mb-0" style="color: var(--ink);">Riwayat Mutasi Saldo</h2>
            <div class="btn-group btn-group-sm" role="group" aria-label="Filter mutasi">
                <button type="button" class="btn {{ $tab === 'all' ? 'btn-eco' : 'btn-outline-secondary' }}" wire:click="setTab('all')">
                    Semua
                </button>
                <button type="button" class="btn {{ $tab === 'deposits' ? 'btn-eco' : 'btn-outline-secondary' }}" wire:click="setTab('deposits')">
                    Pemasukan
                </button>
                <button type="button" class="btn {{ $tab === 'withdrawals' ? 'btn-eco' : 'btn-outline-secondary' }}" wire:click="setTab('withdrawals')">
                    Penarikan
                </button>
            </div>
        </div>

        @if($transactions->isEmpty())
            <x-ui.empty-state
                title="Belum ada mutasi saldo"
                description="Mutasi saldo akan muncul setelah setoran sampah kakak disetujui oleh mitra bank sampah."
                actionLabel="Scan Sampah Sekarang"
                actionUrl="{{ route('scanner') }}"
            />
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.875rem;">
                    <thead class="table-light">
                        <tr style="font-size: 0.78rem; color: var(--ink-muted);">
                            <th class="ps-3 py-3">TANGGAL & TRANSAKSI</th>
                            <th>TIPE</th>
                            <th>NOMINAL</th>
                            <th class="text-end pe-3">SALDO AKHIR</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $tx)
                            @php
                                $isCredit = $tx->amount > 0;
                            @endphp
                            <tr>
                                <td class="ps-3 py-3">
                                    <div class="fw-semibold" style="color: var(--ink);">{{ $tx->description }}</div>
                                    <div class="small text-muted">{{ $tx->created_at->translatedFormat('d M Y, H:i') }}</div>
                                </td>
                                <td>
                                    @if($tx->type === 'credit_deposit')
                                        <span class="badge bg-success-subtle text-success px-2 py-1 rounded-2">Pemasukan</span>
                                    @elseif($tx->type === 'debit_withdrawal')
                                        <span class="badge bg-danger-subtle text-danger px-2 py-1 rounded-2">Penarikan</span>
                                    @elseif($tx->type === 'refund')
                                        <span class="badge bg-warning-subtle text-warning-emphasis px-2 py-1 rounded-2">Pengembalian</span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary px-2 py-1 rounded-2">{{ $tx->type }}</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="fw-bold {{ $isCredit ? 'text-success' : 'text-danger' }}">
                                        {{ $isCredit ? '+' : '' }}Rp{{ number_format(abs($tx->amount), 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="text-end pe-3">
                                    <span class="fw-semibold text-muted">
                                        Rp{{ number_format($tx->balance_after, 0, ',', '.') }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
</div>
