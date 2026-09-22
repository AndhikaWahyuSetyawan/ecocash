<div>
    <x-ui.page-header
        title="Kelola Penarikan Saldo"
        subtitle="Panel admin untuk memverifikasi dan memproses transfer pencairan dana pengguna."
    />

    @if(session('status'))
        <div class="alert alert-success alert-dismissible fade show mb-4 py-2" role="alert">
            <i class="bi bi-check-circle me-1"></i> {{ session('status') }}
            <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    {{-- Filter Status Penarikan --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div class="btn-group btn-group-sm" role="group">
            <button type="button" class="btn {{ $statusFilter === 'pending' ? 'btn-eco' : 'btn-outline-secondary' }}" wire:click="setStatusFilter('pending')">
                Menunggu ({{ $counts['pending'] }})
            </button>
            <button type="button" class="btn {{ $statusFilter === 'paid' ? 'btn-eco' : 'btn-outline-secondary' }}" wire:click="setStatusFilter('paid')">
                Telah Dibayar ({{ $counts['paid'] }})
            </button>
            <button type="button" class="btn {{ $statusFilter === 'rejected' ? 'btn-eco' : 'btn-outline-secondary' }}" wire:click="setStatusFilter('rejected')">
                Ditolak ({{ $counts['rejected'] }})
            </button>
            <button type="button" class="btn {{ $statusFilter === '' ? 'btn-eco' : 'btn-outline-secondary' }}" wire:click="setStatusFilter('')">
                Semua
            </button>
        </div>
    </div>

    {{-- Tabel Penarikan --}}
    <div class="eco-card p-4">
        @if($withdrawals->isEmpty())
            <x-ui.empty-state
                title="Tidak ada permohonan penarikan"
                description="Tidak ditemukan permohonan penarikan saldo dengan status ini."
                icon="bi-cash"
            />
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.875rem;">
                    <thead class="table-light">
                        <tr style="font-size: 0.78rem; color: var(--ink-muted);">
                            <th class="ps-3 py-3">ID & TANGGAL</th>
                            <th>PENGGUNA</th>
                            <th>METODE & TUJUAN</th>
                            <th>NOMINAL BERSIH</th>
                            <th>STATUS</th>
                            <th class="text-end pe-3">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($withdrawals as $w)
                            <tr>
                                <td class="ps-3 py-3">
                                    <div class="fw-bold" style="color: var(--ink);">#{{ $w->id }}</div>
                                    <div class="small text-muted">{{ $w->created_at->translatedFormat('d M Y, H:i') }}</div>
                                </td>
                                <td>
                                    <div class="fw-semibold" style="color: var(--ink);">{{ $w->user?->name ?? 'User' }}</div>
                                    <div class="small text-muted">{{ $w->user?->email }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border me-1">{{ $w->method?->name }}</span>
                                    <div class="small mt-1">
                                        <strong>{{ $w->account_number }}</strong> (a.n. {{ $w->account_name }})
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-bold text-success fs-6">
                                        Rp{{ number_format($w->net_amount, 0, ',', '.') }}
                                    </div>
                                    @if($w->fee > 0)
                                        <div class="small text-muted" style="font-size: 0.72rem;">Biaya: Rp{{ number_format($w->fee) }}</div>
                                    @endif
                                </td>
                                <td>
                                    <x-ui.status-badge :status="$w->status" />
                                    @if($w->status === 'rejected' && $w->rejection_reason)
                                        <div class="small text-danger mt-1" style="font-size: 0.75rem;">
                                            Alasan: {{ $w->rejection_reason }}
                                        </div>
                                    @endif
                                </td>
                                <td class="text-end pe-3">
                                    @if($w->status === 'pending')
                                        <div class="btn-group btn-group-sm">
                                            <button type="button" wire:click="markAsPaid({{ $w->id }})" class="btn btn-sm btn-eco" title="Tandai telah ditransfer">
                                                <i class="bi bi-check-lg me-1"></i> Cairkan
                                            </button>
                                            <button type="button" wire:click="openRejectModal({{ $w->id }})" class="btn btn-sm btn-outline-danger" title="Tolak penarikan">
                                                Tolak
                                            </button>
                                        </div>
                                    @else
                                        <span class="text-muted small">Selesai</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $withdrawals->links() }}
            </div>
        @endif
    </div>

    {{-- Modal Tolak Penarikan --}}
    @if($selectedWithdrawalId)
        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="border-radius: 12px;">
                    <div class="modal-header">
                        <h2 class="modal-title h6 fw-bold mb-0">Tolak Permohonan Penarikan #{{ $selectedWithdrawalId }}</h2>
                        <button type="button" class="btn-close" wire:click="$set('selectedWithdrawalId', null)"></button>
                    </div>
                    <form wire:submit.prevent="confirmReject">
                        <div class="modal-body">
                            <p class="small text-muted mb-3">
                                Saldo akan dikembalikan secara penuh ke dompet pengguna. Masukkan alasan penolakan agar pengguna memahami kendala transfer.
                            </p>
                            <label for="rejectionReason" class="form-label small fw-semibold">Alasan Penolakan <span class="text-danger">*</span></label>
                            <textarea id="rejectionReason" class="form-control @error('rejectionReason') is-invalid @enderror"
                                      wire:model="rejectionReason" rows="3"
                                      placeholder="Contoh: Nomor rekening tidak valid atau nama pemilik tidak cocok."></textarea>
                            @error('rejectionReason')
                                <div class="invalid-feedback small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-sm btn-secondary" wire:click="$set('selectedWithdrawalId', null)">Batal</button>
                            <button type="submit" class="btn btn-sm btn-danger">Konfirmasi Tolak & Refund</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
