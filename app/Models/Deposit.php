<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Deposit extends Model { protected $guarded = []; protected $casts = ['submitted_at'=>'datetime','verified_at'=>'datetime']; public function items() { return $this->hasMany(DepositItem::class); } public function partner() { return $this->belongsTo(Partner::class); } public function user() { return $this->belongsTo(User::class); } }
