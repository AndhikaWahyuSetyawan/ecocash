<div>
    <x-ui.page-header
        title="Detail Setoran #{{ $deposit->id }}"
        subtitle="Diajukan pada {{ $deposit->submitted_at?->translatedFormat('d F Y, H:i') ?? $deposit->created_at->translatedFormat('d F Y, H:i') }}"
    >
        <x-slot:actions>
            <a href="{{ route('setoran') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Riwayat
            </a>
        </x-slot:actions>
    </x-ui.page-header>

    {{-- Status Timeline Card --}}
    <div class="eco-card p-4 mb-4">
        <h2 class="h6 fw-bold mb-3" style="color: var(--ink);">Status Verifikasi</h2>
        
        <div class="d-flex align-items-center justify-content-between position-relative py-2">
            {{-- Step 1: Diajukan --}}
            <div class="text-center position-relative z-1 flex-grow-1">
                <div class="rounded-circle bg-success text-white d-inline-flex align-items-center justify-content-center mb-1" style="width: 32px; height: 32px;">
                    <i class="bi bi-check-lg"></i>
                </div>
                <div class="small fw-bold" style="font-size: 0.8rem;">Setoran Dikirim</div>
                <div class="text-muted" style="font-size: 0.72rem;">{{ $deposit->created_at->format('d M, H:i') }}</div>
            </div>

            {{-- Step 2: Menunggu / Diverifikasi --}}
            <div class="text-center position-relative z-1 flex-grow-1">
                @if($deposit->status === 'verified')
                    <div class="rounded-circle bg-success text-white d-inline-flex align-items-center justify-content-center mb-1" style="width: 32px; height: 32px;">
                        <i class="bi bi-patch-check-fill"></i>
                    </div>
                    <div class="small fw-bold text-success" style="font-size: 0.8rem;">Terverifikasi</div>
                    <div class="text-muted" style="font-size: 0.72rem;">{{ $deposit->verified_at?->format('d M, H:i') }}</div>
                @elseif($deposit->status === 'rejected')
                    <div class="rounded-circle bg-danger text-white d-inline-flex align-items-center justify-content-center mb-1" style="width: 32px; height: 32px;">
                        <i class="bi bi-x-lg"></i>
                    </div>
                    <div class="small fw-bold text-danger" style="font-size: 0.8rem;">Ditolak Mitra</div>
                    <div class="text-muted" style="font-size: 0.72rem;">{{ $deposit->verified_at?->format('d M, H:i') }}</div>
                @else
                    <div class="rounded-circle bg-warning text-white d-inline-flex align-items-center justify-content-center mb-1" style="width: 32px; height: 32px;">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div class="small fw-bold text-warning-emphasis" style="font-size: 0.8rem;">Menunggu Pemeriksaan</div>
                    <div class="text-muted" style="font-size: 0.72rem;">Sedang diproses</div>
                @endif
            </div>

            {{-- Step 3: Saldo & Reward Masuk --}}
            <div class="text-center position-relative z-1 flex-grow-1">
                @if($deposit->status === 'verified')
                    <div class="rounded-circle bg-success text-white d-inline-flex align-items-center justify-content-center mb-1" style="width: 32px; height: 32px;">
                        <i class="bi bi-wallet2"></i>
                    </div>
                    <div class="small fw-bold text-success" style="font-size: 0.8rem;">Saldo Masuk</div>
                    <div class="text-muted" style="font-size: 0.72rem;">Siap ditarik</div>
                @else
                    <div class="rounded-circle bg-light border text-muted d-inline-flex align-items-center justify-content-center mb-1" style="width: 32px; height: 32px;">
                        <i class="bi bi-wallet2"></i>
                    </div>
                    <div class="small text-muted" style="font-size: 0.8rem;">Kredit Saldo</div>
                    <div class="text-muted" style="font-size: 0.72rem;">Setelah verifikasi</div>
                @endif
            </div>
        </div>

        @if($deposit->status === 'rejected' && $deposit->rejection_reason)
            <div class="alert alert-danger mb-0 mt-3 d-flex align-items-start gap-2" role="alert">
                <i class="bi bi-exclamation-triangle-fill fs-5 mt-1"></i>
                <div>
                    <div class="fw-bold">Alasan Penolakan:</div>
                    <p class="mb-0 small">{{ $deposit->rejection_reason }}</p>
                </div>
            </div>
        @endif
    </div>

    {{-- Detail Barang / Item Sampah --}}
    <div class="row g-4">
        <div class="col-12 col-lg-8">
            <div class="eco-card p-4 mb-4">
                <h2 class="h6 fw-bold mb-3" style="color: var(--ink);">Rincian Material Sampah</h2>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr style="font-size: 0.78rem; color: var(--ink-muted);">
                                <th>KATEGORI</th>
                                <th>BERAT DEKLARASI</th>
                                <th>BERAT AKTUAL</th>
                                <th>HARGA/KG</th>
                                <th class="text-end">NILAI TOTAL</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($deposit->items as $item)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $item->category?->name ?? 'Material' }}</div>
                                        @if($item->ai_category)
                                            <span class="small text-muted" style="font-size: 0.75rem;">AI: {{ $item->ai_category }}</span>
                                        @endif
                                    </td>
                                    <td>{{ number_format($item->declared_weight, 2) }} kg</td>
                                    <td>
                                        @if($item->verified_weight !== null)
                                            <span class="fw-bold text-success">{{ number_format($item->verified_weight, 2) }} kg</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>Rp{{ number_format($item->price_per_kg, 0, ',', '.') }}</td>
                                    <td class="text-end">
                                        @if($deposit->status === 'verified' && $item->final_value !== null)
                                            <span class="fw-bold text-success fs-6">Rp{{ number_format($item->final_value, 0, ',', '.') }}</span>
                                        @else
                                            <span class="text-muted">~Rp{{ number_format($item->estimated_value, 0, ',', '.') }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Ringkasan Mitra & Nilai --}}
        <div class="col-12 col-lg-4">
            <div class="eco-card p-4">
                <h2 class="h6 fw-bold mb-3" style="color: var(--ink);">Informasi Mitra</h2>
                <div class="mb-3">
                    <span class="text-muted small d-block">Bank Sampah Tujuan:</span>
                    <span class="fw-bold" style="color: var(--ink);">{{ $deposit->partner?->name ?? 'Mitra Bank Sampah' }}</span>
                    @if($deposit->partner?->address)
                        <p class="small text-muted mb-0 mt-1">{{ $deposit->partner->address }}</p>
                    @endif
                </div>

                <div class="border-top pt-3 mt-3">
                    <span class="text-muted small d-block">Total Nilai Finansial:</span>
                    @php
                        $totVal = $deposit->status === 'verified'
                            ? $deposit->items->sum('final_value')
                            : $deposit->items->sum('estimated_value');
                        $totPts = $deposit->status === 'verified'
                            ? $deposit->items->sum('final_ecopoint')
                            : $deposit->items->sum('estimated_ecopoint');
                    @endphp
                    <div class="fs-4 fw-bold {{ $deposit->status === 'verified' ? 'text-success' : 'text-muted' }}">
                        Rp{{ number_format($totVal, 0, ',', '.') }}
                    </div>
                    <div class="small fw-semibold text-warning-emphasis mt-1">
                        +{{ number_format($totPts) }} ECOPOINT
                    </div>
                </div>

                @if($deposit->status === 'verified')
                    <div class="mt-4 pt-2">
                        <a href="{{ route('wallet') }}" class="btn btn-eco w-100 py-2">
                            <i class="bi bi-wallet2 me-1"></i> Periksa Saldo Dompet
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
