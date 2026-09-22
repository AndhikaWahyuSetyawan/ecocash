<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'has_completed_onboarding',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    public function isRole(string $role): bool
    {
        return $this->role === $role;
    }

    public function deposits() { return $this->hasMany(Deposit::class); }
    public function ledgers() { return $this->hasMany(EcoPointLedger::class); }
    public function walletAccount() { return $this->hasOne(WalletAccount::class); }
    public function walletTransactions() { return $this->hasMany(WalletTransaction::class); }
    public function withdrawalRequests() { return $this->hasMany(WithdrawalRequest::class); }
    public function xpLedgers() { return $this->hasMany(XpLedger::class); }
    public function userMissions() { return $this->hasMany(UserMission::class); }
    public function userAchievements() { return $this->hasMany(UserAchievement::class); }
    public function inAppNotifications() { return $this->hasMany(InAppNotification::class); }

    public function getAvailableBalanceAttribute(): int
    {
        return (int) ($this->walletAccount?->balance ?? 0);
    }

    public function getPendingBalanceAttribute(): int
    {
        return (int) ($this->walletAccount?->pending_balance ?? 0);
    }

    public function getTotalXpAttribute(): int
    {
        return (int) $this->xpLedgers()->sum('amount');
    }

    public function getCurrentLevelAttribute(): ?Level
    {
        $xp = $this->total_xp;
        return Level::where('minimum_xp', '<=', $xp)
            ->where('is_active', true)
            ->orderByDesc('minimum_xp')
            ->first() ?? Level::orderBy('minimum_xp')->first();
    }

    public function getNextLevelAttribute(): ?Level
    {
        $xp = $this->total_xp;
        return Level::where('minimum_xp', '>', $xp)
            ->where('is_active', true)
            ->orderBy('minimum_xp')
            ->first();
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'has_completed_onboarding' => 'boolean',
            'last_activity_date' => 'date',
        ];
    }
}
