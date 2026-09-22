@props([
    'mission',
    'userMission' => null,
])

@php
    $progress = $userMission?->current_progress ?? 0;
    $target = $mission->target_count;
    $pct = min(100, (int) round(($progress / max(1, $target)) * 100));
    $isCompleted = $userMission?->is_completed ?? false;
@endphp

<div class="eco-card p-3 mb-3 d-flex flex-column justify-content-between h-100 {{ $isCompleted ? 'border-success-subtle bg-success-subtle-10' : '' }}">
    <div>
        <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
            <span class="badge {{ $mission->type === 'hunting' ? 'bg-warning-subtle text-warning-emphasis' : 'bg-success-subtle text-success-emphasis' }} px-2 py-1 rounded-2" style="font-size: 0.72rem; font-weight: 600;">
                {{ $mission->type === 'hunting' ? 'Berburu Sampah' : ($mission->type === 'daily' ? 'Misi Harian' : 'Misi Mingguan') }}
            </span>
            @if($isCompleted)
                <span class="badge bg-success text-white px-2 py-1 rounded-pill" style="font-size: 0.72rem;">
                    <i class="bi bi-check-circle-fill me-1"></i> Selesai
                </span>
            @else
                <span class="text-muted small fw-semibold">
                    {{ $progress }} / {{ $target }}
                </span>
            @endif
        </div>

        <h3 class="h6 fw-bold mb-1" style="color: var(--ink);">
            {{ $mission->title }}
        </h3>
        <p class="text-muted small mb-3" style="font-size: 0.82rem; line-height: 1.45;">
            {{ $mission->description }}
        </p>
    </div>

    <div>
        {{-- Progress bar --}}
        <div class="progress mb-2" style="height: 6px; background-color: #e2e8f0; border-radius: 4px;">
            <div class="progress-bar {{ $isCompleted ? 'bg-success' : 'bg-eco' }}" role="progressbar" style="width: {{ $pct }}%;" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="{{ $target }}"></div>
        </div>

        {{-- Reward Preview --}}
        <div class="d-flex align-items-center justify-content-between pt-2 border-top border-light-subtle">
            <div class="d-flex align-items-center gap-2">
                <span class="small fw-bold text-success" style="font-size: 0.8rem;">
                    +{{ $mission->reward_xp }} XP
                </span>
                @if($mission->reward_ecopoint > 0)
                    <span class="small fw-bold text-warning-emphasis" style="font-size: 0.8rem;">
                        +{{ $mission->reward_ecopoint }} Poin
                    </span>
                @endif
            </div>

            @if(!$isCompleted)
                <a href="{{ route('scanner') }}" class="btn btn-sm btn-outline-eco py-1 px-2" style="font-size: 0.75rem;">
                    Jalankan
                </a>
            @endif
        </div>
    </div>
</div>
