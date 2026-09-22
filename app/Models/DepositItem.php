<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class DepositItem extends Model { protected $guarded = []; protected $casts = ['declared_weight'=>'float','verified_weight'=>'float']; public function deposit() { return $this->belongsTo(Deposit::class); } public function category() { return $this->belongsTo(WasteCategory::class,'waste_category_id'); } public function detection() { return $this->belongsTo(AiDetection::class,'ai_detection_id'); } }
