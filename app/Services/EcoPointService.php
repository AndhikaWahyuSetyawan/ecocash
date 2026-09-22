<?php

namespace App\Services;

use App\Models\Deposit;
use App\Models\EcoPointLedger;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class EcoPointService
{
    public function pointsForValue(int $value): int
    {
        return (int) floor($value / (int) config('ecocash.points_per_rupiah', 1000));
    }

    public function credit(Deposit $deposit, User $user, int $amount): EcoPointLedger
    {
        return DB::transaction(function () use ($deposit, $user, $amount) {
            $balance = (int) EcoPointLedger::where('user_id', $user->id)->sum('amount');
            return EcoPointLedger::create([
                'user_id' => $user->id,
                'deposit_id' => $deposit->id,
                'type' => 'credit',
                'amount' => $amount,
                'balance_after' => $balance + $amount,
                'description' => 'ECOPOINT setoran #' . $deposit->id,
            ]);
        });
    }

    public function creditWithoutDeposit(User $user, int $amount, string $description): EcoPointLedger
    {
        return DB::transaction(function () use ($user, $amount, $description) {
            $balance = (int) EcoPointLedger::where('user_id', $user->id)->sum('amount');
            return EcoPointLedger::create([
                'user_id' => $user->id,
                'deposit_id' => null,
                'type' => 'credit',
                'amount' => $amount,
                'balance_after' => $balance + $amount,
                'description' => $description,
            ]);
        });
    }
}
