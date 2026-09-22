<?php

namespace App\Services;

use App\Models\User;
use App\Models\WalletAccount;
use App\Models\WalletTransaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class WalletService
{
    /**
     * Dapatkan atau buat akun dompet pengguna secara atomik.
     */
    public function getOrCreateAccount(User $user): WalletAccount
    {
        return WalletAccount::firstOrCreate(
            ['user_id' => $user->id],
            ['balance' => 0, 'pending_balance' => 0]
        );
    }

    /**
     * Tambah estimasi saldo tertunda saat setoran diajukan.
     */
    public function recordPendingDeposit(User $user, int $amount): void
    {
        if ($amount <= 0) return;

        DB::transaction(function () use ($user, $amount) {
            $account = WalletAccount::where('user_id', $user->id)->lockForUpdate()->first();
            if (!$account) {
                $account = WalletAccount::create([
                    'user_id' => $user->id,
                    'balance' => 0,
                    'pending_balance' => 0,
                ]);
            }

            $account->increment('pending_balance', $amount);
        });
    }

    /**
     * Kredit saldo riil setelah setoran diverifikasi mitra.
     */
    public function creditVerifiedDeposit(User $user, int $amount, ?Model $reference = null, string $description = 'Pencairan setoran sampah terverifikasi'): WalletTransaction
    {
        if ($amount <= 0) {
            throw new InvalidArgumentException('Nominal kredit harus lebih besar dari 0.');
        }

        return DB::transaction(function () use ($user, $amount, $reference, $description) {
            $account = WalletAccount::where('user_id', $user->id)->lockForUpdate()->first();
            if (!$account) {
                $account = WalletAccount::create([
                    'user_id' => $user->id,
                    'balance' => 0,
                    'pending_balance' => 0,
                ]);
            }

            // Kurangi saldo tertunda (jika ada) hingga batas nominal atau nol
            $pendingReduction = min($account->pending_balance, $amount);
            $account->decrement('pending_balance', $pendingReduction);

            // Tambahkan ke saldo tersedia riil
            $account->increment('balance', $amount);
            $account->refresh();

            return WalletTransaction::create([
                'user_id' => $user->id,
                'type' => 'credit_deposit',
                'amount' => $amount,
                'reference_type' => $reference ? get_class($reference) : null,
                'reference_id' => $reference?->getKey(),
                'description' => $description,
                'balance_after' => $account->balance,
                'status' => 'completed',
            ]);
        });
    }

    /**
     * Potong saldo untuk penarikan dana.
     */
    public function debitForWithdrawal(User $user, int $amount, Model $withdrawalRequest, string $description = 'Penarikan saldo'): WalletTransaction
    {
        if ($amount <= 0) {
            throw new InvalidArgumentException('Nominal penarikan harus lebih dari 0.');
        }

        return DB::transaction(function () use ($user, $amount, $withdrawalRequest, $description) {
            $account = WalletAccount::where('user_id', $user->id)->lockForUpdate()->first();
            if (!$account || $account->balance < $amount) {
                throw new InvalidArgumentException('Saldo tidak mencukupi untuk melakukan penarikan.');
            }

            $account->decrement('balance', $amount);
            $account->refresh();

            return WalletTransaction::create([
                'user_id' => $user->id,
                'type' => 'debit_withdrawal',
                'amount' => -$amount,
                'reference_type' => get_class($withdrawalRequest),
                'reference_id' => $withdrawalRequest->getKey(),
                'description' => $description,
                'balance_after' => $account->balance,
                'status' => 'completed',
            ]);
        });
    }

    /**
     * Refund / pengembalian saldo jika penarikan ditolak admin.
     */
    public function refundWithdrawal(User $user, int $amount, Model $withdrawalRequest, string $reason = 'Penarikan ditolak'): WalletTransaction
    {
        return DB::transaction(function () use ($user, $amount, $withdrawalRequest, $reason) {
            $account = WalletAccount::where('user_id', $user->id)->lockForUpdate()->first();
            if (!$account) {
                $account = WalletAccount::create([
                    'user_id' => $user->id,
                    'balance' => 0,
                    'pending_balance' => 0,
                ]);
            }

            $account->increment('balance', $amount);
            $account->refresh();

            return WalletTransaction::create([
                'user_id' => $user->id,
                'type' => 'refund',
                'amount' => $amount,
                'reference_type' => get_class($withdrawalRequest),
                'reference_id' => $withdrawalRequest->getKey(),
                'description' => 'Pengembalian saldo: ' . $reason,
                'balance_after' => $account->balance,
                'status' => 'completed',
            ]);
        });
    }
}
