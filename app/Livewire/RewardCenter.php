<?php

namespace App\Livewire;

use App\Models\Achievement;
use App\Models\EcoPointLedger;
use App\Models\Level;
use App\Models\UserAchievement;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class RewardCenter extends Component
{
    public function render(): \Illuminate\Contracts\View\View
    {
        $user = Auth::user();
        $totalPoints = (int) EcoPointLedger::where('user_id', $user->id)->sum('amount');
        $totalXp = $user->total_xp;
        $currentLevel = $user->current_level;
        $nextLevel = $user->next_level;
        $allLevels = Level::where('is_active', true)->orderBy('minimum_xp')->get();

        $achievements = Achievement::where('is_active', true)->get();
        $unlockedIds = UserAchievement::where('user_id', $user->id)->pluck('achievement_id')->toArray();

        $recentLedgers = EcoPointLedger::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        return view('livewire.reward-center', [
            'user'          => $user,
            'totalPoints'   => $totalPoints,
            'totalXp'       => $totalXp,
            'currentLevel'  => $currentLevel,
            'nextLevel'     => $nextLevel,
            'allLevels'     => $allLevels,
            'achievements'  => $achievements,
            'unlockedIds'   => $unlockedIds,
            'recentLedgers' => $recentLedgers,
        ])->layout('layouts.app');
    }
}
