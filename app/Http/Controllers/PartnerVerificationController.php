<?php

namespace App\Http\Controllers;

use App\Models\Deposit;
use App\Models\InAppNotification;
use App\Services\EcoPointService;
use App\Services\GamificationService;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PartnerVerificationController extends Controller
{
    public function update(
        Request $request,
        Deposit $deposit,
        EcoPointService $points,
        WalletService $walletService,
        GamificationService $gamificationService
    ): \Illuminate\Http\RedirectResponse {
        abort_unless($request->user()->isRole('bank_partner'), 403);

        // Mencegah verifikasi ulang untuk setoran yang sudah final
        abort_if(in_array($deposit->status, ['verified', 'rejected']), 422);

        $data = $request->validate([
            'status'            => 'required|in:verified,rejected',
            'rejection_reason'  => 'required_if:status,rejected|nullable|string|max:1000',
            'verified_weight'   => 'nullable|numeric|min:0.001',
            'verified_category' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($deposit, $data, $request, $points, $walletService, $gamificationService) {
            $deposit->update([
                'status'           => $data['status'],
                'verified_at'      => now(),
                'verified_by'      => $request->user()->id,
                'rejection_reason' => $data['rejection_reason'] ?? null,
            ]);

            if ($data['status'] === 'verified') {
                $actualWeight = isset($data['verified_weight'])
                    ? (float) $data['verified_weight']
                    : null;

                foreach ($deposit->items as $item) {
                    $itemWeight = $actualWeight ?? (float) $item->declared_weight;
                    $finalVal = (int) round($itemWeight * (int) $item->price_per_kg);
                    $finalPts = $points->pointsForValue($finalVal);

                    $item->update([
                        'verified_weight'   => $itemWeight,
                        'verified_category' => $data['verified_category'] ?? $item->ai_category,
                        'final_value'       => $finalVal,
                        'final_ecopoint'    => $finalPts,
                    ]);
                }

                $totalFinalValue = (int) $deposit->items()->sum('final_value');
                $totalPoints = (int) $deposit->items()->sum('final_ecopoint');

                // 1. Saldo Tunai Riil dikreditkan ke dompet
                if ($totalFinalValue > 0) {
                    $walletService->creditVerifiedDeposit(
                        $deposit->user,
                        $totalFinalValue,
                        $deposit,
                        'Hasil setoran sampah terverifikasi #' . $deposit->id
                    );
                }

                // 2. ECOPOINT dikreditkan ke buku besar poin
                if ($totalPoints > 0) {
                    $points->credit($deposit, $deposit->user, $totalPoints);
                }

                // 3. Berikan XP kepada pengguna (misal 50 XP per kg sampah terverifikasi)
                $totalWeight = (float) $deposit->items()->sum('verified_weight');
                $xpEarned = max(20, (int) round($totalWeight * 50));
                $gamificationService->awardXp(
                    $deposit->user,
                    $xpEarned,
                    'deposit_verified',
                    $deposit->id,
                    'Setoran sampah #' . $deposit->id . ' terverifikasi (' . number_format($totalWeight, 2) . ' kg)'
                );

                // 4. Catat progres misi setoran
                $gamificationService->recordActionProgress($deposit->user, 'deposit_count', 1);

                // 5. Kirim In-App Notification
                InAppNotification::create([
                    'user_id' => $deposit->user_id,
                    'title' => 'Setoran Terverifikasi!',
                    'message' => 'Setoran #' . $deposit->id . ' berhasil diverifikasi mitra. Saldo bertambah Rp' . number_format($totalFinalValue, 0, ',', '.') . ', +' . $totalPoints . ' ECOPOINT, +' . $xpEarned . ' XP.',
                    'type' => 'deposit_verified',
                    'action_url' => '/setoran/' . $deposit->id,
                ]);

                Log::info('Deposit verified successfully', [
                    'deposit_id'  => $deposit->id,
                    'verified_by' => $request->user()->id,
                    'value'       => $totalFinalValue,
                    'points'      => $totalPoints,
                    'xp'          => $xpEarned,
                ]);
            } else {
                // Setoran ditolak
                // Sesuaikan saldo tertunda di akun dompet jika ada
                $account = $deposit->user->walletAccount;
                if ($account && $account->pending_balance > 0) {
                    $estValue = (int) $deposit->items()->sum('estimated_value');
                    $account->decrement('pending_balance', min($account->pending_balance, $estValue));
                }

                InAppNotification::create([
                    'user_id' => $deposit->user_id,
                    'title' => 'Setoran Ditolak',
                    'message' => 'Setoran #' . $deposit->id . ' belum dapat disetujui. Alasan: ' . ($data['rejection_reason'] ?? 'Tidak memenuhi kriteria.'),
                    'type' => 'deposit_rejected',
                    'action_url' => '/setoran/' . $deposit->id,
                ]);

                Log::info('Deposit rejected', [
                    'deposit_id'  => $deposit->id,
                    'verified_by' => $request->user()->id,
                    'reason'      => $data['rejection_reason'] ?? null,
                ]);
            }
        });

        return redirect()
            ->route('partner.dashboard')
            ->with('status', 'Verifikasi setoran #' . $deposit->id . ' berhasil diproses.');
    }
}
