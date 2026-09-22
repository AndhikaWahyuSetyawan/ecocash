<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    protected $fillable = [
        'name',
        'description',
        'minimum_xp',
        'badge_icon',
        'level_order',
        'is_active',
    ];
}
