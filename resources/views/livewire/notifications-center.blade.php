<div>
    <x-ui.page-header
        title="Pusat Notifikasi"
        subtitle="Pantau pemberitahuan seputar setoran, saldo dompet, dan capaian misi kakak."
    >
        <x-slot:actions>
            <button type="button" wire:click="markAllAsRead" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-check2-all me-1"></i> Tandai Semua Terbaca
            </button>
        </x-slot:actions>
    </x-ui.page-header>

    <div class="eco-card p-3">
        @if($notifications->isEmpty())
            <x-ui.empty-state
                title="Belum ada notifikasi"
                description="Semua informasi terkait proses verifikasi setoran dan pencairan saldo akan muncul di sini."
                icon="bi-bell-slash"
            />
        @else
            <div class="list-group list-group-flush">
                @foreach($notifications as $n)
                    @php
                        $iconConfig = match($n->type) {
                            'deposit_verified', 'wallet_credit' => ['icon' => 'bi-check-circle-fill', 'color' => 'text-success'],
                            'deposit_rejected' => ['icon' => 'bi-x-circle-fill', 'color' => 'text-danger'],
                            'level_up', 'achievement_unlocked' => ['icon' => 'bi-trophy-fill', 'color' => 'text-warning'],
                            'withdrawal_submitted', 'withdrawal_status' => ['icon' => 'bi-cash-coin', 'color' => 'text-primary'],
                            default => ['icon' => 'bi-info-circle-fill', 'color' => 'text-secondary'],
                        };
                    @endphp
                    <div class="list-group-item px-3 py-3 border-bottom d-flex align-items-start justify-content-between gap-3 {{ !$n->is_read ? 'bg-light' : '' }}"
                         style="border-radius: 8px;">
                        <div class="d-flex align-items-start gap-3">
                            <i class="bi {{ $iconConfig['icon'] }} {{ $iconConfig['color'] }} fs-4 mt-1"></i>
                            <div>
                                <div class="d-flex align-items-center gap-2">
                                    <h3 class="h6 fw-bold mb-0" style="color: var(--ink);">{{ $n->title }}</h3>
                                    @if(!$n->is_read)
                                        <span class="badge bg-eco text-white" style="font-size: 0.65rem;">Baru</span>
                                    @endif
                                </div>
                                <p class="small text-muted mb-2 mt-1" style="line-height: 1.45;">{{ $n->message }}</p>
                                <span class="text-muted" style="font-size: 0.72rem;">{{ $n->created_at->diffForHumans() }}</span>
                            </div>
                        </div>

                        <div class="d-flex align-items-center gap-2">
                            @if($n->action_url)
                                <a href="{{ $n->action_url }}" class="btn btn-sm btn-outline-eco py-1 px-2" style="font-size: 0.75rem;">
                                    Lihat
                                </a>
                            @endif
                            @if(!$n->is_read)
                                <button type="button" wire:click="markAsRead({{ $n->id }})" class="btn btn-sm text-muted py-1 px-2" title="Tandai terbaca">
                                    <i class="bi bi-check"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-3">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</div>
