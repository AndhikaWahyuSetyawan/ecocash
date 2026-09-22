<?php

namespace App\Livewire;

use App\Models\Deposit;
use App\Models\DepositItem;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class PartnerDashboard extends Component
{
    use WithPagination;

    public string $tab = 'pending';

    public function setTab(string $tab): void
    {
        $this->tab = $tab;
        $this->resetPage();
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        $query = Deposit::with(['user', 'items.category', 'items.detection'])
            ->when($this->tab === 'pending', fn ($q) => $q->where('status', 'pending_verification'))
            ->when($this->tab === 'today',   fn ($q) => $q->where('status', 'verified')->whereDate('verified_at', today()))
            ->when($this->tab === 'all',     fn ($q) => $q) // no extra filter
            ->orderByDesc('created_at');

        $stats = [
            'pending'     => Deposit::where('status', 'pending_verification')->count(),
            'today_count' => Deposit::where('status', 'verified')->whereDate('verified_at', today())->count(),
            'today_kg'    => (float) DepositItem::whereHas(
                'deposit',
                fn ($q) => $q->where('status', 'verified')->whereDate('verified_at', today())
            )->sum('verified_weight'),
        ];

        return view('livewire.partner-dashboard', [
            'deposits' => $query->paginate(15),
            'stats'    => $stats,
        ])->layout('layouts.app');
    }
}
