<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class AiScan extends Model { protected $guarded = []; public function detections() { return $this->hasMany(AiDetection::class); } }
