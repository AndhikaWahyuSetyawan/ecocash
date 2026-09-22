<?php

namespace App\Livewire;

use App\Models\InAppNotification;
use App\Models\WithdrawalRequest;
use App\Services\WalletService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class AdminWithdrawals extends Component
{
    use WithPagination;

    public string $statusFilter = 'pending';
    public ?string $rejectionReason = null;
    public ?int $selectedWithdrawalId = null;

    public function mount(): void
    {
        abort_unless(Auth::user()->isRole('admin'), 403);
    }

    public function setStatusFilter(string $status): void
    {
        $this->statusFilter = $status;
        $this->resetPage();
    }

    public function markAsPaid(int $id): void
    {
        $withdrawal = WithdrawalRequest::findOrFail($id);
        abort_if($withdrawal->status !== 'pending', 422);

        $withdrawal->update([
            'status'       => 'paid',
            'processed_at' => now(),
            'processed_by' => Auth::id(),
        ]);

        InAppNotification::create([
            'user_id'    => $withdrawal->user_id,
            'title'      => 'Penarikan Saldo Berhasil Dicairkan',
            'message'    => 'Dana sebesar Rp' . number_format($withdrawal->amount, 0, ',', '.') . ' telah berhasil ditransfer ke ' . $withdrawal->method?->name . ' (' . $withdrawal->account_number . ').',
            'type'       => 'withdrawal_status',
            'action_url' => '/wallet',
        ]);

        session()->flash('status', 'Penarikan #' . $withdrawal->id . ' ditandai telah dibayar.');
    }

    public function openRejectModal(int $id): void
    {
        $this->selectedWithdrawalId = $id;
        $this->rejectionReason = '';
    }

    public function confirmReject(WalletService $walletService): void
    {
        $this->validate([
            'rejectionReason' => 'required|string|max:500',
        ], [
            'rejectionReason.required' => 'Mohon sertakan alasan penolakan pencairan.',
        ]);

        $withdrawal = WithdrawalRequest::findOrFail($this->selectedWithdrawalId);
        abort_if($withdrawal->status !== 'pending', 422);

        $withdrawal->update([
            'status'           => 'rejected',
            'rejection_reason' => $this->rejectionReason,
            'processed_at'     => now(),
            'processed_by'     => Auth::id(),
        ]);

        // Kembalikan saldo pengguna secara aman via WalletService
        $walletService->refundWithdrawal(
            $withdrawal->user,
            $withdrawal->amount,
            $withdrawal,
            $this->rejectionReason
        );

        InAppNotification::create([
            'user_id'    => $withdrawal->user_id,
            'title'      => 'Permohonan Penarikan Ditolak',
            'message'    => 'Penarikan Rp' . number_format($withdrawal->amount, 0, ',', '.') . ' ditolak. Alasan: ' . $this->rejectionReason . '. Saldo telah dikembalikan ke dompet.',
            'type'       => 'withdrawal_status',
            'action_url' => '/wallet',
        ]);

        $this->selectedWithdrawalId = null;
        $this->rejectionReason = null;
        session()->flash('status', 'Penarikan #' . $withdrawal->id . ' ditolak dan saldo telah dikembalikan ke pengguna.');
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        $query = WithdrawalRequest::with(['user', 'method'])->orderByDesc('created_at');

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        $withdrawals = $query->paginate(12);

        $counts = [
            'pending' => WithdrawalRequest::where('status', 'pending')->count(),
            'paid'    => WithdrawalRequest::where('status', 'paid')->count(),
            'rejected'=> WithdrawalRequest::where('status', 'rejected')->count(),
        ];

        return view('livewire.admin-withdrawals', [
            'withdrawals' => $withdrawals,
            'counts'      => $counts,
        ])->layout('layouts.app');
    }
}
