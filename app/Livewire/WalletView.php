<?php

namespace App\Livewire;

use App\Models\WalletAccount;
use App\Models\WalletTransaction;
use App\Models\WithdrawalRequest;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class WalletView extends Component
{
    use WithPagination;

    public string $tab = 'all'; // 'all' | 'deposits' | 'withdrawals'

    public function setTab(string $tab): void
    {
        $this->tab = $tab;
        $this->resetPage();
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        $userId = Auth::id();
        $account = WalletAccount::firstOrCreate(
            ['user_id' => $userId],
            ['balance' => 0, 'pending_balance' => 0]
        );

        $totalWithdrawn = (int) WithdrawalRequest::where('user_id', $userId)
            ->where('status', 'paid')
            ->sum('amount');

        $totalEarned = (int) WalletTransaction::where('user_id', $userId)
            ->where('type', 'credit_deposit')
            ->sum('amount');

        $query = WalletTransaction::where('user_id', $userId)->orderByDesc('created_at');

        if ($this->tab === 'deposits') {
            $query->where('type', 'credit_deposit');
        } elseif ($this->tab === 'withdrawals') {
            $query->whereIn('type', ['debit_withdrawal', 'refund']);
        }

        $transactions = $query->paginate(10);

        return view('livewire.wallet-view', [
            'account'        => $account,
            'totalWithdrawn' => $totalWithdrawn,
            'totalEarned'    => $totalEarned,
            'transactions'   => $transactions,
        ])->layout('layouts.app');
    }
}
