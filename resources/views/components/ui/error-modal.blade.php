@props([
    'title' => 'Terjadi Kesalahan',
    'message' => null,
    'actionLabel' => null,
    'actionWire' => null,
    'actionUrl' => null,
    'dismissWire' => null,
    'dismissLabel' => 'Tutup',
])

<div class="modal fade show d-block" 
     tabindex="-1" 
     role="dialog" 
     aria-modal="true" 
     style="background: rgba(15, 23, 42, 0.65); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); z-index: 1060;"
     x-data="{ show: true }" 
     x-show="show" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 transform scale-95"
     x-transition:enter-end="opacity-100 transform scale-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 transform scale-100"
     x-transition:leave-end="opacity-0 transform scale-95">
     
    <div class="modal-dialog modal-dialog-centered" style="max-width: 440px;">
        <div class="modal-content border-0 shadow-2xl overflow-hidden" 
             style="border-radius: 24px; background: #ffffff;">
             
            {{-- Top Decorative Glow Header --}}
            <div class="text-center pt-4 pb-2 px-4 position-relative" style="background: radial-gradient(circle at top, #fee2e2 0%, #ffffff 70%);">
                
                {{-- Close X Button --}}
                @if($dismissWire)
                    <button type="button" 
                            wire:click="{{ $dismissWire }}" 
                            class="btn-close position-absolute top-0 end-0 m-3 p-2 shadow-none" 
                            aria-label="Close"></button>
                @else
                    <button type="button" 
                            @click="show = false" 
                            class="btn-close position-absolute top-0 end-0 m-3 p-2 shadow-none" 
                            aria-label="Close"></button>
                @endif

                {{-- Animated SVG Error Illustration --}}
                <div class="animated-error-icon-wrap mx-auto mb-3">
                    <svg class="animated-error-svg" viewBox="0 0 100 100" width="88" height="88" fill="none" xmlns="http://www.w3.org/2000/svg">
                        {{-- Outer Soft Glow Pulse Circle --}}
                        <circle class="svg-pulse-ring" cx="50" cy="50" r="44" stroke="#fee2e2" stroke-width="4" />
                        
                        {{-- Shield or Circle Outer Outline --}}
                        <circle class="svg-circle-bg" cx="50" cy="50" r="38" fill="#fef2f2" stroke="#f87171" stroke-width="2.5" stroke-dasharray="240" stroke-dashoffset="0" />
                        
                        {{-- Warning Triangle Sign inside or Triangle Path --}}
                        <path class="svg-triangle" d="M50 26L74 68H26L50 26Z" fill="#fee2e2" stroke="#ef4444" stroke-width="2.5" stroke-linejoin="round" stroke-linecap="round" />
                        
                        {{-- Exclamation Mark Stem with Draw Animation --}}
                        <line class="svg-stem" x1="50" y1="40" x2="50" y2="54" stroke="#dc2626" stroke-width="3.5" stroke-linecap="round" />
                        
                        {{-- Exclamation Dot with Pop/Bounce Animation --}}
                        <circle class="svg-dot" cx="50" cy="61" r="2.2" fill="#dc2626" />
                    </svg>
                </div>

                {{-- Modal Title --}}
                <h3 class="h5 fw-bold mb-1" style="color: #991b1b; letter-spacing: -0.01em;">
                    {{ $title }}
                </h3>
            </div>

            {{-- Modal Body / Explanation --}}
            <div class="modal-body text-center px-4 py-2">
                <p class="text-secondary mb-0" style="font-size: 0.915rem; line-height: 1.55;">
                    {{ $message ?? $slot }}
                </p>
            </div>

            {{-- Modal Footer / Actions --}}
            <div class="modal-footer border-0 p-4 pt-3 d-flex flex-column flex-sm-row gap-2 justify-content-center">
                @if($actionLabel && $actionWire)
                    <button type="button" 
                            wire:click="{{ $actionWire }}" 
                            class="btn btn-eco flex-grow-1 py-2 px-3 fw-semibold d-inline-flex align-items-center justify-content-center gap-2"
                            style="border-radius: 12px; font-size: 0.9rem;">
                        <i class="bi bi-pencil-square"></i>
                        <span>{{ $actionLabel }}</span>
                    </button>
                @elseif($actionLabel && $actionUrl)
                    <a href="{{ $actionUrl }}" 
                       class="btn btn-eco flex-grow-1 py-2 px-3 fw-semibold d-inline-flex align-items-center justify-content-center gap-2"
                       style="border-radius: 12px; font-size: 0.9rem;">
                        <span>{{ $actionLabel }}</span>
                    </a>
                @endif

                @if($dismissWire)
                    <button type="button" 
                            wire:click="{{ $dismissWire }}" 
                            class="btn btn-light text-secondary border flex-grow-1 py-2 px-3 fw-semibold"
                            style="border-radius: 12px; font-size: 0.9rem;">
                        {{ $dismissLabel }}
                    </button>
                @else
                    <button type="button" 
                            @click="show = false" 
                            class="btn btn-light text-secondary border flex-grow-1 py-2 px-3 fw-semibold"
                            style="border-radius: 12px; font-size: 0.9rem;">
                        {{ $dismissLabel }}
                    </button>
                @endif
            </div>

        </div>
    </div>
