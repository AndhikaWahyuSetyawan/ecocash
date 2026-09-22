<div>
<style>
.scanner-wrap { max-width: 760px; margin: 0 auto; }

/* Step progress bar */
.step-bar { display: flex; gap: 6px; margin-bottom: 1.75rem; }
.step-bar-seg {
    height: 4px; flex: 1; border-radius: 2px; background: #e2e8f0;
    transition: background 0.25s;
}
.step-bar-seg.done   { background: #22c55e; }
.step-bar-seg.active { background: #15803d; }

/* Upload zone (desktop) */
.drop-zone {
    border: 2px dashed #c8ddd0;
    border-radius: 10px;
    background: #f7faf8;
    min-height: 200px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    cursor: pointer;
    transition: border-color 0.15s, background 0.15s;
    padding: 2rem 1.5rem;
}
.drop-zone:hover, .drop-zone.drag-over { border-color: #15803d; background: #f0f9f3; }

/* Camera buttons — mobile first */
.camera-primary-btn {
    width: 100%;
    padding: 1rem 1.25rem;
    background: #15803d;
    color: #fff;
    border: none;
    border-radius: 10px;
    font-size: 1.0625rem;
    font-weight: 600;
    font-family: inherit;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.625rem;
    cursor: pointer;
    transition: background 0.15s;
    min-height: 56px;
}
.camera-primary-btn:hover, .camera-primary-btn:focus { background: #14532d; }
.camera-primary-btn:focus-visible { outline: 3px solid #22c55e; outline-offset: 2px; }
.camera-primary-btn i { font-size: 1.375rem; }

.gallery-btn {
    width: 100%;
    padding: 0.75rem 1rem;
    background: transparent;
    color: #17211b;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 0.9375rem;
    font-weight: 500;
    font-family: inherit;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    cursor: pointer;
    transition: border-color 0.15s, background 0.15s;
    min-height: 48px;
}
.gallery-btn:hover { border-color: #15803d; background: #f0f9f3; }

/* Preview image container with canvas overlay */
.image-preview-wrap {
    position: relative;
    width: 100%;
    border-radius: 10px;
    overflow: hidden;
    background: #000;
    line-height: 0;
}
.image-preview-wrap img {
    width: 100%;
    height: auto;
    display: block;
    max-height: 420px;
    object-fit: contain;
}
.bbox-canvas {
    position: absolute;
    top: 0; left: 0;
    width: 100%; height: 100%;
    pointer-events: none;
}

/* Detection card */
.detection-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 0.875rem 1rem;
}
.detection-card + .detection-card { margin-top: 0.625rem; }
.confidence-bar-wrap { background: #e2e8f0; border-radius: 2px; height: 4px; flex: 1; }
.confidence-bar { height: 4px; border-radius: 2px; background: #15803d; }
.confidence-bar.low { background: #f59e0b; }

/* Value estimate card */
.value-card {
    background: #f0f9f3;
    border: 1px solid #bbf7d0;
    border-radius: 8px;
    padding: 1rem 1.25rem;
}

/* Sorting tip */
.sorting-tip {
    background: #fff;
    border-left: 3px solid #22c55e;
    border-radius: 0 6px 6px 0;
    padding: 0.75rem 1rem;
    font-size: 0.875rem;
    color: #17211b;
}

/* Success card */
.success-card {
    background: #f0f9f3;
    border: 1px solid #bbf7d0;
    border-radius: 12px;
    padding: 2rem 1.5rem;
    text-align: center;
}

/* Loading spinner inline */
.spin { animation: spin 0.8s linear infinite; display: inline-block; }
@keyframes spin { to { transform: rotate(360deg); } }

/* AI note */
.ai-note {
    font-size: 0.78rem;
    color: #64748b;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 0.5rem 0.75rem;
}
</style>

<div class="scanner-wrap">

    {{-- ── STEP PROGRESS ─────────────────────────────── --}}
    @if($step !== 'submitted')
    <div class="step-bar" role="progressbar" aria-label="Langkah scan sampah">
        <div class="step-bar-seg {{ in_array($step,['review','weight']) ? 'done' : ($step==='upload' ? 'active' : '') }}"></div>
        <div class="step-bar-seg {{ $step==='weight' ? 'active' : ($step==='review' ? 'done' : '') }}"></div>
        <div class="step-bar-seg"></div>
    </div>
    @endif

    {{-- ── ERROR MODAL WITH ANIMATED SVG ICONS ────────────────── --}}
    @if($error)
        <x-ui.error-modal
            title="Terjadi Kesalahan Pemindaian"
            :message="$error"
            actionLabel="Lanjutkan dengan Mode Manual"
            actionWire="enableManualMode"
            dismissLabel="Coba Lagi Nanti"
            dismissWire="dismissError"
        />
    @endif


    {{-- ── SUCCESS / INFO MESSAGE ───────────────────── --}}
    @if($message && !$error && $step !== 'submitted')
    <div class="alert mb-4"
         style="background:#f0fdf4; border:1px solid #bbf7d0; border-radius:8px; padding:.75rem 1rem; font-size:.875rem; color:#166534;"
         role="status">
        <i class="bi bi-check-circle me-1"></i>{{ $message }}
    </div>
    @endif

    {{-- ══════════════════════════════════════════════ --}}
    {{-- STEP 1 — UPLOAD                               --}}
    {{-- ══════════════════════════════════════════════ --}}
    @if($step === 'upload')
    <div>
        <h1 class="h4 fw-bold mb-1">Scan Sampah</h1>
        <p class="mb-4" style="color:#64748b; font-size:.9rem;">
            Foto materialmu, lalu AI akan membantu mengenali jenisnya.
            Pemindaian memerlukan koneksi internet.
        </p>

        {{--
            Dua input terpisah (camera + gallery) — TIDAK ada wire:model.
            Upload dilakukan via $wire.upload() dari Alpine.js.
            Ini cara yang benar di Livewire 3 untuk file input programatik.
        --}}
        <div x-data>
            {{-- Input kamera (capture=environment) --}}
            <input type="file"
                   id="eco-input-camera"
                   accept="image/jpeg,image/jpg,image/png,image/webp"
                   capture="environment"
                   class="d-none"
                   x-on:change="
                       const f = $event.target.files[0];
                       if (f) $wire.upload('image', f);
                   ">

            {{-- Input galeri (tanpa capture) --}}
            <input type="file"
                   id="eco-input-gallery"
                   accept="image/jpeg,image/jpg,image/png,image/webp"
                   class="d-none"
                   x-on:change="
                       const f = $event.target.files[0];
                       if (f) $wire.upload('image', f);
                   ">

            {{-- Mobile: dua tombol --}}
            <div class="d-md-none mb-3">
                <button type="button"
                        class="camera-primary-btn mb-2"
                        onclick="document.getElementById('eco-input-camera').click()">
                    <i class="bi bi-camera-fill"></i>
                    Ambil Foto
                </button>
                <button type="button"
                        class="gallery-btn"
                        onclick="document.getElementById('eco-input-gallery').click()">
                    <i class="bi bi-images"></i>
                    Pilih dari Galeri
                </button>
            </div>

            {{-- Desktop: drop zone --}}
            <div class="d-none d-md-block mb-3"
                 x-data="{ dragging: false }"
                 @dragover.prevent="dragging = true"
                 @dragleave.prevent="dragging = false"
                 @drop.prevent="
                     dragging = false;
                     const f = $event.dataTransfer.files[0];
                     if (f) $wire.upload('image', f);
                 ">
                <div class="drop-zone" :class="{ 'drag-over': dragging }"
                     onclick="document.getElementById('eco-input-gallery').click()"
                     role="button" tabindex="0"
                     onkeydown="if(event.key==='Enter') document.getElementById('eco-input-gallery').click()"
                     aria-label="Klik atau seret foto ke sini">
                    <i class="bi bi-cloud-upload" style="font-size:2.5rem; color:#94a3b8;"></i>
                    <div style="font-weight:600; color:#475569;">Seret foto ke sini atau klik untuk memilih</div>
                    <div style="font-size:.8rem; color:#94a3b8;">JPEG, PNG, WebP — maks. 10 MB</div>
                    <button type="button"
                            class="btn-eco px-4 py-2 mt-2"
                            style="border-radius:6px; font-size:.875rem;"
                            onclick="event.stopPropagation(); document.getElementById('eco-input-camera').click()">
                        <i class="bi bi-camera me-1"></i>Buka Kamera
                    </button>
                </div>
            </div>
        </div>

        {{-- Upload progress --}}
        <div wire:loading wire:target="image" class="mb-3 ai-note">
            <i class="bi bi-arrow-repeat spin me-1"></i>Mengunggah foto...
        </div>

        {{-- Preview setelah foto dipilih --}}
        @if($image)
        <div class="eco-card p-3 mb-3">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="fw-semibold" style="font-size:.875rem;">Pratinjau foto</span>
                <button type="button" wire:click="$set('image', null)"
                        style="background:none;border:none;color:#64748b;cursor:pointer;font-size:.8rem;padding:0;">
                    <i class="bi bi-x-lg"></i> Ganti
                </button>
            </div>
            <img src="{{ $image->temporaryUrl() }}"
                 alt="Foto sampah yang akan dianalisis"
                 style="width:100%; max-height:320px; object-fit:contain; border-radius:8px; background:#111;">
        </div>
        @endif

        {{-- Tombol scan --}}
        <button type="button"
                wire:click="scan"
                wire:loading.attr="disabled"
                wire:target="scan,image"
                class="camera-primary-btn"
                @if(!$image) style="opacity:.45; cursor:not-allowed;" @endif>
            <span wire:loading.remove wire:target="scan,image">
                <i class="bi bi-stars me-1"></i>Analisis Foto dengan AI
            </span>
            <span wire:loading wire:target="scan">
                <i class="bi bi-arrow-repeat spin me-1"></i>AI sedang menganalisis...
            </span>
            <span wire:loading wire:target="image">
                <i class="bi bi-arrow-repeat spin me-1"></i>Mengunggah...
            </span>
        </button>

        <p class="mt-2 text-center" style="font-size:.78rem; color:#94a3b8;">
            Tidak ada sampah terdeteksi?
            <button type="button" wire:click="enableManualMode"
                    style="background:none; border:none; color:#15803d; font-weight:600; cursor:pointer; font-size:.78rem; font-family:inherit; padding:0;">
                Catat manual
            </button>
        </p>
    </div>

    {{-- ══════════════════════════════════════════════ --}}
    {{-- STEP 2 — REVIEW                               --}}
    {{-- ══════════════════════════════════════════════ --}}
    @elseif($step === 'review')
    <div>
        <h2 class="h5 fw-bold mb-1">Hasil Prediksi AI</h2>
        <p class="mb-4" style="color:#64748b; font-size:.875rem;">
            Prediksi AI bersifat informatif. Periksa dan koreksi kategori sebelum melanjutkan.
        </p>

        {{-- Image with bounding box canvas --}}
        @if($image && count($detections))
        <div class="mb-4"
             x-data="{
                 detections: {{ Js::from($detections) }},
                 draw() {
                     const img    = this.$refs.scanImg;
                     const canvas = this.$refs.bboxCanvas;
                     canvas.width  = img.naturalWidth;
                     canvas.height = img.naturalHeight;
                     const ctx = canvas.getContext('2d');
                     ctx.clearRect(0, 0, canvas.width, canvas.height);
                     const colors = ['#15803d','#f59e0b','#3b82f6','#dc2626','#8b5cf6'];
                     this.detections.forEach(function(d, i) {
                         const c  = colors[i % colors.length];
                         const w  = d.x2 - d.x1;
                         const h  = d.y2 - d.y1;
                         ctx.strokeStyle = c;
                         ctx.lineWidth   = Math.max(2, canvas.width / 200);
                         ctx.strokeRect(d.x1, d.y1, w, h);
                         const pct  = Math.round(d.confidence * 100);
                         const lbl  = d.class_name + ' ' + pct + '%';
                         const fs   = Math.max(14, canvas.width / 40);
                         ctx.font   = 'bold ' + fs + 'px system-ui';
                         const tw   = ctx.measureText(lbl).width;
                         const pad  = 6;
                         const bh   = fs + pad * 2;
                         ctx.fillStyle = c;
                         ctx.fillRect(d.x1, Math.max(0, d.y1 - bh), tw + pad * 2, bh);
                         ctx.fillStyle = '#fff';
                         ctx.fillText(lbl, d.x1 + pad, Math.max(bh, d.y1) - pad / 2);
                     });
                 }
             }">
            <div class="image-preview-wrap mb-3">
                <img x-ref="scanImg"
                     src="{{ $image->temporaryUrl() }}"
                     alt="Foto sampah dengan deteksi AI"
                     @load="draw()">
                <canvas x-ref="bboxCanvas" class="bbox-canvas"></canvas>
            </div>
        </div>
        @elseif($image)
        <div class="mb-4">
            <div class="image-preview-wrap">
                <img src="{{ $image->temporaryUrl() }}"
                     alt="Foto sampah"
                     style="width:100%; display:block; border-radius:10px;">
            </div>
        </div>
        @endif

        {{-- Detection results --}}
        @if(count($detections))
        <div class="mb-4">
            @foreach($detections as $det)
            @php
                $pct = round($det['confidence'] * 100);
                $lowConf = $det['confidence'] < 0.70;
            @endphp
            <div class="detection-card">
                <div class="d-flex align-items-start justify-content-between gap-3 mb-2">
                    <div>
                        <div class="fw-semibold" style="font-size:.9375rem;">{{ $det['class_name'] }}</div>
                        @if($det['corrected_class_id'])
                            @php $correctedCat = $categories->firstWhere('id', $det['corrected_class_id']); @endphp
                            <span class="badge-verified mt-1">Dikoreksi: {{ $correctedCat?->name ?? 'Kategori dipilih' }}</span>
                        @endif
                    </div>
                    <div class="text-end flex-shrink-0">
                        <div class="fw-bold" style="font-size:1.0625rem; color:{{ $lowConf ? '#f59e0b' : '#15803d' }};">
                            {{ $pct }}%
                        </div>
                        <div style="font-size:.75rem; color:#94a3b8;">confidence</div>
                    </div>
                </div>

                {{-- Confidence bar --}}
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div class="confidence-bar-wrap">
                        <div class="confidence-bar {{ $lowConf ? 'low' : '' }}" style="width:{{ $pct }}%;"></div>
                    </div>
                </div>

                {{-- Low confidence warning --}}
                @if($lowConf)
                <div class="mb-2" style="font-size:.8rem; color:#92400e; background:#fef3c7; border-radius:5px; padding:.375rem .625rem;">
                    <i class="bi bi-info-circle me-1"></i>
                    AI kurang yakin. Periksa dan koreksi kategori jika perlu.
                </div>
                @endif

                {{-- Correction dropdown --}}
                <div class="d-flex align-items-center gap-2 mt-2">
                    <label for="correct-{{ $det['id'] }}" class="form-label mb-0" style="font-size:.8rem; white-space:nowrap;">
                        Koreksi:
                    </label>
                    <select id="correct-{{ $det['id'] }}"
                            class="form-select form-select-sm"
                            wire:change="correct({{ $det['id'] }}, $event.target.value)"
                            aria-label="Koreksi kategori untuk {{ $det['class_name'] }}">
                        <option value="">Gunakan prediksi AI</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}"
                            {{ ($det['corrected_class_id'] == $cat->id) ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
            @endforeach
        </div>
        @else
        {{-- No detections --}}
        <div class="eco-card p-4 text-center mb-4">
            <i class="bi bi-search" style="font-size:2rem; color:#94a3b8;"></i>
            <p class="mt-2 mb-1 fw-semibold">Tidak ada objek terdeteksi</p>
            <p style="font-size:.875rem; color:#64748b;">
                AI tidak dapat mengenali objek dalam foto ini. Pilih kategori secara manual di langkah berikutnya.
            </p>
        </div>
        @endif

        <div class="ai-note mb-4">
            <i class="bi bi-robot me-1"></i>
            Prediksi AI menggunakan model YOLOv8. Hasil bukan penilaian akhir — kamu yang menentukan kategori final.
        </div>

        <div class="d-flex gap-2 flex-column flex-sm-row">
            <button type="button" wire:click="resetAll"
                    class="btn btn-sm"
                    style="border:1px solid #e2e8f0; border-radius:6px; color:#475569; background:#fff; padding:.6rem 1rem;">
                <i class="bi bi-arrow-left me-1"></i>Ulangi foto
            </button>
            <button type="button" wire:click="goToWeight"
                    class="camera-primary-btn" style="flex:1;">
                Lanjutkan <i class="bi bi-arrow-right ms-1"></i>
            </button>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════ --}}
    {{-- STEP 3 — WEIGHT & SUBMIT                      --}}
    {{-- ══════════════════════════════════════════════ --}}
    @elseif($step === 'weight')
    <div>
        <h2 class="h5 fw-bold mb-1">Catat Setoran</h2>
        <p class="mb-4" style="color:#64748b; font-size:.875rem;">
            Masukkan berat material dan pilih mitra bank sampah tujuan.
        </p>

        {{-- Sorting instruction --}}
        @if($sortingInstruction)
        <div class="sorting-tip mb-4">
            <div class="fw-semibold mb-1" style="font-size:.875rem;">
                <i class="bi bi-lightbulb me-1" style="color:#22c55e;"></i>Cara memilah
            </div>
            {{ $sortingInstruction }}
            @if($processingRoute)
            <div class="mt-1" style="font-size:.8rem; color:#64748b;">
                Rute: {{ $processingRoute }}
            </div>
            @endif
        </div>
        @endif

        <div class="row g-3">
            {{-- Category --}}
            <div class="col-12">
                <label for="inp-category" class="form-label">
                    Kategori material <span style="color:#dc2626;">*</span>
                </label>
                <select id="inp-category"
                        class="form-select"
                        wire:model.live="categoryId"
                        required>
                    <option value="">Pilih kategori</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Weight --}}
            <div class="col-sm-6">
                <label for="inp-weight" class="form-label">
                    Berat (kg) <span style="color:#dc2626;">*</span>
                </label>
                <input id="inp-weight"
                       type="number"
                       step="0.001"
                       min="0.001"
                       max="9999"
                       class="form-control"
                       wire:model="weight"
                       placeholder="contoh: 2.5"
                       required>
            </div>

            {{-- Partner --}}
            <div class="col-sm-6">
                <label for="inp-partner" class="form-label">
                    Mitra tujuan <span style="color:#dc2626;">*</span>
                </label>
                <select id="inp-partner"
                        class="form-select"
                        wire:model="partnerId"
                        required>
                    <option value="">Pilih mitra</option>
                    @foreach($partners as $p)
                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Calculate button --}}
        <button type="button"
                wire:click="calculate"
                wire:loading.attr="disabled"
                wire:target="calculate"
                class="btn w-100 mt-3"
                style="border:1px solid #15803d; color:#15803d; background:#f0f9f3; border-radius:7px; font-weight:600; padding:.7rem; font-family:inherit;">
            <span wire:loading.remove wire:target="calculate">
                <i class="bi bi-calculator me-1"></i>Hitung estimasi nilai
            </span>
            <span wire:loading wire:target="calculate">
                <i class="bi bi-arrow-repeat spin me-1"></i>Menghitung...
            </span>
        </button>

        {{-- Estimated value result --}}
        @if($estimatedValue > 0)
        <div class="value-card mt-3">
            <div class="row g-0">
                <div class="col-6 pe-3" style="border-right:1px solid #bbf7d0;">
                    <div style="font-size:.78rem; color:#64748b; margin-bottom:.125rem;">Estimasi nilai</div>
                    <div style="font-size:1.375rem; font-weight:700; color:#15803d;">
                        Rp{{ number_format($estimatedValue, 0, ',', '.') }}
                    </div>
                    <div style="font-size:.75rem; color:#64748b;">{{ $weight }} kg × harga/kg</div>
                </div>
                <div class="col-6 ps-3">
                    <div style="font-size:.78rem; color:#64748b; margin-bottom:.125rem;">Estimasi ECOPOINT</div>
                    <div style="font-size:1.375rem; font-weight:700; color:#f59e0b;">
                        {{ number_format($estimatedPoints) }}
                    </div>
                    <div style="font-size:.75rem; color:#64748b;">poin (setelah verifikasi)</div>
                </div>
            </div>
            <div class="mt-2" style="font-size:.73rem; color:#64748b;">
                * ECOPOINT dikreditkan setelah mitra menyetujui setoran.
            </div>
        </div>
        @endif

        {{-- Action buttons --}}
        <div class="d-flex gap-2 mt-4 flex-column flex-sm-row">
            <button type="button"
                    wire:click="$set('step', 'review')"
                    class="btn btn-sm"
                    style="border:1px solid #e2e8f0; border-radius:6px; color:#475569; background:#fff; padding:.6rem 1rem;">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </button>
            <button type="button"
                    wire:click="submit"
                    wire:loading.attr="disabled"
                    wire:target="submit"
                    @if(!$categoryId || !$weight || !$partnerId) disabled @endif
                    class="camera-primary-btn" style="flex:1;"
                    @if(!$categoryId || !$weight || !$partnerId) style="flex:1; opacity:.45; cursor:not-allowed;" @endif>
                <span wire:loading.remove wire:target="submit">
                    <i class="bi bi-send me-1"></i>Kirim Setoran
                </span>
                <span wire:loading wire:target="submit">
                    <i class="bi bi-arrow-repeat spin me-1"></i>Menyimpan...
                </span>
            </button>
        </div>

        <p class="mt-2 text-center" style="font-size:.78rem; color:#94a3b8;">
            Setoran akan berstatus menunggu verifikasi mitra.
        </p>
    </div>

    {{-- ══════════════════════════════════════════════ --}}
    {{-- STEP 4 — SUBMITTED                            --}}
    {{-- ══════════════════════════════════════════════ --}}
    @elseif($step === 'submitted')
    <div class="success-card">
        <div style="width:64px; height:64px; background:#dcfce7; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; margin-bottom:1rem;">
            <i class="bi bi-check-lg" style="font-size:1.75rem; color:#15803d;"></i>
        </div>
        <h2 class="h5 fw-bold mb-2">Setoran Berhasil Dikirim</h2>
        @if($depositId)
        <div class="mb-3">
            <span style="font-size:.8rem; color:#64748b;">ID Setoran</span><br>
            <span style="font-size:1.125rem; font-weight:700; color:#17211b;">#{{ $depositId }}</span>
        </div>
        @endif
        <p style="font-size:.875rem; color:#64748b; max-width:320px; margin:0 auto 1.5rem;">
            Setoran menunggu verifikasi dari mitra bank sampah. ECOPOINT dikreditkan setelah disetujui.
        </p>
        <div class="d-flex gap-2 justify-content-center flex-column flex-sm-row">
            <button type="button" wire:click="resetAll"
                    class="camera-primary-btn" style="max-width:200px; margin:0 auto;">
                <i class="bi bi-camera me-1"></i>Scan Lagi
            </button>
            <a href="{{ route('setoran') }}"
               class="btn"
               style="border:1px solid #e2e8f0; border-radius:8px; color:#475569; background:#fff; padding:.75rem 1.25rem; font-weight:500; text-decoration:none; display:inline-flex; align-items:center; gap:.375rem;">
                <i class="bi bi-clock-history"></i>Lihat Riwayat
            </a>
        </div>
    </div>
    @endif

</div>
</div>
