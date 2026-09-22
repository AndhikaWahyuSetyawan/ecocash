<?php
namespace App\Services;
use App\Models\WastePrice;
class WasteValueCalculatorService { public function calculate(float $weight, WastePrice $price): int { return (int) round($weight * $price->price_per_kg); } }
