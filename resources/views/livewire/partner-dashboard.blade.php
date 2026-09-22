<div>
    {{-- ===== Custom Scoped Styling for Partner Dashboard ===== --}}
    <style>
        .partner-stat-card {
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 1.25rem 1.35rem;
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-subtle);
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .partner-stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-hover);
        }
        .partner-stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: transparent;
        }
        .partner-stat-card.stat-amber::before { background: linear-gradient(90deg, #f59e0b, #fbbf24); }
        .partner-stat-card.stat-eco::before { background: linear-gradient(90deg, #15803d, #22c55e); }
        .partner-stat-card.stat-blue::before { background: linear-gradient(90deg, #0284c7, #38bdf8); }

        .stat-icon-bubble {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
            flex-shrink: 0;
        }
        .bubble-amber { background: #fffbeb; color: #d97706; border: 1px solid #fef3c7; }
        .bubble-eco { background: #f0fdf4; color: #15803d; border: 1px solid #dcfce7; }
        .bubble-blue { background: #f0f9ff; color: #0284c7; border: 1px solid #e0f2fe; }

        /* Modern Segmented Filter Tabs */
        .partner-nav-segmented {
            background: #eef3f0;
            padding: 5px;
            border-radius: 14px;
            display: inline-flex;
            gap: 6px;
            border: 1px solid #dce8df;
            max-width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        .partner-nav-btn {
            border: none;
            background: transparent;
            color: #475569;
            font-weight: 600;
            font-size: 0.86rem;
            padding: 8px 16px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
            cursor: pointer;
            transition: all 0.18s ease;
        }
        .partner-nav-btn:hover {
            color: var(--ink);
            background: rgba(255, 255, 255, 0.5);
        }
        .partner-nav-btn.active {
            background: #ffffff;
            color: var(--eco);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04);
        }
        .partner-nav-badge {
            font-size: 0.74rem;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 9999px;
            line-height: 1.2;
        }

        /* Deposit Ticket Card */
        .deposit-ticket {
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--shadow-subtle);
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            margin-bottom: 1.25rem;
        }
        .deposit-ticket:hover {
            box-shadow: var(--shadow-hover);
            border-color: #cbd5e1;
        }
        .deposit-ticket-header {
            padding: 1rem 1.25rem;
            background: #fafcfb;
            border-bottom: 1px solid #edf2ef;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
        }
        .user-avatar-initial {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: linear-gradient(135deg, #15803d 0%, #166534 100%);
            color: #ffffff;
            font-weight: 700;
            font-size: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 2px 6px rgba(21, 128, 61, 0.2);
        }

        /* Item Table Styling */
        .deposit-table {
            margin-bottom: 0;
            font-size: 0.86rem;
        }
        .deposit-table th {
            background: #f8faf9;
            color: #64748b;
            font-size: 0.76rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 600;
            padding: 0.65rem 1rem;
            border-bottom: 1px solid #e2e8f0;
            white-space: nowrap;
        }
        .deposit-table td {
            padding: 0.75rem 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
            color: #1e293b;
        }
        .deposit-table tr:last-child td {
            border-bottom: none;
        }

        /* Verification Form Card Area */
        .verify-box {
            background: #f8faf8;
            border-top: 1px solid #dce8df;
            padding: 1.25rem;
            border-bottom-left-radius: 16px;
            border-bottom-right-radius: 16px;
        }

        /* Segmented Radio Options */
        .decision-selector {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .decision-pill {
            flex: 1;
            min-width: 140px;
            position: relative;
        }
        .decision-pill input[type="radio"] {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }
        .decision-label {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 9px 14px;
            border-radius: 10px;
            border: 1.5px solid #cbd5e1;
            background: #ffffff;
            font-size: 0.875rem;
            font-weight: 600;
            color: #475569;
            cursor: pointer;
            transition: all 0.16s ease;
            user-select: none;
        }
        .decision-label:hover {
            border-color: #94a3b8;
            background: #f8fafc;
        }
        .decision-pill input[type="radio"]:checked + .decision-label.label-verify {
            border-color: #15803d;
            background: #f0fdf4;
            color: #15803d;
            box-shadow: 0 0 0 3px rgba(21, 128, 61, 0.15);
        }
        .decision-pill input[type="radio"]:checked + .decision-label.label-reject {
            border-color: #dc2626;
            background: #fef2f2;
            color: #dc2626;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.15);
        }

        /* Image Thumbnail Preview Link */
        .img-thumb-preview {
            width: 38px;
            height: 38px;
            border-radius: 8px;
            object-fit: cover;
            border: 1.5px solid #cbd5e1;
            transition: transform 0.15s ease, border-color 0.15s ease;
        }
        .img-thumb-preview:hover {
            transform: scale(1.1);
            border-color: var(--eco);
        }
    </style>

    {{-- ===== Header & Title ===== --}}
    <x-ui.page-header
        title="Verifikasi Setoran Nasabah"
        subtitle="Panel operasional verifikasi timbangan aktual, pengecekan klasifikasi sampah, dan persetujuan deposit">
        <x-slot:badge>
            <span class="badge bg-eco text-white px-2 py-1 rounded-pill" style="font-size: 0.72rem; font-weight: 600;">
                <i class="bi bi-shield-check me-1"></i>Mitra Resmi
            </span>
        </x-slot:badge>
    </x-ui.page-header>

    {{-- Flash status notification --}}
    @if(session('status'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-4" role="alert" style="border-radius: 12px; border: 1px solid #bbf7d0; background: #f0fdf4; color: #166534;">
            <i class="bi bi-check-circle-fill fs-5 text-success"></i>
            <div>{{ session('status') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    {{-- ===== Stats Overview Cards ===== --}}
    <div class="row g-3 mb-4">
        {{-- Card 1: Menunggu Verifikasi --}}
        <div class="col-12 col-md-4">
            <div class="partner-stat-card stat-amber h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="text-muted small fw-semibold">Menunggu Verifikasi</span>
                    <div class="stat-icon-bubble bubble-amber">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                </div>
                <div class="d-flex align-items-baseline gap-2">
                    <span class="fs-2 fw-bold" style="color: #b45309; letter-spacing: -0.02em;">
                        {{ number_format($stats['pending']) }}
                    </span>
                    <span class="text-muted small fw-medium">setoran aktif</span>
                </div>
                <p class="text-muted small mb-0 mt-2" style="font-size: 0.8rem;">
                    <i class="bi bi-exclamation-circle text-warning me-1"></i>Perlu ditimbang dan divalidasi
                </p>
            </div>
        </div>

        {{-- Card 2: Terverifikasi Hari Ini --}}
        <div class="col-12 col-md-4">
            <div class="partner-stat-card stat-eco h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="text-muted small fw-semibold">Selesai Diverifikasi Hari Ini</span>
                    <div class="stat-icon-bubble bubble-eco">
                        <i class="bi bi-check2-circle"></i>
                    </div>
                </div>
                <div class="d-flex align-items-baseline gap-2">
                    <span class="fs-2 fw-bold" style="color: #15803d; letter-spacing: -0.02em;">
                        {{ number_format($stats['today_count']) }}
                    </span>
                    <span class="text-muted small fw-medium">setoran selesai</span>
                </div>
                <p class="text-muted small mb-0 mt-2" style="font-size: 0.8rem;">
                    <i class="bi bi-shield-check text-success me-1"></i>Poin & saldo otomatis masuk ke dompet nasabah
                </p>
            </div>
        </div>

        {{-- Card 3: Total Berat Hari Ini --}}
        <div class="col-12 col-md-4">
            <div class="partner-stat-card stat-blue h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="text-muted small fw-semibold">Total Timbangan Terverifikasi</span>
                    <div class="stat-icon-bubble bubble-blue">
                        <i class="bi bi-box-seam"></i>
                    </div>
                </div>
                <div class="d-flex align-items-baseline gap-2">
                    <span class="fs-2 fw-bold" style="color: #0369a1; letter-spacing: -0.02em;">
                        {{ number_format($stats['today_kg'], 2) }}
                    </span>
                    <span class="fs-6 fw-bold text-muted">kg</span>
                </div>
                <p class="text-muted small mb-0 mt-2" style="font-size: 0.8rem;">
                    <i class="bi bi-calendar-event text-primary me-1"></i>Rekap per tanggal {{ now()->translatedFormat('d F Y') }}
                </p>
            </div>
        </div>
    </div>

    {{-- ===== Segmented Filter Navigation ===== --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div class="partner-nav-segmented">
            <button class="partner-nav-btn {{ $tab === 'pending' ? 'active' : '' }}"
                    wire:click="setTab('pending')"
                    type="button">
                <i class="bi bi-clock-history"></i>
                <span>Menunggu Verifikasi</span>
                @if($stats['pending'] > 0)
                    <span class="partner-nav-badge" style="background: #fef3c7; color: #92400e;">
                        {{ $stats['pending'] }}
                    </span>
                @endif
            </button>

            <button class="partner-nav-btn {{ $tab === 'today' ? 'active' : '' }}"
                    wire:click="setTab('today')"
                    type="button">
                <i class="bi bi-check2-all"></i>
                <span>Terverifikasi Hari Ini</span>
                @if($stats['today_count'] > 0)
                    <span class="partner-nav-badge" style="background: #dcfce7; color: #15803d;">
                        {{ $stats['today_count'] }}
                    </span>
                @endif
            </button>

            <button class="partner-nav-btn {{ $tab === 'all' ? 'active' : '' }}"
                    wire:click="setTab('all')"
                    type="button">
                <i class="bi bi-collection"></i>
                <span>Semua Riwayat</span>
            </button>
        </div>

        <div class="text-muted small fw-medium d-none d-sm-block">
            Menampilkan {{ $deposits->total() }} data setoran
        </div>
    </div>

    {{-- ===== Deposit List Section ===== --}}
    @if($deposits->isEmpty())
        <x-ui.empty-state
            title="{{ $tab === 'pending' ? 'Tidak Ada Antrean Verifikasi' : ($tab === 'today' ? 'Belum Ada Setoran Hari Ini' : 'Belum Ada Riwayat Setoran') }}"
            description="{{ $tab === 'pending' ? 'Bagus sekali! Semua setoran nasabah telah selesai Anda proses atau belum ada kiriman baru.' : 'Data transaksi akan otomatis tampil di sini begitu nasabah menyerahkan sampah.' }}"
            icon="{{ $tab === 'pending' ? 'bi-check-circle' : 'bi-inbox' }}"
        />
    @else
        <div class="deposit-list-wrap">
            @foreach($deposits as $deposit)
                <div class="deposit-ticket" x-data="{ action: '{{ $deposit->status === 'pending_verification' ? 'verified' : '' }}' }">
                    
                    {{-- Ticket Header: User info, timestamp, status --}}
                    <div class="deposit-ticket-header">
                        <div class="d-flex align-items-center gap-3">
                            <div class="user-avatar-initial">
                                {{ strtoupper(substr($deposit->user->name ?? 'N', 0, 1)) }}
                            </div>
                            <div>
                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                    <span class="fw-bold" style="color: var(--ink); font-size: 0.95rem;">
                                        {{ $deposit->user->name ?? 'Nasabah' }}
                                    </span>
                                    <span class="badge bg-light text-secondary border px-2 py-0.5 rounded-pill font-monospace" style="font-size: 0.75rem;">
                                        #{{ $deposit->id }}
                                    </span>
                                </div>
                                <div class="text-muted small d-flex align-items-center gap-2 mt-0.5" style="font-size: 0.8rem;">
                                    <span><i class="bi bi-calendar3 me-1"></i>{{ $deposit->submitted_at?->format('d M Y, H:i') ?? $deposit->created_at->format('d M Y, H:i') }}</span>
                                    @if($deposit->user->email)
                                        <span class="d-none d-md-inline">&bull; {{ $deposit->user->email }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="d-flex align-items-center gap-2">
                            @if($deposit->status === 'verified')
                                <span class="badge-verified">
                                    <i class="bi bi-check-circle-fill me-1"></i>Terverifikasi
                                </span>
                            @elseif($deposit->status === 'rejected')
                                <span class="badge-rejected">
                                    <i class="bi bi-x-circle-fill me-1"></i>Ditolak
                                </span>
                            @else
                                <span class="badge-pending">
                                    <i class="bi bi-clock-fill me-1"></i>Perlu Verifikasi
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Items / Scan Detection Details --}}
                    @if($deposit->items->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table deposit-table">
                                <thead>
                                    <tr>
                                        <th scope="col">Kategori (Deteksi AI)</th>
                                        <th scope="col" class="text-center">Akurasi AI</th>
                                        <th scope="col" class="text-end">Berat Deklarasi</th>
                                        <th scope="col" class="text-end">Estimasi Nilai</th>
                                        <th scope="col" class="text-end">Estimasi Poin</th>
                                        <th scope="col" class="text-center">Foto Sampah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($deposit->items as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="p-1 rounded bg-light border text-muted">
                                                        <i class="bi bi-tags"></i>
                                                    </span>
                                                    <div>
                                                        <span class="fw-semibold text-dark">{{ $item->ai_category ?? 'Material Umum' }}</span>
                                                        @if($item->verified_category && $item->verified_category !== $item->ai_category)
                                                            <div class="small text-success">
                                                                <i class="bi bi-arrow-right-short"></i>Revisi: {{ $item->verified_category }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                @if($item->detection)
                                                    @php $conf = $item->detection->confidence * 100; @endphp
                                                    <span class="badge {{ $conf >= 80 ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning-emphasis' }} border px-2 py-1 rounded-pill" style="font-size: 0.76rem;">
                                                        {{ number_format($conf, 1) }}%
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="text-end fw-semibold">
                                                {{ number_format($item->declared_weight ?? 0, 3) }} <span class="text-muted fw-normal">kg</span>
                                                @if($item->verified_weight && $deposit->status === 'verified')
                                                    <div class="small text-success fw-bold">
                                                        Aktual: {{ number_format($item->verified_weight, 3) }} kg
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="text-end fw-semibold" style="color: var(--eco);">
                                                Rp {{ number_format($item->final_value ?? $item->estimated_value ?? 0, 0, ',', '.') }}
                                            </td>
                                            <td class="text-end">
                                                <span class="badge bg-warning-subtle text-amber border px-2 py-1 rounded-pill" style="color: #92400e; font-size: 0.76rem;">
                                                    +{{ number_format($item->final_ecopoint ?? $item->estimated_ecopoint ?? 0) }} Pts
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                @if($item->detection?->aiScan?->image_path)
                                                    <a href="{{ asset('storage/' . $item->detection->aiScan->image_path) }}"
                                                       target="_blank"
                                                       rel="noopener noreferrer"
                                                       title="Lihat foto resolusi penuh"
                                                       class="d-inline-block">
                                                        <img src="{{ asset('storage/' . $item->detection->aiScan->image_path) }}"
                                                             alt="Foto Sampah"
                                                             class="img-thumb-preview shadow-2xs">
                                                    </a>
                                                @else
                                                    <span class="text-muted small">Tanpa foto</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    {{-- Rejection reason (if already rejected) --}}
                    @if($deposit->status === 'rejected' && $deposit->rejection_reason)
                        <div class="p-3 mx-3 mb-3" style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 10px; color: #991b1b; font-size: 0.875rem;">
                            <div class="d-flex align-items-center gap-2 mb-1 fw-bold">
                                <i class="bi bi-info-circle-fill text-danger"></i> Alasan Penolakan:
                            </div>
                            <div>{{ $deposit->rejection_reason }}</div>
                        </div>
                    @endif

                    {{-- Verification Action Box (Active only for pending deposits) --}}
                    @if($deposit->status === 'pending_verification')
                        <div class="verify-box">
                            <form method="POST" action="{{ route('partner.deposits.verify', $deposit) }}">
                                @csrf
                                @method('PATCH')

                                <div class="row g-3 align-items-end">
                                    {{-- Decision Selector (Verifikasi vs Tolak) --}}
                                    <div class="col-12 col-md-4">
                                        <label class="form-label small fw-bold text-dark mb-1">
                                            Keputusan Verifikasi
                                        </label>
                                        <div class="decision-selector">
                                            <div class="decision-pill">
                                                <input type="radio"
                                                       name="status"
                                                       id="status_verified_{{ $deposit->id }}"
                                                       value="verified"
                                                       x-model="action"
                                                       checked>
                                                <label for="status_verified_{{ $deposit->id }}" class="decision-label label-verify">
                                                    <i class="bi bi-check-circle-fill"></i> Setujui
                                                </label>
                                            </div>
                                            <div class="decision-pill">
                                                <input type="radio"
                                                       name="status"
                                                       id="status_rejected_{{ $deposit->id }}"
                                                       value="rejected"
                                                       x-model="action">
                                                <label for="status_rejected_{{ $deposit->id }}" class="decision-label label-reject">
                                                    <i class="bi bi-x-circle-fill"></i> Tolak
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Form when VERIFIED: Weight & Category --}}
                                    <div class="col-12 col-sm-6 col-md-3" x-show="action === 'verified'">
                                        <label for="verified_weight_{{ $deposit->id }}" class="form-label small fw-bold text-dark mb-1">
                                            Berat Timbangan Aktual (kg) <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <input type="number"
                                                   id="verified_weight_{{ $deposit->id }}"
                                                   name="verified_weight"
                                                   class="form-control"
                                                   step="0.001"
                                                   min="0.001"
                                                   value="{{ number_format($deposit->items->sum(fn($i) => $i->declared_weight ?? 0), 3, '.', '') }}"
                                                   placeholder="0.000"
                                                   required>
                                            <span class="input-group-text bg-light text-muted small fw-semibold">kg</span>
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-6 col-md-3" x-show="action === 'verified'">
                                        <label for="verified_category_{{ $deposit->id }}" class="form-label small fw-bold text-dark mb-1">
                                            Kategori Material
                                        </label>
                                        <input type="text"
                                               id="verified_category_{{ $deposit->id }}"
                                               name="verified_category"
                                               class="form-control"
                                               value="{{ $deposit->items->first()?->ai_category ?? '' }}"
                                               placeholder="Contoh: PET Bening"
                                               maxlength="255">
                                    </div>

                                    {{-- Form when REJECTED: Reason --}}
                                    <div class="col-12 col-md-6" x-show="action === 'rejected'" x-cloak>
                                        <label for="rejection_reason_{{ $deposit->id }}" class="form-label small fw-bold text-danger mb-1">
                                            Alasan Penolakan <span class="text-danger">*</span>
                                        </label>
                                        <input type="text"
                                               id="rejection_reason_{{ $deposit->id }}"
                                               name="rejection_reason"
                                               class="form-control border-danger-subtle"
                                               :required="action === 'rejected'"
                                               placeholder="Misal: Sampah tercampur zat berbahaya / berat tidak sesuai"
                                               maxlength="1000">
                                    </div>

                                    {{-- Submit Button --}}
                                    <div class="col-12 col-md-2 d-grid">
                                        <button type="submit"
                                                class="btn fw-semibold d-flex align-items-center justify-content-center gap-2"
                                                :class="action === 'rejected' ? 'btn-danger' : 'btn-eco'"
                                                style="border-radius: 10px; height: 42px;">
                                            <i class="bi" :class="action === 'rejected' ? 'bi-x-octagon' : 'bi-check-lg'"></i>
                                            <span x-text="action === 'rejected' ? 'Tolak' : 'Simpan'"></span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endif

                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="d-flex justify-content-center mt-4">
            {{ $deposits->links() }}
        </div>
    @endif
</div>