</div>

{{-- SVG Microinteractions & Keyframe Animations --}}
<style>
    .animated-error-icon-wrap {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }
    .animated-error-svg {
        animation: error-subtle-shake 0.8s ease-in-out both;
    }
    
    /* Pulse ring on background */
    .svg-pulse-ring {
        transform-origin: center;
        animation: svg-pulse-glow 2s infinite ease-out;
    }
    
    /* Outer Circle draw */
    .svg-circle-bg {
        stroke-dasharray: 260;
        stroke-dashoffset: 260;
        animation: svg-draw-circle 0.7s cubic-bezier(0.65, 0, 0.45, 1) forwards 0.1s;
    }
    
    /* Warning Triangle pop & scale */
    .svg-triangle {
        transform-origin: 50px 50px;
        opacity: 0;
        animation: svg-pop-triangle 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) forwards 0.3s;
    }
    
    /* Exclamation mark stem line */
    .svg-stem {
        stroke-dasharray: 20;
        stroke-dashoffset: 20;
        animation: svg-draw-stem 0.4s ease-out forwards 0.6s;
    }
    
    /* Exclamation dot bounce */
    .svg-dot {
        transform-origin: 50px 61px;
        opacity: 0;
        animation: svg-bounce-dot 0.45s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards 0.8s;
    }

    @keyframes error-subtle-shake {
        0%, 100% { transform: translateX(0) scale(1); }
        15% { transform: translateX(-4px) rotate(-3deg); }
        30% { transform: translateX(4px) rotate(3deg); }
        45% { transform: translateX(-2px) rotate(-1.5deg); }
        60% { transform: translateX(2px) rotate(1.5deg); }
        75% { transform: translateX(0); }
    }

    @keyframes svg-pulse-glow {
        0% { transform: scale(0.9); opacity: 0.8; }
        50% { transform: scale(1.08); opacity: 0.2; }
        100% { transform: scale(1.15); opacity: 0; }
    }

    @keyframes svg-draw-circle {
        to { stroke-dashoffset: 0; }
    }

    @keyframes svg-pop-triangle {
        0% { opacity: 0; transform: scale(0.6); }
        100% { opacity: 1; transform: scale(1); }
    }

    @keyframes svg-draw-stem {
        to { stroke-dashoffset: 0; }
    }

    @keyframes svg-bounce-dot {
        0% { opacity: 0; transform: scale(0); }
        60% { opacity: 1; transform: scale(1.35); }
        100% { opacity: 1; transform: scale(1); }
    }
</style>
