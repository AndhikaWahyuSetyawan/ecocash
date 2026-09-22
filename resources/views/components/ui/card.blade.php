@props([
    'variant' => 'default',
    'padding' => 'p-4',
])

@php
    $variantStyle = match($variant) {
        'eco' => 'border-color: var(--border-eco); background: var(--white);',
        'subtle-green' => 'border-color: #bbf7d0; background: #f0fdf4;',
        'subtle-amber' => 'border-color: #fef08a; background: #fefce8;',
        'flat' => 'border: none; background: var(--white); box-shadow: 0 1px 3px rgba(0,0,0,0.05);',
        default => 'border: 1px solid var(--border); background: var(--white);',
    };
@endphp

<div {{ $attributes->merge(['class' => 'eco-card ' . $padding]) }} style="{{ $variantStyle }}">
    {{ $slot }}
</div>
