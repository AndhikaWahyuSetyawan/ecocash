<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class WastePrice extends Model { protected $guarded = []; protected $casts = ['effective_from'=>'date','effective_until'=>'date','is_active'=>'boolean']; public function category() { return $this->belongsTo(WasteCategory::class,'waste_category_id'); } }
