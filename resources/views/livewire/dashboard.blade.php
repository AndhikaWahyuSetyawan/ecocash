<div>
    {{-- Onboarding Banner untuk pengguna baru yang belum menyelesaikan tutorial --}}
    {{-- Onboarding Banner dengan Animasi Transisi & Dismiss Permanen --}}
    @if(!$user->has_completed_onboarding)
    <div class="eco-card p-4 mb-4 border-success-subtle bg-white shadow-sm position-relative overflow-hidden onboarding-card"
         x-data="{
             step: 1,
             totalSteps: 3,
             direction: 'next',
             dismissed: false,
             init() {
                 if (localStorage.getItem('ecocash_onboarding_dismissed_{{ $user->id }}')) {
                     this.dismissed = true;
                 }
             },
             close() {
                 this.dismissed = true;
                 localStorage.setItem('ecocash_onboarding_dismissed_{{ $user->id }}', 'true');
                 $wire.completeOnboarding();
             },
             goNext(target) {
                 this.direction = 'next';
                 this.step = target;
             },
             goPrev(target) {
                 this.direction = 'prev';
                 this.step = target;
             }
         }"
         x-show="!dismissed"
         x-transition:leave="transition ease-in duration-250"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-95"
         style="border-left: 4px solid var(--eco) !important;">

        {{-- Top Bar Card --}}
        <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
            <div class="d-flex align-items-center gap-2">
                <span class="badge px-2.5 py-1 text-white rounded-pill fw-semibold"
                      style="background: linear-gradient(135deg, var(--eco) 0%, #166534 100%); font-size: 0.72rem; letter-spacing: 0.02em;">
                    <i class="bi bi-compass me-1"></i> Panduan Singkat ECOCASH
                </span>
                {{-- Step Indicator Dots --}}
                <div class="d-flex align-items-center gap-1.5 ms-2">
                    <span class="step-dot" :class="step === 1 ? 'active' : (step > 1 ? 'completed' : '')"></span>
                    <span class="step-dot" :class="step === 2 ? 'active' : (step > 2 ? 'completed' : '')"></span>
                    <span class="step-dot" :class="step === 3 ? 'active' : ''"></span>
                </div>
            </div>
            <button type="button" @click="close()" class="btn-close" aria-label="Tutup panduan" title="Tutup panduan"></button>
        </div>

        {{-- Step Content Carousel with Smooth Transitions --}}
        <div class="position-relative" style="min-height: 90px;">
            {{-- Step 1 --}}
            <div x-show="step === 1"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 translate-x-6"
                 x-transition:enter-end="opacity-100 translate-x-0"
                 x-transition:leave="transition ease-in duration-200 transform position-absolute w-100 top-0"
                 x-transition:leave-start="opacity-100 translate-x-0"
                 x-transition:leave-end="opacity-0 -translate-x-6">
                <div class="d-flex align-items-start gap-3">
                    <div class="step-icon-badge">
                        <i class="bi bi-camera-fill text-success"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h2 class="h6 fw-bold mb-1" style="color: var(--ink);">1. Foto & Kenali Sampah</h2>
                        <p class="text-muted small mb-3">Gunakan <strong>AI Scanner</strong> untuk mendeteksi jenis material sampah seperti botol PET, kertas, kardus, atau kaleng logam secara otomatis.</p>
                        <div class="d-flex align-items-center justify-content-between pt-1">
                            <button type="button" @click="close()" class="btn btn-sm text-muted px-0 hover-underline">Lewati semua</button>
                            <button type="button" @click="goNext(2)" class="btn btn-sm btn-eco d-inline-flex align-items-center px-3.5 py-1.5 shadow-sm fw-medium">
                                <span class="me-2">Lanjut</span>
                                <i class="bi bi-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Step 2 --}}
            <div x-show="step === 2"
                 x-cloak
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 translate-x-6"
                 x-transition:enter-end="opacity-100 translate-x-0"
                 x-transition:leave="transition ease-in duration-200 transform position-absolute w-100 top-0"
                 x-transition:leave-start="opacity-100 translate-x-0"
                 x-transition:leave-end="opacity-0 -translate-x-6">
                <div class="d-flex align-items-start gap-3">
                    <div class="step-icon-badge">
                        <i class="bi bi-geo-alt-fill text-success"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h2 class="h6 fw-bold mb-1" style="color: var(--ink);">2. Setor ke Bank Sampah</h2>
                        <p class="text-muted small mb-3">Pilih mitra bank sampah terdekat, serahkan sampah terpilahmu, dan dapatkan verifikasi timbangan berat aktual di lokasi.</p>
                        <div class="d-flex align-items-center justify-content-between pt-1">
                            <button type="button" @click="goPrev(1)" class="btn btn-sm text-secondary px-2 d-inline-flex align-items-center">
                                <i class="bi bi-arrow-left me-2"></i>
                                <span>Kembali</span>
                            </button>
                            <button type="button" @click="goNext(3)" class="btn btn-sm btn-eco d-inline-flex align-items-center px-3.5 py-1.5 shadow-sm fw-medium">
                                <span class="me-2">Lanjut</span>
                                <i class="bi bi-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Step 3 --}}
            <div x-show="step === 3"
                 x-cloak
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 translate-x-6"
                 x-transition:enter-end="opacity-100 translate-x-0"
                 x-transition:leave="transition ease-in duration-200 transform position-absolute w-100 top-0"
                 x-transition:leave-start="opacity-100 translate-x-0"
                 x-transition:leave-end="opacity-0 -translate-x-6">
                <div class="d-flex align-items-start gap-3">
                    <div class="step-icon-badge">
                        <i class="bi bi-wallet2 text-success"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h2 class="h6 fw-bold mb-1" style="color: var(--ink);">3. Dapatkan Saldo & Reward</h2>
                        <p class="text-muted small mb-3">Saldo rupiah langsung otomatis masuk ke akun dompet dan bebas ditarik ke rekening bank atau e-wallet pilihanmu kapan saja.</p>
                        <div class="d-flex align-items-center justify-content-between pt-1">
                            <button type="button" @click="goPrev(2)" class="btn btn-sm text-secondary px-2 d-inline-flex align-items-center">
                                <i class="bi bi-arrow-left me-2"></i>
                                <span>Kembali</span>
                            </button>
                            <button type="button" @click="close()" class="btn btn-sm btn-eco d-inline-flex align-items-center px-4 py-1.5 shadow-sm fw-medium">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                <span>Mulai Sekarang</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Greeting & Primary Action --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h1 class="h4 mb-0 fw-bold" style="color: var(--ink); letter-spacing: -0.02em;">
                Selamat datang, {{ $user->name }}!
            </h1>
            <p class="text-muted small mb-0 mt-1">
                @if($stats['pendingDepositsCount'] > 0)
                    <span class="text-warning-emphasis fw-semibold">
                        <i class="bi bi-clock-history me-1"></i>{{ $stats['pendingDepositsCount'] }} setoran sedang diverifikasi mitra.
                    </span>
                @else
                    Ubah sampah di sekitarmu menjadi nilai nyata hari ini.
                @endif
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('scanner') }}" class="btn btn-eco px-3 py-2 shadow-sm d-flex align-items-center gap-2">
                <i class="bi bi-camera fs-6"></i>
                <span>Scan Sampah</span>
            </a>
        </div>
    </div>

    {{-- Baris Saldo Dompet & Level Progress --}}
    <div class="row g-3 mb-4">
        {{-- Dompet Saldo Tunai --}}
        <div class="col-12 col-lg-7">
            <div class="p-4 rounded-4 text-white h-100 d-flex flex-column justify-content-between shadow-sm position-relative overflow-hidden eco-card"
                 style="background: linear-gradient(135deg, #14532d 0%, #15803d 100%); min-height: 180px; border: none;">
                <!-- Decorative background ambient glow -->
                <div style="position: absolute; right: -20px; bottom: -20px; width: 140px; height: 140px; background: radial-gradient(circle, rgba(34,197,94,0.3) 0%, transparent 70%); pointer-events: none;"></div>
                
                <div class="d-flex align-items-center justify-content-between mb-3 position-relative" style="z-index: 1;">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-wallet2 fs-5 text-white-50"></i>
                        <span class="small fw-semibold text-white-50 text-uppercase tracking-wider">Saldo Dompet ECOCASH</span>
                    </div>
                    <a href="{{ route('wallet') }}" class="badge bg-white text-dark text-decoration-none px-2 py-1 rounded-pill" style="font-size: 0.75rem;">
                        Rincian Saldo <i class="bi bi-chevron-right ms-1"></i>
                    </a>
                </div>

                <div class="position-relative" style="z-index: 1;">
                    <div class="text-white-50 small mb-1">Saldo Tersedia</div>
                    <div class="display-6 fw-bold tracking-tight text-white mb-1">
                        Rp{{ number_format($stats['availableBalance'], 0, ',', '.') }}
                    </div>
                    <div class="small text-white-50">
                        Tertunda: Rp{{ number_format($stats['pendingBalance'], 0, ',', '.') }}
                    </div>
                </div>

                <div class="d-flex gap-2 mt-3 pt-2 position-relative" style="z-index: 1;">
                    <a href="{{ route('wallet.withdraw') }}" class="btn btn-light btn-sm fw-semibold text-success px-3 py-2 rounded-3 shadow-sm">
                        <i class="bi bi-arrow-down-circle me-1"></i> Tarik Saldo
                    </a>
                    <a href="{{ route('wallet') }}" class="btn btn-outline-light btn-sm px-3 py-2 rounded-3">
                        <i class="bi bi-clock-history me-1"></i> Mutasi
                    </a>
                </div>
            </div>
        </div>

        {{-- Level & Gamifikasi Ringkas --}}
        <div class="col-12 col-lg-5">
            <div class="eco-card p-4 h-100 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="small fw-bold text-muted text-uppercase tracking-wider">Tingkat Pengguna</span>
                        <a href="{{ route('reward') }}" class="text-decoration-none small text-eco fw-semibold">
                            Tukar Hadiah <i class="bi bi-chevron-right"></i>
                        </a>
                    </div>
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="fs-4">{{ $user->level_badge }}</span>
                        <div>
                            <span class="small text-muted">{{ number_format($stats['totalXp']) }} XP terkumpul</span>
                        </div>
                    </div>

                    {{-- XP Progress Bar --}}
                    @if($stats['nextLevel'])
                        @php
                            $minXp = $stats['currentLevel']?->minimum_xp ?? 0;
                            $targetXp = $stats['nextLevel']->minimum_xp;
                            $range = max(1, $targetXp - $minXp);
                            $progressInLevel = max(0, $stats['totalXp'] - $minXp);
                            $pct = min(100, (int) round(($progressInLevel / $range) * 100));
                        @endphp
                        <div class="mt-3">
                            <div class="d-flex justify-content-between small text-muted mb-1" style="font-size: 0.78rem;">
                                <span>Menuju {{ $stats['nextLevel']->name }}</span>
                                <span>{{ number_format($stats['xpToNextLevel']) }} XP lagi</span>
                            </div>
                            <div class="progress" style="height: 6px; background-color: #e2e8f0; border-radius: 3px;">
                                <div class="progress-bar bg-eco" role="progressbar" style="width: {{ $pct }}%;" aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    @else
                        <span class="badge bg-success-subtle text-success mt-2">Level Maksimal Tercapai!</span>
                    @endif
                </div>

                <div class="row g-2 mt-3 pt-3 border-top">
                    <div class="col-6">
                        <span class="text-muted small d-block" style="font-size: 0.78rem;">ECOPOINT</span>
                        <span class="fw-bold text-warning-emphasis fs-5">
                            {{ number_format($stats['ecopoints']) }}
                        </span>
                        <span class="small text-muted">pts</span>
                    </div>
                    <div class="col-6 border-start ps-3">
                        <span class="text-muted small d-block" style="font-size: 0.78rem;">Sampah Terpilah</span>
                        <span class="fw-bold text-eco fs-5">
                            {{ number_format((float) $stats['totalWeight'], 1) }}
                        </span>
                        <span class="small text-muted">kg</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Misi Hari Ini --}}
    <div class="mb-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h2 class="h6 fw-bold mb-0" style="color: var(--ink);">
                <i class="bi bi-trophy text-warning-emphasis me-1"></i> Misi & Tantangan Aktif
            </h2>
            <a href="{{ route('missions') }}" class="small text-eco fw-semibold text-decoration-none">
                Semua Misi <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="row g-3">
            @forelse($activeMissions as $item)
                <div class="col-12 col-md-4">
                    <x-ui.mission-card :mission="$item['model']" :userMission="$item['user_mission']" />
                </div>
            @empty
                <div class="col-12">
                    <x-ui.empty-state title="Belum ada misi aktif" description="Misi baru akan segera diperbarui secara berkala." />
                </div>
            @endforelse
        </div>
    </div>

    {{-- Setoran Terbaru & Dampak Nyata --}}
    <div class="row g-4">
        {{-- Riwayat Setoran Terbaru --}}
        <div class="col-12 col-lg-8">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h2 class="h6 fw-bold mb-0" style="color: var(--ink);">Setoran Terakhir</h2>
                <a href="{{ route('setoran') }}" class="small text-eco fw-semibold text-decoration-none">
                    Lihat Semua <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            @if($recentDeposits->isEmpty())
                <x-ui.empty-state
                    title="Belum ada setoran sampah"
                    description="Mulai langkah pertamamu dengan memfoto dan mendeteksi sampah daur ulangmu."
                    actionLabel="Scan Sampah Pertama"
                    actionUrl="{{ route('scanner') }}"
                />
            @else
                <div class="eco-card overflow-hidden">
                    <div class="table-responsive mb-0">
                        <table class="table table-hover align-middle mb-0" style="font-size: 0.875rem;">
                            <thead class="table-light">
                                <tr style="color: var(--ink-muted); font-size: 0.78rem;">
                                    <th class="ps-3 py-3">ID & MITRA</th>
                                    <th>STATUS</th>
                                    <th>BERAT</th>
                                    <th>NILAI</th>
                                    <th class="text-end pe-3">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentDeposits as $deposit)
                                    @php
                                        $val = $deposit->status === 'verified'
                                            ? $deposit->items->sum('final_value')
                                            : $deposit->items->sum('estimated_value');
                                        $wt = $deposit->items->sum('declared_weight');
                                    @endphp
                                    <tr>
                                        <td class="ps-3 py-3">
                                            <div class="fw-bold" style="color: var(--ink);">#{{ $deposit->id }}</div>
                                            <div class="small text-muted">{{ $deposit->partner?->name ?? 'Mitra Bank Sampah' }}</div>
                                        </td>
                                        <td>
                                            <x-ui.status-badge :status="$deposit->status" />
                                        </td>
                                        <td>
                                            <span class="fw-semibold">{{ number_format($wt, 2) }} kg</span>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-success">
                                                Rp{{ number_format($val, 0, ',', '.') }}
                                            </span>
                                        </td>
                                        <td class="text-end pe-3">
                                            <a href="{{ route('setoran.detail', $deposit) }}" class="btn btn-sm btn-outline-secondary py-1 px-2" style="font-size: 0.78rem;">
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>

        {{-- Estimasi Dampak Lingkungan Riil --}}
        <div class="col-12 col-lg-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h2 class="h6 fw-bold mb-0" style="color: var(--ink);">Dampak Lingkungan</h2>
            </div>
            <div class="eco-card p-4 h-100 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex align-items-center gap-2 text-eco mb-3">
                        <i class="bi bi-globe-americas fs-4"></i>
                        <span class="fw-bold">Bumi yang Lebih Lestari</span>
                    </div>
                    <p class="text-muted small mb-4" style="line-height: 1.5;">
                        Setiap kilogram sampah yang kakak setorkan dan daur ulang secara teratur mencegah pencemaran tanah dan mengurangi emisi gas rumah kaca.
                    </p>

                    <div class="p-3 rounded-3 mb-3" style="background: #f0fdf4; border: 1px solid #bbf7d0;">
                        <span class="small text-muted d-block" style="font-size: 0.75rem;">Estimasi Jejak Karbon Terselamatkan</span>
                        <span class="fs-4 fw-bold text-eco">
                            {{ number_format((float) $stats['co2Impact'], 2) }}
                        </span>
                        <span class="small text-muted">kg CO2e</span>
                    </div>
                </div>

                <div class="text-muted small pt-2 border-top" style="font-size: 0.75rem;">
                    *Estimasi dihitung berdasarkan faktor konversi emisi KLHK dan standar dampak ECOCASH.
                </div>
            </div>
        </div>
    </div>

    <style>
        .onboarding-card {
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .step-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background-color: #cbd5e1;
            transition: all 0.25s ease;
            display: inline-block;
        }
        .step-dot.active {
            width: 18px;
            border-radius: 4px;
            background-color: var(--eco);
        }
        .step-dot.completed {
            background-color: #86efac;
        }
        .step-icon-badge {
            width: 42px;
            height: 42px;
            min-width: 42px;
            min-height: 42px;
            border-radius: 12px;
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }
        .hover-underline:hover {
            text-decoration: underline !important;
            color: #334155 !important;
        }
        /* Utility transitions */
        .translate-x-6 { transform: translateX(18px); }
        .-translate-x-6 { transform: translateX(-18px); }
        .translate-x-0 { transform: translateX(0); }
        [x-cloak] { display: none !important; }
    </style>
</div>
