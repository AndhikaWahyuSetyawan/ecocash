<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImpactFactor extends Model
{
    protected $guarded = [];

    protected $casts = [
        'co2e_per_kg' => 'float',
        'is_active'   => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(WasteCategory::class, 'waste_category_id');
    }
}
