<?php

namespace App\Livewire;

use App\Models\AiDetection;
use App\Models\AiScan;
use App\Models\Deposit;
use App\Models\Partner;
use App\Models\WasteCategory;
use App\Models\WastePrice;
use App\Services\AiWasteScannerService;
use App\Services\EcoPointService;
use App\Services\WasteSortingRecommendationService;
use App\Services\WasteValueCalculatorService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;

class WasteScanner extends Component
{
    use WithFileUploads;

    // Step tracking: 'upload' | 'review' | 'weight' | 'submitted'
    public string $step = 'upload';

    // File upload
    public $image = null;
    public bool $manualMode = false;

    // Scan results — stored as plain arrays for Livewire serialization
    public ?int $scanId = null;
    public array $detections = [];          // [['id','class_id','class_name','confidence','x1','y1','x2','y2','corrected_class_id'], ...]
    public array $detectionCorrections = []; // [detection_id => category_id]

    // Sorting recommendation
    public ?string $sortingInstruction = null;
    public ?string $processingRoute = null;

    // Weight + value
    public ?string $weight = null;
    public ?int $categoryId = null;
    public ?int $partnerId = null;
    public int $estimatedValue = 0;
    public int $estimatedPoints = 0;

    // Submitted deposit
    public ?int $depositId = null;

    // UI state
    public ?string $message = null;
    public ?string $error = null;
    public bool $isScanning = false;
    public bool $isSubmitting = false;

    public function resetAll(): void
    {
        $this->reset([
            'image', 'step', 'scanId', 'detections', 'detectionCorrections',
            'sortingInstruction', 'processingRoute', 'weight', 'categoryId',
            'partnerId', 'estimatedValue', 'estimatedPoints', 'message', 'error',
            'manualMode', 'depositId',
        ]);
        $this->step = 'upload';
    }

    public function dismissError(): void
    {
        $this->error = null;
    }

    public function enableManualMode(): void
    {
        $this->manualMode = true;
        $this->step = 'weight';
        $this->detections = [];
        $this->error = null;
        $this->message = 'Mode manual aktif. Pilih kategori dan masukkan berat secara langsung.';
    }

    public function scan(AiWasteScannerService $scanner): void
    {
        $this->validate(['image' => 'required|image|mimes:jpeg,jpg,png,webp|max:10240']);
        $this->reset(['error', 'message', 'detections', 'scanId', 'detectionCorrections']);
        $this->isScanning = true;

        try {
            $path   = $this->image->store('waste-scans', 'public');
            $result = $scanner->scan($this->image);

            $scan = AiScan::create([
                'user_id'            => Auth::id(),
                'image_path'         => $path,
                'model_name'         => 'yolov8n-waste-12cls-best.pt',
                'model_version'      => null,
                'processing_time_ms' => $result['processing_time_ms'] ?? null,
            ]);

            foreach ($result['detections'] as $d) {
                $detection = $scan->detections()->create([
                    'class_id'   => $d['class_id'],
                    'class_name' => $d['class_name'],
                    'confidence' => $d['confidence'],
                    'x1'         => $d['bbox']['x1'],
                    'y1'         => $d['bbox']['y1'],
                    'x2'         => $d['bbox']['x2'],
                    'y2'         => $d['bbox']['y2'],
                ]);

                $this->detections[] = $this->detectionToArray($detection);
            }

            $this->scanId = $scan->id;
            $this->step   = 'review';
            $this->message = count($this->detections)
                ? 'Prediksi selesai. Periksa dan koreksi jika perlu sebelum melanjutkan.'
                : 'Tidak ada objek yang terdeteksi. Pilih kategori secara manual.';

        } catch (\Throwable $e) {
            Log::error('AI scan failed', ['err' => $e->getMessage()]);
            $this->error = 'AI Service sedang tidak tersedia. Gunakan mode manual untuk mencatat setoran.';
        } finally {
            $this->isScanning = false;
        }
    }

    public function correct(int $detectionId, int $categoryId): void
    {
        $detection = AiDetection::findOrFail($detectionId);
        abort_unless($detection->aiScan->user_id === Auth::id(), 403);

        $detection->update([
            'corrected_class_id' => $categoryId,
            'corrected_by'       => Auth::id(),
            'corrected_at'       => now(),
        ]);

        $this->detectionCorrections[$detectionId] = $categoryId;

        // Update the stored array in place
        foreach ($this->detections as $i => $d) {
            if ($d['id'] === $detectionId) {
                $this->detections[$i] = $this->detectionToArray($detection->fresh());
                break;
            }
        }
    }

    public function goToWeight(): void
    {
        $this->step = 'weight';
        $this->message = null;
        $this->loadSortingRecommendation();
    }

