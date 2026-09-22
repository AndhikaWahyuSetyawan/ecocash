@props([
    'title',
    'description' => null,
    'icon' => 'bi-inbox',
    'actionLabel' => null,
    'actionUrl' => null,
    'actionWire' => null,
])

<div {{ $attributes->merge(['class' => 'eco-card p-5 text-center d-flex flex-column align-items-center justify-content-center']) }}>
    <div class="empty-state-icon mb-3">
        <i class="bi {{ $icon }} fs-1 text-muted"></i>
    </div>
    <h3 class="h6 fw-semibold mb-1" style="color: var(--ink);">{{ $title }}</h3>
    @if($description)
        <p class="text-muted small mb-4" style="max-width: 360px;">{{ $description }}</p>
    @endif

    @if($actionLabel && $actionUrl)
        <a href="{{ $actionUrl }}" class="btn btn-eco px-4 py-2">
            {{ $actionLabel }}
        </a>
    @elseif($actionLabel && $actionWire)
        <button type="button" wire:click="{{ $actionWire }}" class="btn btn-eco px-4 py-2">
            {{ $actionLabel }}
        </button>
    @endif
</div>
