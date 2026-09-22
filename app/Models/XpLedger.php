<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class XpLedger extends Model
{
    protected $fillable = [
        'user_id',
        'amount',
        'balance_after',
        'source_type',
        'source_id',
        'description',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
