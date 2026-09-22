<div>
    <x-ui.page-header
        title="Reward & Gamifikasi"
        subtitle="Pantau pencapaian lingkunganmu, kumpulkan saldo ECOPOINT, dan naikkan level pemilah untuk apresiasi lebih tinggi."
    />

    {{-- Kartu Ringkasan ECOPOINT & Level --}}
    <div class="row g-4 mb-4">
        {{-- Kartu ECOPOINT (Amber Glow) --}}
        <div class="col-12 col-md-6">
            <div class="p-4 rounded-4 text-white h-100 shadow-sm d-flex flex-column justify-content-between position-relative overflow-hidden reward-hero-card"
                 style="background: linear-gradient(135deg, #b45309 0%, #d97706 60%, #f59e0b 100%); min-height: 200px; border: 1px solid rgba(255,255,255,0.15);">
                {{-- Decorative ambient sparkle & circle --}}
                <div class="ambient-glow-circle" style="background: radial-gradient(circle, rgba(253, 224, 71, 0.25) 0%, transparent 70%);"></div>

                <div>
                    <div class="d-flex align-items-center justify-content-between mb-3 position-relative" style="z-index: 2;">
                        <div class="d-flex align-items-center gap-2">
                            <div class="coin-micro-icon">
                                <i class="bi bi-coin"></i>
                            </div>
                            <span class="small fw-bold text-white text-uppercase tracking-wider" style="letter-spacing: 0.05em; text-shadow: 0 1px 2px rgba(0,0,0,0.2);">
                                Saldo Penghargaan
                            </span>
                        </div>
                        <span class="badge bg-white text-dark fw-bold px-2.5 py-1 rounded-pill shadow-xs" style="font-size: 0.72rem; letter-spacing: 0.02em;">
                            ECOPOINT
                        </span>
                    </div>

                    <div class="position-relative" style="z-index: 2;">
                        <div class="text-white-50 small mb-1 fw-medium">Total Poin Terkumpul</div>
                        <div class="d-flex align-items-baseline gap-2 mb-2">
                            <span class="display-5 fw-extrabold text-white tracking-tight" style="font-family: var(--font-rubik); font-weight: 800; text-shadow: 0 2px 4px rgba(0,0,0,0.15);">
                                {{ number_format($totalPoints) }}
                            </span>
                            <span class="fs-5 fw-semibold text-warning" style="text-shadow: 0 1px 2px rgba(0,0,0,0.2);">pts</span>
                        </div>
                    </div>
                </div>

                <div class="position-relative border-top border-white-15 pt-3 mt-2" style="z-index: 2;">
                    <p class="small text-white-75 mb-0" style="font-size: 0.82rem; line-height: 1.5;">
                        <i class="bi bi-info-circle me-1 text-warning"></i>
                        Diberikan otomatis dari setiap kg sampah terpilah yang berhasil diverifikasi mitra di bank sampah.
                    </p>
                </div>
            </div>
        </div>

        {{-- Kartu Level & XP (Emerald Dynamic Glow) --}}
        <div class="col-12 col-md-6">
            <div class="eco-card p-4 h-100 d-flex flex-column justify-content-between position-relative overflow-hidden"
                 style="background: #ffffff; border: 1px solid var(--border-eco); box-shadow: var(--shadow-subtle);">
                <div>
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="text-muted small fw-bold text-uppercase" style="letter-spacing: 0.04em;">Tingkat Pemilah</span>
                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 rounded-pill fw-semibold" style="font-size: 0.72rem;">
                            <i class="bi bi-patch-check-fill me-1"></i>Level Aktif
                        </span>
                    </div>

                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="level-icon-pulse-wrapper">
                            @php
                                $currentIcon = $currentLevel?->badge_icon ?? 'bi-award';
                                $currentIcon = str_starts_with($currentIcon, 'bi-') ? $currentIcon : 'bi-' . $currentIcon;
                            @endphp
                            <i class="bi {{ $currentIcon }}"></i>
                        </div>
                        <div>
                            <h2 class="h5 fw-bold mb-0" style="color: var(--ink); letter-spacing: -0.01em;">{{ $currentLevel?->name ?? 'Pemilah Pemula' }}</h2>
                            <span class="small fw-semibold text-eco">{{ number_format($totalXp) }} Total XP</span>
                        </div>
                    </div>

                    @if($nextLevel)
                        @php
                            $minXp = $currentLevel?->minimum_xp ?? 0;
                            $targetXp = $nextLevel->minimum_xp;
                            $range = max(1, $targetXp - $minXp);
                            $progressInLevel = max(0, $totalXp - $minXp);
                            $pct = min(100, (int) round(($progressInLevel / $range) * 100));
                        @endphp
                        <div>
                            <div class="d-flex justify-content-between align-items-center small text-muted mb-1.5" style="font-size: 0.8rem;">
                                <span>Menuju <strong class="text-dark">{{ $nextLevel->name }}</strong></span>
                                <span class="badge bg-light text-secondary border fw-semibold">{{ number_format($targetXp - $totalXp) }} XP lagi</span>
                            </div>
                            <div class="progress" style="height: 10px; background-color: #f1f5f9; border-radius: 6px; overflow: hidden; border: 1px solid #e2e8f0;">
                                <div class="progress-bar bg-eco progress-bar-animated" role="progressbar" style="width: {{ $pct }}%; transition: width 0.6s ease;" aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="d-flex justify-content-between mt-1" style="font-size: 0.72rem; color: #94a3b8;">
                                <span>{{ number_format($minXp) }} XP</span>
                                <span>{{ $pct }}%</span>
                                <span>{{ number_format($targetXp) }} XP</span>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-success d-flex align-items-center gap-2 p-2.5 mb-0 rounded-3" style="font-size: 0.8rem;">
                            <i class="bi bi-trophy-fill fs-5 text-warning"></i>
                            <div><strong>Luar Biasa!</strong> Kamu telah meraih tingkat pemilah tertinggi di ECOCASH.</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Tingkatan Level Pemilah --}}
    <div class="eco-card p-4 p-md-5 mb-5 border shadow-xs" style="background: #ffffff; border-radius: 20px;">
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
            <div>
                <h2 class="h5 fw-bold mb-1" style="color: var(--ink);">Tingkatan Pemilah ECOCASH</h2>
                <p class="text-muted small mb-0">Jejak kontribusi daur ulangmu membuka pengakuan dan keistimewaan baru.</p>
            </div>
            <span class="badge bg-light text-secondary border px-3 py-1.5 rounded-pill fw-semibold" style="font-size: 0.78rem;">
                {{ $allLevels->count() }} Tingkatan
            </span>
        </div>

        <div class="row g-3 g-md-4">
            @foreach($allLevels as $lvl)
                @php
                    $isReached = $totalXp >= $lvl->minimum_xp;
                    $isCurrent = $currentLevel?->id === $lvl->id;
                    $badgeIcon = $lvl->badge_icon ?: 'bi-award';
                    $badgeIcon = str_starts_with($badgeIcon, 'bi-') ? $badgeIcon : 'bi-' . $badgeIcon;
                @endphp
                <div class="col-12 col-sm-6 col-lg">
                    <div class="p-3.5 p-md-4 rounded-4 border h-100 text-center level-card-item {{ $isCurrent ? 'is-current' : ($isReached ? 'is-reached' : 'is-locked') }}">
                        <div class="level-badge-avatar mb-3">
                            <i class="bi {{ $badgeIcon }}"></i>
                        </div>
                        <div class="fw-bold small text-truncate mb-1" style="color: var(--ink); font-size: 0.88rem;" title="{{ $lvl->name }}">
                            {{ $lvl->name }}
                        </div>
                        <div class="text-muted mb-3" style="font-size: 0.76rem;">
                            Min. <strong class="text-dark">{{ number_format($lvl->minimum_xp) }}</strong> XP
                        </div>
                        <div>
                            @if($isCurrent)
                                <span class="badge bg-success text-white rounded-pill px-2.5 py-1" style="font-size: 0.7rem; letter-spacing: 0.02em;">
                                    <i class="bi bi-star-fill me-1"></i>Posisi Kamu
                                </span>
                            @elseif($isReached)
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1" style="font-size: 0.7rem;">
                                    <i class="bi bi-check me-0.5"></i>Tercapai
                                </span>
                            @else
                                <span class="badge bg-light text-muted border rounded-pill px-2.5 py-1" style="font-size: 0.7rem;">
                                    <i class="bi bi-lock-fill me-0.5"></i>Terkunci
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Lencana Pencapaian (Achievements) --}}
    <div class="eco-card p-4 p-md-5 mb-5 border shadow-xs" style="background: #ffffff; border-radius: 20px;">
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
            <div>
                <h2 class="h5 fw-bold mb-1" style="color: var(--ink);">Lencana Pencapaian</h2>
                <p class="text-muted small mb-0">Selesaikan target setoran dan kelola sampah untuk mengoleksi lencana prestasi.</p>
            </div>
            <div class="badge bg-light text-secondary border px-3 py-1.5 rounded-pill fw-semibold" style="font-size: 0.78rem;">
                <span class="text-success fw-bold">{{ count($unlockedIds) }}</span> dari {{ $achievements->count() }} Lencana Terbuka
            </div>
        </div>

        <div class="row g-3 g-md-4">
            @foreach($achievements as $ach)
                @php
                    $isUnlocked = in_array($ach->id, $unlockedIds);
                    $achIcon = $ach->icon ?: 'bi-award-fill';
                    $achIcon = str_starts_with($achIcon, 'bi-') ? $achIcon : 'bi-' . $achIcon;
                @endphp
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="p-4 rounded-4 border h-100 d-flex flex-column align-items-center text-center achievement-badge-item {{ $isUnlocked ? 'badge-unlocked' : 'badge-locked' }}">
                        <div class="ach-icon-container mb-3 {{ $isUnlocked ? 'ach-unlocked-glow' : '' }}">
                            <i class="bi {{ $achIcon }}"></i>
                        </div>
                        <div class="fw-bold mb-1.5" style="color: var(--ink); font-size: 0.92rem;">{{ $ach->title }}</div>
                        <p class="text-muted small mb-4" style="font-size: 0.78rem; line-height: 1.5;">
                            {{ $ach->description }}
                        </p>
                        <span class="badge {{ $isUnlocked ? 'bg-success text-white' : 'bg-light text-muted border' }} px-3 py-1.5 rounded-pill mt-auto" style="font-size: 0.72rem;">
                            @if($isUnlocked)
                                <i class="bi bi-patch-check-fill me-1 text-warning"></i>Terbuka (+{{ $ach->reward_xp }} XP)
                            @else
                                <i class="bi bi-lock me-1"></i>Terkunci (+{{ $ach->reward_xp }} XP)
                            @endif
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Riwayat Mutasi ECOPOINT Terkini --}}
    <div class="eco-card p-4 p-md-5 mb-5 border shadow-xs" style="background: #ffffff; border-radius: 20px;">
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
            <div>
                <h2 class="h5 fw-bold mb-1" style="color: var(--ink);">Riwayat Penghargaan ECOPOINT</h2>
                <p class="text-muted small mb-0">Catatan perolehan poin dari aktivitas setoran atau misi lingkungan.</p>
            </div>
            <a href="{{ route('missions') }}" class="btn btn-sm btn-outline-success rounded-pill px-3.5 py-1.5 fw-semibold" style="font-size: 0.8rem;">
                <i class="bi bi-trophy me-1"></i>Jelajahi Misi Lingkungan
            </a>
        </div>

        @if($recentLedgers->isEmpty())
            <div class="text-center py-5 px-3 rounded-4" style="background: #f8fafc; border: 1px dashed #cbd5e1;">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center bg-white border mb-3 shadow-2xs" style="width: 54px; height: 54px;">
                    <i class="bi bi-coin fs-3 text-warning"></i>
                </div>
                <div class="fw-semibold text-dark mb-1">Belum ada riwayat mutasi poin</div>
                <div class="text-muted small max-w-sm mx-auto" style="font-size: 0.8rem;">
                    Mulai setorkan sampah terpilahmu atau selesaikan misi harian untuk mendapatkan saldo ECOPOINT!
                </div>
            </div>
        @else
            <div class="table-responsive rounded-3 border">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.86rem;">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3 py-3 text-muted fw-semibold">Aktivitas</th>
                            <th class="py-3 text-muted fw-semibold">Tanggal</th>
                            <th class="text-end pe-3 py-3 text-muted fw-semibold">Jumlah Poin</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentLedgers as $ledger)
                            <tr>
                                <td class="ps-3 py-3">
                                    <div class="d-flex align-items-center gap-2.5">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 {{ $ledger->amount >= 0 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}" style="width: 34px; height: 34px;">
                                            <i class="bi {{ $ledger->amount >= 0 ? 'bi-plus-lg' : 'bi-dash-lg' }}"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold" style="color: var(--ink);">{{ $ledger->description ?: 'Bonus Penghargaan ECOCASH' }}</div>
                                            <div class="small text-muted">{{ ucfirst($ledger->transaction_type ?: 'Reward') }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-muted">
                                    {{ $ledger->created_at?->translatedFormat('d M Y, H:i') ?? '-' }}
                                </td>
                                <td class="text-end pe-3">
                                    <span class="fw-bold {{ $ledger->amount >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ $ledger->amount >= 0 ? '+' : '' }}{{ number_format($ledger->amount) }} pts
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- Style Khusus Microinteractions & Visual Clarity --}}
    <style>
        .reward-hero-card {
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }
        .reward-hero-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(180, 83, 9, 0.25) !important;
        }
        .ambient-glow-circle {
            position: absolute;
            right: -30px;
            bottom: -30px;
            width: 170px;
            height: 170px;
            pointer-events: none;
            z-index: 1;
        }
        .border-white-15 {
            border-color: rgba(255, 255, 255, 0.18) !important;
        }

        /* Microinteraction: Animated Spinning/Pulsing Coin */
        .coin-micro-icon {
            width: 34px;
            height: 34px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(4px);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: #fef08a;
            animation: coin-tilt 4s ease-in-out infinite alternate;
        }
        @keyframes coin-tilt {
            0% { transform: scale(1) rotate(0deg); }
            50% { transform: scale(1.08) rotate(12deg); }
            100% { transform: scale(1) rotate(-8deg); }
        }

        /* Microinteraction: Pulse icon on current active level */
        .level-icon-pulse-wrapper {
            width: 52px;
            height: 52px;
            min-width: 52px;
            border-radius: 16px;
            background: #ecfdf5;
            border: 2px solid #a7f3d0;
            color: var(--eco);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            box-shadow: 0 4px 12px rgba(21, 128, 61, 0.15);
            animation: pulse-badge 3s infinite ease-in-out;
        }
        @keyframes pulse-badge {
            0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4); }
            70% { transform: scale(1.04); box-shadow: 0 0 0 10px rgba(16, 185, 129, 0); }
            100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }

        /* Level Cards */
        .level-card-item {
            transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1);
            background: #ffffff;
        }
        .level-card-item.is-current {
            border-color: var(--eco) !important;
            background: #f0fdf4 !important;
            box-shadow: 0 4px 14px rgba(21, 128, 61, 0.12);
            transform: translateY(-2px);
        }
        .level-card-item.is-reached:not(.is-current) {
            border-color: #cbd5e1;
            background: #fafbfc;
        }
        .level-card-item.is-locked {
            opacity: 0.55;
            background: #f8fafc;
            border-color: #e2e8f0;
        }
        .level-card-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.06);
        }
        .level-badge-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            transition: transform 0.2s ease;
        }
        .level-card-item.is-current .level-badge-avatar {
            background: var(--eco);
            color: #ffffff;
            box-shadow: 0 3px 10px rgba(21, 128, 61, 0.3);
        }
        .level-card-item.is-reached:not(.is-current) .level-badge-avatar {
            background: #dcfce7;
            color: var(--eco);
        }
        .level-card-item.is-locked .level-badge-avatar {
            background: #e2e8f0;
            color: #94a3b8;
        }
        .level-card-item:hover .level-badge-avatar {
            transform: scale(1.1);
        }

        /* Achievement Badges Microinteractions */
        .achievement-badge-item {
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            background: #ffffff;
        }
        .achievement-badge-item.badge-unlocked {
            border-color: #86efac !important;
            background: linear-gradient(180deg, #ffffff 0%, #f0fdf4 100%);
        }
        .achievement-badge-item.badge-unlocked:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(21, 128, 61, 0.14);
            border-color: var(--eco) !important;
        }
        .achievement-badge-item.badge-locked {
            opacity: 0.65;
            background: #f8fafc;
            border-color: #e2e8f0;
        }
        .achievement-badge-item.badge-locked:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }

        .ach-icon-container {
            width: 54px;
            height: 54px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.55rem;
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .badge-unlocked .ach-icon-container {
            background: linear-gradient(135deg, #fef08a 0%, #f59e0b 100%);
            color: #78350f;
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
        }
        .badge-locked .ach-icon-container {
            background: #e2e8f0;
            color: #94a3b8;
        }
        .achievement-badge-item:hover .ach-icon-container {
            transform: scale(1.14) rotate(4deg);
        }
    </style>
</div>
