<div>
    {{-- ===== Page header ===== --}}
    <div class="mb-4">
        <h1 class="h4 fw-bold mb-0" style="color:#17211b;">Edukasi Lingkungan</h1>
        <p class="text-muted small mb-0">Panduan pilah sampah, informasi daur ulang, dan tips hidup hijau.</p>
    </div>

    {{-- ===== Search & category filters ===== --}}
    <div class="mb-4">
        <div class="mb-3">
            <label for="edu-search" class="visually-hidden">Cari artikel</label>
            <input
                id="edu-search"
                type="search"
                class="form-control"
                placeholder="Cari artikel..."
                wire:model.live.debounce.300ms="search"
                style="max-width:420px;border-radius:8px;"
                autocomplete="off"
            >
        </div>

        <div class="d-flex flex-wrap gap-2" role="tablist" aria-label="Filter kategori">
            <button
                type="button"
                class="btn btn-sm {{ $category === '' ? 'btn-eco' : 'btn-outline-secondary' }}"
                wire:click="$set('category', '')"
                style="border-radius:20px;"
                role="tab"
                aria-selected="{{ $category === '' ? 'true' : 'false' }}"
            >
                Semua
            </button>
            @foreach($categories as $key => $label)
                <button
                    type="button"
                    class="btn btn-sm {{ $category === $key ? 'btn-eco' : 'btn-outline-secondary' }}"
                    wire:click="$set('category', '{{ $key }}')"
                    style="border-radius:20px;"
                    role="tab"
                    aria-selected="{{ $category === $key ? 'true' : 'false' }}"
                >
                    {{ $label }}
                </button>
            @endforeach
        </div>
    </div>

    {{-- ===== Article grid ===== --}}
    @if($contents->isEmpty())
        <div class="card p-5 text-center" style="border-radius:8px;">
            <p class="text-muted mb-1 fw-semibold">Tidak ada artikel ditemukan.</p>
            <p class="text-muted small mb-0">
                @if($search)
                    Coba kata kunci lain atau hapus filter kategori.
                @else
                    Belum ada konten yang dipublikasikan di kategori ini.
                @endif
            </p>
        </div>
    @else
        <div class="row g-3 mb-4">
            @foreach($contents as $content)
                @php
                    $badgeStyle = match($content->category) {
                        'sorting_guide' => 'background:#dcfce7;color:#15803d;',
                        'waste_info'    => 'background:#dbeafe;color:#1d4ed8;',
                        'tips'          => 'background:#fef9c3;color:#92400e;',
                        'news'          => 'background:#f3e8ff;color:#7e22ce;',
                        default         => 'background:#f1f5f9;color:#475569;',
                    };
                    $categoryLabel = $categories[$content->category] ?? $content->category;
                @endphp
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="eco-card h-100 d-flex flex-column p-3" style="border-radius:8px;border:1px solid #dce8df;">
                        {{-- Category badge --}}
                        <div class="mb-2">
                            <span class="badge" style="{{ $badgeStyle }}border-radius:4px;font-weight:500;font-size:.75rem;">
                                {{ $categoryLabel }}
                            </span>
                        </div>

                        {{-- Title --}}
                        <h2 class="h6 fw-semibold mb-1 flex-grow-1" style="color:#17211b;line-height:1.4;">
                            {{ $content->title }}
                        </h2>

                        {{-- Summary --}}
                        @if($content->summary)
                            <p class="text-muted small mb-3" style="line-height:1.5;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;">
                                {{ $content->summary }}
                            </p>
                        @else
                            <div class="mb-3"></div>
                        @endif

                        {{-- Footer: date + link baca artikel --}}
                        <div class="d-flex align-items-center justify-content-between mt-auto pt-2" style="border-top:1px solid #eef2ee;">
                            <span class="text-muted" style="font-size:.75rem;">
                                {{ $content->created_at->format('d M Y') }}
                            </span>
                            <a href="{{ route('education.detail', $content->slug) }}"
                               class="small fw-semibold text-eco text-decoration-none"
                            >
                                Baca Panduan <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- ===== Pagination ===== --}}
        <div class="d-flex justify-content-center">
            {{ $contents->links() }}
        </div>
    @endif
</div>
