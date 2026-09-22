@props([
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button',
    'icon' => null,
])

@php
    $baseClass = 'btn font-family-inherit d-inline-flex align-items-center justify-content-center gap-2 fw-semibold text-decoration-none transition-all';
    
    $sizeClasses = [
        'sm' => 'px-3 py-1 text-xs',
        'md' => 'px-3 py-2 text-sm',
        'lg' => 'px-4 py-3 text-base',
    ];

    $variantClasses = [
        'primary' => 'btn-eco shadow-sm',
        'secondary' => 'btn-outline-eco',
        'subtle' => 'btn-eco-subtle',
        'amber' => 'btn-amber',
        'danger' => 'btn-danger-subtle',
        'outline' => 'btn-outline-custom',
    ];

    $classes = $baseClass . ' ' . ($sizeClasses[$size] ?? $sizeClasses['md']) . ' ' . ($variantClasses[$variant] ?? $variantClasses['primary']);
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
    @if($icon)
        <i class="bi {{ $icon }}" aria-hidden="true"></i>
    @endif
    <span>{{ $slot }}</span>
</button>
