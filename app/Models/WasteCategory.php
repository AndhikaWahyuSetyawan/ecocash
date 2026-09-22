<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class WasteCategory extends Model { use SoftDeletes; protected $guarded = []; public function prices() { return $this->hasMany(WastePrice::class); } public function depositItems() { return $this->hasMany(DepositItem::class); } }
