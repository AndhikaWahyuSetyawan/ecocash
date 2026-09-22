<x-app-layout>
    {{-- ===== Scoped Styles for Profile Page ===== --}}
    <style>
        .profile-hero-card {
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--shadow-subtle);
            position: relative;
        }
        .profile-banner-pattern {
            height: 100px;
            background: linear-gradient(135deg, #15803d 0%, #047857 50%, #064e3b 100%);
            position: relative;
        }
        .profile-banner-pattern::after {
            content: '';
            position: absolute;
            inset: 0;
            opacity: 0.1;
            background-image: radial-gradient(#ffffff 1px, transparent 1px);
            background-size: 16px 16px;
        }
        .profile-avatar-giant {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #15803d 0%, #166534 100%);
            color: #ffffff;
            font-size: 2rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 4px solid #ffffff;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.12);
            margin-top: -40px;
            position: relative;
            z-index: 2;
        }
        .profile-stat-item {
            background: #f8faf9;
            border: 1px solid #edf2ef;
            border-radius: 14px;
            padding: 0.85rem 1rem;
            transition: all 0.15s ease;
        }
        .profile-stat-item:hover {
            background: #f0fdf4;
            border-color: #bbf7d0;
        }
        .profile-quick-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem 1rem;
            border-radius: 12px;
            color: var(--ink);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.15s ease;
        }
        .profile-quick-link:hover {
            background: #f0fdf4;
            color: var(--eco);
            transform: translateX(3px);
        }
        .profile-quick-link i.icon-left {
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            background: #eef3f0;
            color: #475569;
            transition: all 0.15s ease;
        }
        .profile-quick-link:hover i.icon-left {
            background: var(--eco-pale);
            color: var(--eco);
        }
    </style>

    <div class="container-fluid px-0">
        {{-- Page Header --}}
        <x-ui.page-header
            title="Profil & Pengaturan Akun"
            subtitle="Kelola informasi pribadi, kontak, preferensi keamanan, dan data keanggotaan EcoCash kamu.">
            <x-slot:badge>
                <span class="badge bg-eco text-white px-2 py-1 rounded-pill" style="font-size: 0.72rem; font-weight: 600;">
                    <i class="bi bi-person-check me-1"></i>Akun Terdaftar
                </span>
            </x-slot:badge>
        </x-ui.page-header>

        {{-- Alerts status --}}
        @if(session('status') === 'profile-updated')
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-4" role="alert" style="border-radius: 12px; border: 1px solid #bbf7d0; background: #f0fdf4; color: #166534;">
                <i class="bi bi-check-circle-fill fs-5 text-success"></i>
                <div class="small fw-semibold">Informasi profil kamu berhasil diperbarui!</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        @endif

        @if(session('status') === 'password-updated')
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-4" role="alert" style="border-radius: 12px; border: 1px solid #bbf7d0; background: #f0fdf4; color: #166534;">
                <i class="bi bi-check-circle-fill fs-5 text-success"></i>
                <div class="small fw-semibold">Kata sandi akun kamu telah berhasil diperbarui dan diamankan.</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        @endif

        {{-- Main Two-Column Layout --}}
        <div class="row g-4">
            {{-- Left Column: User Profile Summary Card & Quick Navigation --}}
            <div class="col-12 col-lg-4 col-xl-4">
                <div class="sticky-top" style="top: 80px; z-index: 10;">
                    {{-- Hero Card --}}
                    <div class="profile-hero-card mb-4">
                        <div class="profile-banner-pattern"></div>
                        <div class="px-4 pb-4 text-center">
                            <div class="d-flex justify-content-center">
                                <div class="profile-avatar-giant">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                            </div>
                            <h2 class="h5 fw-bold mt-2 mb-0" style="color: var(--ink);">
                                {{ auth()->user()->name }}
                            </h2>
                            <p class="text-muted small mb-2">{{ auth()->user()->email }}</p>
                            
                            <div class="d-inline-flex align-items-center gap-1 badge bg-eco text-white px-3 py-1 rounded-pill mb-3" style="font-size: 0.78rem;">
                                <i class="bi bi-patch-check-fill"></i>
                                <span>{{ auth()->user()->current_level?->name ?? 'Pemilah Pemula' }}</span>
                            </div>

                            <hr class="my-2" style="border-color: #f1f5f9;">

                            {{-- Financial & Gamification Stats --}}
                            <div class="row g-2 text-start mt-1">
                                <div class="col-6">
                                    <div class="profile-stat-item">
                                        <div class="text-muted small" style="font-size: 0.74rem;">Saldo Dompet</div>
                                        <div class="fw-bold text-dark mt-0.5" style="font-size: 0.95rem;">
                                            Rp{{ number_format(auth()->user()->available_balance ?? 0, 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="profile-stat-item">
                                        <div class="text-muted small" style="font-size: 0.74rem;">Poin Reward</div>
                                        <div class="fw-bold text-success mt-0.5" style="font-size: 0.95rem;">
                                            {{ number_format(auth()->user()->ecopoint_balance ?? 0) }} Pts
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3 text-start small text-muted d-flex align-items-center gap-2" style="font-size: 0.78rem;">
                                <i class="bi bi-clock-history"></i>
                                <span>Bergabung sejak {{ auth()->user()->created_at->translatedFormat('F Y') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Quick Navigation & Actions --}}
                    <div class="eco-card p-3 mb-4">
                        <div class="px-2 py-1 text-muted small fw-bold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.05em;">
                            Menu Terkait
                        </div>
                        <nav class="d-flex flex-column gap-1 mt-1">
                            <a href="{{ route('wallet') }}" class="profile-quick-link">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-wallet2 icon-left"></i>
                                    <span>Dompet & Saldo</span>
                                </div>
                                <i class="bi bi-chevron-right text-muted small"></i>
                            </a>
                            <a href="{{ route('reward') }}" class="profile-quick-link">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-gift icon-left"></i>
                                    <span>Katalog Reward</span>
                                </div>
                                <i class="bi bi-chevron-right text-muted small"></i>
                            </a>
                            <a href="{{ route('notifications') }}" class="profile-quick-link">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-bell icon-left"></i>
                                    <span>Notifikasi Saya</span>
                                </div>
                                <i class="bi bi-chevron-right text-muted small"></i>
                            </a>
                        </nav>
                    </div>

                    {{-- Logout Card di Sidebar --}}
                    <div class="eco-card p-3 border text-center" style="background: #fafcfb;">
                        <div class="small text-muted mb-2">Ingin mengakhiri sesi masuk?</div>
                        <form method="POST" action="{{ route('logout') }}" class="m-0">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm w-100 d-inline-flex align-items-center justify-content-center gap-2 py-2 fw-semibold" style="border-radius: 8px;">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Keluar dari Akun</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Right Column: Forms & Settings --}}
            <div class="col-12 col-lg-8 col-xl-8">
                <div class="d-flex flex-column gap-4">
                    {{-- 1. Update Profile Information --}}
                    <div class="eco-card p-4 p-md-4 shadow-sm">
                        @include('profile.partials.update-profile-information-form')
                    </div>

                    {{-- 2. Update Password --}}
                    <div class="eco-card p-4 p-md-4 shadow-sm">
                        @include('profile.partials.update-password-form')
                    </div>

                    {{-- 3. Danger Zone: Delete Account --}}
                    <div class="eco-card p-4 p-md-4 shadow-sm" style="border-color: #fee2e2; background: #fffcfc;">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

