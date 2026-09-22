<?php

namespace App\Livewire;

use App\Models\Mission;
use App\Models\UserMission;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class MissionsView extends Component
{
    public string $filter = 'all'; // 'all' | 'daily' | 'weekly' | 'hunting'

    public function setFilter(string $filter): void
    {
        $this->filter = $filter;
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        $userId = Auth::id();
        $query = Mission::where('is_active', true);

        if ($this->filter !== 'all') {
            $query->where('type', $this->filter);
        }

        $missions = $query->get();
        $userMissions = UserMission::where('user_id', $userId)
            ->whereIn('mission_id', $missions->pluck('id'))
            ->get()
            ->keyBy('mission_id');

        $completedCount = UserMission::where('user_id', $userId)->where('is_completed', true)->count();

        return view('livewire.missions-view', [
            'missions'       => $missions,
            'userMissions'   => $userMissions,
            'completedCount' => $completedCount,
        ])->layout('layouts.app');
    }
}
