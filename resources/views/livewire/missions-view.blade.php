<div>
    <x-ui.page-header
        title="Misi Lingkungan"
        subtitle="Selesaikan misi harian dan mingguan untuk meraih bonus XP dan ECOPOINT."
    />

    {{-- Pengingat Keselamatan "Berburu Sampah" Sesuai Panduan Antislop --}}
    <div class="alert mb-4 border-warning-subtle d-flex align-items-start gap-3 p-3" style="background: #fffbeb; border-radius: 10px;" role="note">
        <i class="bi bi-shield-exclamation text-warning fs-4 mt-1 flex-shrink-0"></i>
        <div class="small" style="color: #92400e; line-height: 1.5;">
            <strong>Panduan Keselamatan Memilah & Berburu Sampah:</strong><br>
            Kumpulkan sampah hanya dari rumah, kantor, sekolah, atau lingkungan sekitar yang aman. Jangan mengambil sampah berbahaya, benda tajam yang terbuka, limbah medis, atau memungut sampah di area berisiko tinggi.
        </div>
    </div>

    {{-- Filter Misi --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div class="btn-group btn-group-sm" role="group" aria-label="Filter Misi">
            <button type="button" class="btn {{ $filter === 'all' ? 'btn-eco' : 'btn-outline-secondary' }}" wire:click="setFilter('all')">
                Semua Misi
            </button>
            <button type="button" class="btn {{ $filter === 'daily' ? 'btn-eco' : 'btn-outline-secondary' }}" wire:click="setFilter('daily')">
                Misi Harian
            </button>
            <button type="button" class="btn {{ $filter === 'weekly' ? 'btn-eco' : 'btn-outline-secondary' }}" wire:click="setFilter('weekly')">
                Misi Mingguan
            </button>
            <button type="button" class="btn {{ $filter === 'hunting' ? 'btn-eco' : 'btn-outline-secondary' }}" wire:click="setFilter('hunting')">
                Berburu Sampah
            </button>
        </div>

        <span class="small text-muted">
            <i class="bi bi-check-circle-fill text-success me-1"></i> {{ $completedCount }} misi telah diselesaikan
        </span>
    </div>

    {{-- Grid Misi --}}
    <div class="row g-3">
        @forelse($missions as $mission)
            <div class="col-12 col-md-6 col-lg-4">
                <x-ui.mission-card :mission="$mission" :userMission="$userMissions->get($mission->id)" />
            </div>
        @empty
            <div class="col-12">
                <x-ui.empty-state
                    title="Tidak ada misi pada kategori ini"
                    description="Pilih kategori lain untuk melihat daftar misi yang tersedia."
                />
            </div>
        @endforelse
    </div>
</div>
