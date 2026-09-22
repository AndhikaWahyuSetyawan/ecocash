<?php
namespace App\Services;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;
class AiWasteScannerService { public function scan(UploadedFile $image): array { try { $response = Http::timeout((int) config('ecocash.ai_timeout', 30))->attach('image', fopen($image->getRealPath(),'r'), $image->getClientOriginalName())->post(rtrim(config('services.ai.url'),'/').'/api/v1/predict'); if ($response->failed() || !is_array($response->json('detections'))) throw new RuntimeException('Invalid AI response'); return $response->json(); } catch (\Throwable $e) { Log::error('AI waste scan failed', ['message'=>$e->getMessage()]); throw new RuntimeException('AI Service sedang tidak tersedia.', previous:$e); } } }
