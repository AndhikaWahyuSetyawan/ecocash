@props([
    'title',
    'subtitle' => null,
    'badge' => null,
])

<div class="d-flex align-items-sm-center justify-content-between flex-column flex-sm-row gap-2 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2">
            <h1 class="h4 fw-bold mb-0" style="color: var(--ink); letter-spacing: -0.02em;">
                {{ $title }}
            </h1>
            @if($badge)
                {{ $badge }}
            @endif
        </div>
        @if($subtitle)
            <p class="text-muted small mb-0 mt-1">
                {{ $subtitle }}
            </p>
        @endif
    </div>
    @if(isset($actions))
        <div class="d-flex align-items-center gap-2 mt-2 mt-sm-0">
            {{ $actions }}
        </div>
    @endif
</div>
