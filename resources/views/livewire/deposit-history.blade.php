<div>
    <style>
        .history-card {
            border: 1px solid #dce8df;
            border-radius: 8px;
            background: #fff;
        }
        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 4px;
            font-size: 0.78rem;
            font-weight: 600;
            white-space: nowrap;
        }
        .status-pending_verification { background: #FEF3C7; color: #92400E; }
        .status-verified             { background: #D1FAE5; color: #065F46; }
        .status-rejected             { background: #FEE2E2; color: #991B1B; }
        .status-draft                { background: #F3F4F6; color: #374151; }

        .deposit-row {
            border-bottom: 1px solid #dce8df;
            padding: 14px 16px;
            cursor: pointer;
            transition: background 0.1s;
        }
        .deposit-row:last-child { border-bottom: none; }
        .deposit-row:hover { background: #F7FAF8; }

        .item-table th {
            font-size: 0.78rem;
            color: #6B7280;
            font-weight: 600;
            padding: 8px 10px;
            border-bottom: 1px solid #dce8df;
        }
        .item-table td {
            font-size: 0.85rem;
            color: #17211B;
            padding: 8px 10px;
            border-bottom: 1px solid #f0f4f1;
            vertical-align: middle;
        }
        .item-table tr:last-child td { border-bottom: none; }

        .filter-select {
            border: 1px solid #dce8df;
            border-radius: 6px;
            padding: 7px 12px;
            font-size: 0.88rem;
            background: #fff;
            color: #17211B;
        }
        .filter-select:focus {
            outline: none;
            border-color: #15803D;
            box-shadow: 0 0 0 3px rgba(21,128,61,0.15);
        }

        .ecopoint-text { color: #F59E0B; font-weight: 700; }

        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 48px 24px;
            color: #6B7280;
        }
    </style>

    <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
        <h1 class="h5 fw-bold mb-0" style="color:#17211B">Riwayat Setoran</h1>

        <div>
            <label for="status-filter" class="visually-hidden">Filter status</label>
            <select id="status-filter"
                class="filter-select"
                wire:model.live="statusFilter">
                <option value="">Semua status</option>
                <option value="draft">Draft</option>
                <option value="pending_verification">Menunggu verifikasi</option>
                <option value="verified">Terverifikasi</option>
                <option value="rejected">Ditolak</option>
            </select>
        </div>
    </div>

    <div class="history-card">
        @if ($deposits->isEmpty())
            <div class="empty-state">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#dce8df" stroke-width="1.5" class="mb-3" aria-hidden="true">
                    <rect x="3" y="3" width="18" height="18" rx="2"/>
                    <path d="M3 9h18"/>
                    <path d="M9 21V9"/>
                </svg>
                <p class="mb-0 fw-semibold" style="color:#374151">Belum ada setoran</p>
                @if ($statusFilter)
                    <p class="mt-1 mb-0" style="font-size:0.85rem">Tidak ada setoran dengan status ini.</p>
                @else
                    <p class="mt-1 mb-0" style="font-size:0.85rem">Mulai dengan menscan sampah Anda.</p>
                @endif
            </div>
        @else
            @foreach ($deposits as $deposit)
                <div class="deposit-row"
                    x-data="{ open: false }"
                    @keydown.enter="open = !open"
                    @keydown.space.prevent="open = !open"
                    tabindex="0"
                    role="button"
                    :aria-expanded="open"
                    aria-label="Setoran #{{ $deposit->id }}, {{ $deposit->partner?->name ?? 'Mitra tidak diketahui' }}">

                    {{-- Row summary --}}
                    <div class="d-flex align-items-start justify-content-between gap-2"
                        @click="open = !open">
                        <div>
                            <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                                <span class="fw-semibold" style="font-size:0.9rem;color:#17211B">
                                    Setoran #{{ $deposit->id }}
                                </span>
                                <span class="status-badge status-{{ $deposit->status }}">
                                    @switch($deposit->status)
                                        @case('pending_verification') Menunggu verifikasi @break
                                        @case('verified') Terverifikasi @break
                                        @case('rejected') Ditolak @break
                                        @case('draft') Draft @break
                                        @default {{ $deposit->status }}
                                    @endswitch
                                </span>
                            </div>

                            <p class="mb-0" style="font-size:0.82rem;color:#6B7280">
                                {{ $deposit->partner?->name ?? 'Mitra tidak diketahui' }}
                                @if ($deposit->submitted_at)
                                    &middot; {{ $deposit->submitted_at->translatedFormat('d M Y') }}
                                @endif
                                &middot; <a href="{{ route('setoran.detail', $deposit) }}" class="text-success fw-semibold text-decoration-none" @click.stop>Lihat Detail</a>
                            </p>

                            @if ($deposit->status === 'rejected' && $deposit->rejection_reason)
                                <p class="mt-1 mb-0" style="font-size:0.82rem;color:#991B1B">
                                    Alasan penolakan: {{ $deposit->rejection_reason }}
                                </p>
                            @endif
                        </div>

                        <div class="text-end flex-shrink-0">
                            @php
                                $totalEstimated = $deposit->items->sum('estimated_value');
                                $totalFinal     = $deposit->items->whereNotNull('final_value')->sum('final_value');
                                $totalWeight    = $deposit->items->sum('declared_weight');
                                $totalPoints    = $deposit->status === 'verified'
                                    ? $deposit->items->sum('final_ecopoint')
                                    : $deposit->items->sum('estimated_ecopoint');
                            @endphp
                            <p class="mb-0 fw-bold" style="font-size:0.95rem;color:#17211B">
                                @if ($deposit->status === 'verified' && $totalFinal > 0)
                                    Rp {{ number_format($totalFinal, 0, ',', '.') }}
                                @else
                                    ~Rp {{ number_format($totalEstimated, 0, ',', '.') }}
                                @endif
                            </p>
                            @if ($totalPoints > 0)
                                <p class="mb-0 ecopoint-text" style="font-size:0.8rem">
                                    {{ number_format($totalPoints, 0, ',', '.') }} pts
                                </p>
                            @endif
                            <p class="mb-0" style="font-size:0.78rem;color:#6B7280">
                                {{ number_format($totalWeight, 2, ',', '.') }} kg
                            </p>

                            <svg x-show="!open" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#6B7280" stroke-width="2" class="mt-1" aria-hidden="true">
                                <polyline points="6 9 12 15 18 9"/>
                            </svg>
                            <svg x-show="open" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#6B7280" stroke-width="2" class="mt-1" aria-hidden="true">
                                <polyline points="18 15 12 9 6 15"/>
                            </svg>
                        </div>
                    </div>

                    {{-- Expandable items --}}
                    <div x-show="open"
                        x-transition:enter="transition"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        class="mt-3">
                        @if ($deposit->items->isEmpty())
                            <p style="font-size:0.85rem;color:#6B7280;margin:0">Tidak ada item tercatat.</p>
                        @else
                            <div style="overflow-x:auto">
                                <table class="item-table w-100" style="border-collapse:collapse;min-width:520px" aria-label="Item setoran #{{ $deposit->id }}">
                                    <thead>
                                        <tr>
                                            <th>Kategori AI</th>
                                            <th>Kategori dicatat</th>
                                            <th>Berat</th>
                                            <th>Harga/kg</th>
                                            <th>Est. nilai</th>
                                            <th>Est. pts</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($deposit->items as $item)
                                            <tr>
                                                <td>{{ $item->ai_category ?? '-' }}</td>
                                                <td>{{ $item->verifiedCategory?->name ?? $item->aiCategory?->name ?? '-' }}</td>
                                                <td>{{ number_format($item->declared_weight, 2, ',', '.') }} kg</td>
                                                <td>
                                                    @if ($item->price_per_kg)
                                                        Rp {{ number_format($item->price_per_kg, 0, ',', '.') }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($item->final_value !== null)
                                                        Rp {{ number_format($item->final_value, 0, ',', '.') }}
                                                    @elseif ($item->estimated_value)
                                                        ~Rp {{ number_format($item->estimated_value, 0, ',', '.') }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>
                                                    @php $pts = $item->final_ecopoint ?? $item->estimated_ecopoint; @endphp
                                                    @if ($pts)
                                                        <span class="ecopoint-text">{{ number_format($pts, 0, ',', '.') }}</span>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            @if ($deposit->status === 'verified' && $deposit->verified_at)
                                <p class="mt-2 mb-0" style="font-size:0.78rem;color:#065F46">
                                    Diverifikasi {{ $deposit->verified_at->translatedFormat('d M Y') }}
                                </p>
                            @endif
                        @endif
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    {{-- Pagination --}}
    @if ($deposits->hasPages())
        <div class="mt-3 d-flex justify-content-center">
            {{ $deposits->links() }}
        </div>
    @endif
</div>
