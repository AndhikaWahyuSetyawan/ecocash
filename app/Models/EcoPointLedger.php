<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class EcoPointLedger extends Model { protected $table = 'ecopoint_ledgers'; protected $guarded = []; public function user() { return $this->belongsTo(User::class); } }
