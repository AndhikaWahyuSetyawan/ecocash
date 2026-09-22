<div style="max-width: 800px; margin: 0 auto;">
    <div class="mb-3">
        <a href="{{ route('education') }}" class="text-decoration-none text-muted small">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Panduan
        </a>
    </div>

    @if($hasEarnedXp)
        <div class="alert alert-success d-flex align-items-center gap-2 mb-4" role="alert">
            <i class="bi bi-check-circle-fill"></i>
            <div>Selamat! Kakak mendapatkan <strong>+30 XP</strong> karena telah membaca artikel edukasi ini.</div>
        </div>
    @endif

    <div class="eco-card p-4 p-md-5 mb-4">
        <div class="mb-3">
            <span class="badge bg-success-subtle text-success px-2 py-1 rounded-2" style="font-size: 0.75rem;">
                {{ ucfirst(str_replace('_', ' ', $content->category)) }}
            </span>
            <span class="text-muted small ms-2">{{ $content->created_at->translatedFormat('d F Y') }}</span>
        </div>

        <h1 class="h3 fw-bold mb-3" style="color: var(--ink); line-height: 1.35; letter-spacing: -0.02em;">
            {{ $content->title }}
        </h1>

        @if($content->summary)
            <div class="lead text-muted fs-6 mb-4 pb-3 border-bottom" style="line-height: 1.6;">
                {{ $content->summary }}
            </div>
        @endif

        <div class="article-body" style="line-height: 1.75; color: var(--ink); font-size: 1rem;">
            {!! $content->body !!}
        </div>
    </div>

    {{-- Panduan Terkait --}}
    @if($relatedArticles->isNotEmpty())
        <div class="mt-4">
            <h2 class="h6 fw-bold mb-3" style="color: var(--ink);">Artikel Terkait Lainnya</h2>
            <div class="row g-3">
                @foreach($relatedArticles as $rel)
                    <div class="col-12 col-md-4">
                        <div class="eco-card p-3 h-100 d-flex flex-column justify-content-between">
                            <div>
                                <h3 class="fw-semibold mb-2" style="font-size: 0.875rem; color: var(--ink);">{{ $rel->title }}</h3>
                                <p class="small text-muted mb-3" style="font-size: 0.78rem; line-height: 1.4;">{{ Str::limit($rel->summary, 80) }}</p>
                            </div>
                            <a href="{{ route('education.detail', $rel->slug) }}" class="small text-eco fw-semibold text-decoration-none">
                                Baca Panduan <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
