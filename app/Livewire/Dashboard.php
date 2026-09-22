<?php

namespace App\Livewire;

use App\Models\Deposit;
use App\Models\DepositItem;
use App\Models\EcoPointLedger;
use App\Models\Mission;
use App\Models\UserMission;
use App\Models\WalletAccount;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Dashboard extends Component
{
    public function completeOnboarding(): void
    {
        $user = Auth::user();
        if ($user) {
            $user->update(['has_completed_onboarding' => true]);
        }
    }

    public function getStatsProperty(): array
    {
        $userId = Auth::id();
        $user = Auth::user();

        // 1. Saldo & Dompet
        $wallet = WalletAccount::firstOrCreate(
            ['user_id' => $userId],
            ['balance' => 0, 'pending_balance' => 0]
        );
        $availableBalance = (int) $wallet->balance;
        $pendingBalance = (int) $wallet->pending_balance;

        // 2. ECOPOINT
        $ecopoints = (int) EcoPointLedger::where('user_id', $userId)->sum('amount');

        // 3. Level & XP
        $totalXp = $user->total_xp;
        $currentLevel = $user->current_level;
        $nextLevel = $user->next_level;
        $xpToNextLevel = $nextLevel ? max(0, $nextLevel->minimum_xp - $totalXp) : 0;

        // 4. Sampah & Dampak Lingkungan Riil
        $totalWeight = DepositItem::whereHas(
                'deposit',
                fn ($q) => $q->where('user_id', $userId)->where('status', 'verified')
            )
            ->selectRaw('COALESCE(SUM(verified_weight), SUM(declared_weight)) as total')
            ->value('total') ?? 0;

        $co2Impact = DepositItem::with('category')
            ->whereHas(
                'deposit',
                fn ($q) => $q->where('user_id', $userId)->where('status', 'verified')
            )
            ->get()
            ->sum(
                fn ($item) =>
                    (float) ($item->verified_weight ?? $item->declared_weight ?? 0)
                    * (float) ($item->category?->impact_factor ?? 0)
            );

        $pendingDepositsCount = Deposit::where('user_id', $userId)
            ->where('status', 'pending_verification')
            ->count();

        return compact(
            'availableBalance',
            'pendingBalance',
            'ecopoints',
            'totalXp',
            'currentLevel',
            'nextLevel',
            'xpToNextLevel',
            'totalWeight',
            'co2Impact',
            'pendingDepositsCount'
        );
    }

    public function getActiveMissionsProperty()
    {
        $userId = Auth::id();
        $missions = Mission::where('is_active', true)->take(3)->get();
        $userMissions = UserMission::where('user_id', $userId)
            ->whereIn('mission_id', $missions->pluck('id'))
            ->get()
            ->keyBy('mission_id');

        return $missions->map(function ($mission) use ($userMissions) {
            return [
                'model' => $mission,
                'user_mission' => $userMissions->get($mission->id),
            ];
        });
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        $user = Auth::user();

        return view('livewire.dashboard', [
            'stats'          => $this->stats,
            'activeMissions' => $this->activeMissions,
            'user'           => $user,
            'recentDeposits' => Deposit::with(['partner', 'items.category'])
                ->where('user_id', Auth::id())
                ->orderByDesc('created_at')
                ->take(5)
                ->get(),
        ])->layout('layouts.app');
    }
}
