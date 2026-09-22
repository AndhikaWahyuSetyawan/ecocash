<?php

namespace App\Services;

use App\Models\Achievement;
use App\Models\InAppNotification;
use App\Models\Level;
use App\Models\Mission;
use App\Models\User;
use App\Models\UserAchievement;
use App\Models\UserMission;
use App\Models\XpLedger;
use Illuminate\Support\Facades\DB;

class GamificationService
{
    /**
     * Berikan XP kepada pengguna dan catat ke buku besar ledger XP.
     */
    public function awardXp(User $user, int $amount, string $sourceType, ?int $sourceId = null, string $description = 'Perolehan XP'): XpLedger
    {
        return DB::transaction(function () use ($user, $amount, $sourceType, $sourceId, $description) {
            $prevTotal = (int) $user->xpLedgers()->sum('amount');
            $newTotal = $prevTotal + $amount;

            $ledger = XpLedger::create([
                'user_id' => $user->id,
                'amount' => $amount,
                'balance_after' => $newTotal,
                'source_type' => $sourceType,
                'source_id' => $sourceId,
                'description' => $description,
            ]);

            // Cek kenaikan level
            $oldLevel = Level::where('minimum_xp', '<=', $prevTotal)->orderByDesc('minimum_xp')->first();
            $newLevel = Level::where('minimum_xp', '<=', $newTotal)->orderByDesc('minimum_xp')->first();

            if ($newLevel && (!$oldLevel || $newLevel->id !== $oldLevel->id)) {
                InAppNotification::create([
                    'user_id' => $user->id,
                    'title' => 'Selamat, Naik Level!',
                    'message' => 'Kakak telah mencapai ' . $newLevel->name . '! Terus pilah dan setor sampah untuk lingkungan yang lebih baik.',
                    'type' => 'level_up',
                    'action_url' => '/reward',
                ]);
            }

            return $ledger;
        });
    }

    /**
     * Perbarui progres misi pengguna berdasarkan aksi tertentu.
     */
    public function recordActionProgress(User $user, string $actionType, int $increment = 1): void
    {
        $missions = Mission::where('action_type', $actionType)
            ->where('is_active', true)
            ->get();

        foreach ($missions as $mission) {
            $userMission = UserMission::firstOrCreate(
                ['user_id' => $user->id, 'mission_id' => $mission->id],
                ['current_progress' => 0, 'is_completed' => false]
            );

            if ($userMission->is_completed) {
                continue;
            }

            $userMission->current_progress += $increment;

            if ($userMission->current_progress >= $mission->target_count) {
                $userMission->is_completed = true;
                $userMission->completed_at = now();
                $userMission->save();

                // Berikan hadiah misi
                if ($mission->reward_xp > 0) {
                    $this->awardXp($user, $mission->reward_xp, 'mission_completed', $mission->id, 'Hadiah misi: ' . $mission->title);
                }

                if ($mission->reward_ecopoint > 0) {
                    app(EcoPointService::class)->creditWithoutDeposit($user, $mission->reward_ecopoint, 'Hadiah misi: ' . $mission->title);
                }

                InAppNotification::create([
                    'user_id' => $user->id,
                    'title' => 'Misi Selesai!',
                    'message' => 'Kakak telah menyelesaikan misi "' . $mission->title . '" dan mendapatkan ' . $mission->reward_xp . ' XP.',
                    'type' => 'mission_completed',
                    'action_url' => '/missions',
                ]);
            } else {
                $userMission->save();
            }
        }

        // Periksa pencapaian / achievements
        $this->checkAchievements($user);
    }

    /**
     * Periksa dan buka achievement baru jika syarat terpenuhi.
     */
    public function checkAchievements(User $user): void
    {
        $verifiedDepositsCount = $user->deposits()->where('status', 'verified')->count();
        $totalWeightKg = (float) $user->deposits()
            ->where('status', 'verified')
            ->join('deposit_items', 'deposits.id', '=', 'deposit_items.deposit_id')
            ->sum(DB::raw('COALESCE(deposit_items.verified_weight, deposit_items.declared_weight)'));

        $achievements = Achievement::where('is_active', true)->get();

        foreach ($achievements as $ach) {
            $hasUnlocked = UserAchievement::where('user_id', $user->id)
                ->where('achievement_id', $ach->id)
                ->exists();

            if ($hasUnlocked) continue;

            $qualifies = false;
            if ($ach->criterion_type === 'first_deposit' && $verifiedDepositsCount >= 1) {
                $qualifies = true;
            } elseif ($ach->criterion_type === 'total_deposits' && $verifiedDepositsCount >= $ach->criterion_value) {
                $qualifies = true;
            } elseif ($ach->criterion_type === 'total_weight' && $totalWeightKg >= $ach->criterion_value) {
                $qualifies = true;
            }

            if ($qualifies) {
                UserAchievement::create([
                    'user_id' => $user->id,
                    'achievement_id' => $ach->id,
                    'unlocked_at' => now(),
                ]);

                if ($ach->reward_xp > 0) {
                    $this->awardXp($user, $ach->reward_xp, 'achievement_unlocked', $ach->id, 'Lencana dibuka: ' . $ach->title);
                }

                InAppNotification::create([
                    'user_id' => $user->id,
                    'title' => 'Lencana Baru Terbuka!',
                    'message' => 'Selamat, kakak mendapatkan lencana "' . $ach->title . '"!',
                    'type' => 'achievement_unlocked',
                    'action_url' => '/reward',
                ]);
            }
        }
    }
}
