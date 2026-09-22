<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mission extends Model
{
    protected $fillable = [
        'title',
        'description',
        'type',
        'action_type',
        'target_count',
        'reward_xp',
        'reward_ecopoint',
        'is_active',
    ];

    public function userMissions(): HasMany
    {
        return $this->hasMany(UserMission::class);
    }
}
