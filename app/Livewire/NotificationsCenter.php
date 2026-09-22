<?php

namespace App\Livewire;

use App\Models\InAppNotification;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NotificationsCenter extends Component
{
    public function markAsRead(int $id): void
    {
        $notif = InAppNotification::where('user_id', Auth::id())->find($id);
        if ($notif) {
            $notif->update(['is_read' => true]);
        }
    }

    public function markAllAsRead(): void
    {
        InAppNotification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        $notifications = InAppNotification::where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('livewire.notifications-center', [
            'notifications' => $notifications,
        ])->layout('layouts.app');
    }
}
