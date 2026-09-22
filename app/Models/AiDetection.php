<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class AiDetection extends Model { protected $guarded = []; public function aiScan() { return $this->belongsTo(AiScan::class); } protected $casts = ['confidence'=>'float','corrected_at'=>'datetime']; public function category() { return $this->belongsTo(WasteCategory::class,'corrected_class_id'); } }