    public function calculate(): void
    {
        $this->validate([
            'weight'     => 'required|numeric|min:0.001|max:9999',
            'categoryId' => 'required|exists:waste_categories,id',
        ]);
        $this->reset('error');

        $price = $this->getActivePrice();

        if (! $price) {
            $this->error = 'Harga untuk kategori ini belum tersedia. Hubungi mitra untuk informasi lebih lanjut.';
            return;
        }

        $this->estimatedValue  = app(WasteValueCalculatorService::class)->calculate((float) $this->weight, $price);
        $this->estimatedPoints = app(EcoPointService::class)->pointsForValue($this->estimatedValue);

        $this->loadSortingRecommendation();
    }

    public function submit(): void
    {
        $this->validate([
            'weight'     => 'required|numeric|min:0.001|max:9999',
            'categoryId' => 'required|exists:waste_categories,id',
            'partnerId'  => 'required|exists:partners,id',
        ]);

        if ($this->isSubmitting) {
            return;
        }
        $this->isSubmitting = true;
        $this->reset('error');

        try {
            $price = $this->getActivePrice();

            if (! $price) {
                $this->error = 'Harga tidak tersedia untuk kategori dan mitra ini. Pilih mitra lain.';
                $this->isSubmitting = false;
                return;
            }

            $value  = app(WasteValueCalculatorService::class)->calculate((float) $this->weight, $price);
            $points = app(EcoPointService::class)->pointsForValue($value);

            $firstDetection = $this->detections[0] ?? null;

            $deposit = Deposit::create([
                'user_id'      => Auth::id(),
                'partner_id'   => $this->partnerId,
                'status'       => 'pending_verification',
                'submitted_at' => now(),
            ]);

            $deposit->items()->create([
                'ai_detection_id'    => $firstDetection['id'] ?? null,
                'waste_category_id'  => $this->categoryId,
                'ai_category'        => $firstDetection['class_name'] ?? null,
                'declared_weight'    => $this->weight,
                'price_per_kg'       => $price->price_per_kg,
                'estimated_value'    => $value,
                'estimated_ecopoint' => $points,
            ]);

            // Catat estimasi saldo tertunda di dompet
            app(\App\Services\WalletService::class)->recordPendingDeposit(Auth::user(), $value);

            // Progres misi scan/setoran
            app(\App\Services\GamificationService::class)->recordActionProgress(Auth::user(), 'scan_count', 1);

            // Kirim notifikasi konfirmasi pengajuan
            \App\Models\InAppNotification::create([
                'user_id' => Auth::id(),
                'title' => 'Setoran Terkirim',
                'message' => 'Setoran #' . $deposit->id . ' sebesar ' . $this->weight . ' kg berhasil dikirim. Menunggu verifikasi mitra bank sampah.',
                'type' => 'deposit_submitted',
                'action_url' => '/setoran/' . $deposit->id,
            ]);

            $this->depositId = $deposit->id;
            $this->step      = 'submitted';
            $this->message   = 'Setoran #' . $deposit->id . ' berhasil disimpan dan menunggu verifikasi mitra.';

        } catch (\Throwable $e) {
            Log::error('Deposit submit failed', ['err' => $e->getMessage()]);
            $this->error = 'Gagal menyimpan setoran. Silakan coba lagi.';
        } finally {
            $this->isSubmitting = false;
        }
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.waste-scanner', [
            'categories' => WasteCategory::orderBy('name')->get(),
            'partners'   => Partner::where('is_active', true)->orderBy('name')->get(),
        ])->layout('layouts.app');
    }

    // --- Helpers ---

    private function detectionToArray(AiDetection $d): array
    {
        return [
            'id'                 => $d->id,
            'class_id'           => $d->class_id,
            'class_name'         => $d->class_name,
            'confidence'         => $d->confidence,
            'x1'                 => $d->x1,
            'y1'                 => $d->y1,
            'x2'                 => $d->x2,
            'y2'                 => $d->y2,
            'corrected_class_id' => $d->corrected_class_id,
        ];
    }

    private function loadSortingRecommendation(): void
    {
        if (! $this->categoryId) {
            return;
        }
        $cat = WasteCategory::find($this->categoryId);
        if ($cat) {
            $rec                    = app(WasteSortingRecommendationService::class)->for($cat);
            $this->sortingInstruction = $rec['instruction'] ?? null;
            $this->processingRoute    = $rec['route'] ?? null;
        }
    }

    private function getActivePrice(): ?WastePrice
    {
        $query = WastePrice::where('waste_category_id', $this->categoryId)
            ->where('is_active', true)
            ->whereDate('effective_from', '<=', now())
            ->where(fn ($q) => $q->whereNull('effective_until')->orWhereDate('effective_until', '>=', now()))
            ->orderByDesc('effective_from');

        if ($this->partnerId) {
            $query->where('partner_id', $this->partnerId);
        }

        return $query->first();
    }
}
