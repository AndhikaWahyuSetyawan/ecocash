@props([
    'status' => 'pending',
])

@php
    $config = match($status) {
        'verified', 'completed', 'paid' => [
            'class' => 'badge-verified',
            'label' => 'Terverifikasi',
            'icon' => 'bi-check-circle-fill',
        ],
        'pending_verification', 'pending' => [
            'class' => 'badge-pending',
            'label' => 'Menunggu Verifikasi',
            'icon' => 'bi-clock-fill',
        ],
        'processing' => [
            'class' => 'badge-pending',
            'label' => 'Sedang Diproses',
            'icon' => 'bi-arrow-repeat',
        ],
        'rejected' => [
            'class' => 'badge-rejected',
            'label' => 'Ditolak',
            'icon' => 'bi-x-circle-fill',
        ],
        'draft' => [
            'class' => 'badge-draft',
            'label' => 'Draft',
            'icon' => 'bi-pencil-fill',
        ],
        default => [
            'class' => 'badge-draft',
            'label' => ucfirst($status),
            'icon' => 'bi-circle-fill',
        ],
    };
@endphp

<span class="{{ $config['class'] }} d-inline-flex align-items-center gap-1">
    <i class="bi {{ $config['icon'] }}" style="font-size: 0.75rem;"></i>
    <span>{{ $config['label'] }}</span>
</span>
