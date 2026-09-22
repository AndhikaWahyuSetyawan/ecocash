@props([
    'title',
    'value',
    'unit' => null,
    'subtitle' => null,
    'icon' => null,
    'color' => 'eco', // 'eco', 'amber', 'ink', 'danger'
])

@php
    $colorClass = match($color) {
        'amber' => 'color: var(--amber);',
        'danger' => 'color: var(--danger);',
        'ink' => 'color: var(--ink);',
        default => 'color: var(--eco);',
    };
@endphp

<div {{ $attributes->merge(['class' => 'eco-card p-3 p-md-4 h-100 d-flex flex-column justify-content-between']) }}>
    <div class="d-flex align-items-center justify-content-between mb-2">
        <span class="text-muted small fw-medium">{{ $title }}</span>
        @if($icon)
            <div class="stat-icon-wrap" style="{{ $colorClass }}">
                <i class="bi {{ $icon }} fs-5"></i>
            </div>
        @endif
    </div>
    <div>
        <div class="d-flex align-items-baseline gap-1">
            <span class="fs-2 fw-bold" style="{{ $colorClass }}; letter-spacing: -0.02em;">
                {{ $value }}
            </span>
            @if($unit)
                <span class="small fw-semibold text-muted">{{ $unit }}</span>
            @endif
        </div>
        @if($subtitle)
            <p class="text-muted small mb-0 mt-1" style="font-size: 0.8rem;">
                {{ $subtitle }}
            </p>
        @endif
    </div>
</div>
