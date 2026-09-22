<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WalletAccount extends Model
{
    protected $fillable = [
        'user_id',
        'balance',
        'pending_balance',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
