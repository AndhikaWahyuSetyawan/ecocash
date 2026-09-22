<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WithdrawalMethod extends Model
{
    protected $fillable = [
        'name',
        'type',
        'code',
        'min_amount',
        'fee',
        'is_active',
    ];
}
