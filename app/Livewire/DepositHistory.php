<?php

namespace App\Livewire;

use App\Models\Deposit;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class DepositHistory extends Component
{
    use WithPagination;

    public string $statusFilter = '';

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        $query = Deposit::with(['partner', 'items'])
            ->where('user_id', Auth::id())
            ->latest('submitted_at');

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        return view('livewire.deposit-history', [
            'deposits' => $query->paginate(10),
        ])->layout('layouts.app');
    }
}
