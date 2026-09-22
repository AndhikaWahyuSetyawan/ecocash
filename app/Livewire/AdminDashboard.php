<?php

namespace App\Livewire;

use App\Models\Deposit;
use App\Models\DepositItem;
use App\Models\EcoPointLedger;
use App\Models\User;
use App\Models\WasteCategory;
use Livewire\Component;

class AdminDashboard extends Component
{
    public function render(): \Illuminate\Contracts\View\View
    {
        $stats = [
            'total_users'            => User::where('role', 'user')->count(),
            'total_deposits'         => Deposit::count(),
            'pending_deposits'       => Deposit::where('status', 'pending_verification')->count(),
            'total_kg'               => DepositItem::whereHas('deposit', fn ($q) => $q->where('status', 'verified'))->sum('verified_weight'),
            'total_ecopoints_issued' => EcoPointLedger::where('type', 'credit')->sum('amount'),
        ];

        $recentDeposits = Deposit::with(['user', 'partner', 'items'])->latest()->take(10)->get();

        $categoryStats = WasteCategory::select('waste_categories.*')
            ->selectSub(
                DepositItem::selectRaw('count(*)')->whereColumn('waste_category_id', 'waste_categories.id'),
                'total_items'
            )
            ->selectSub(
                DepositItem::selectRaw('COALESCE(SUM(declared_weight), 0)')->whereColumn('waste_category_id', 'waste_categories.id'),
                'total_weight'
            )
            ->get();

        return view('livewire.admin-dashboard', compact('stats', 'recentDeposits', 'categoryStats'))
            ->layout('layouts.app');
    }
}
