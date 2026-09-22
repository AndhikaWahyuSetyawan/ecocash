<div>
    {{-- ===== Page header ===== --}}
    <div class="mb-4">
        <h1 class="h4 fw-bold mb-0" style="color:#17211b;">Admin Dashboard</h1>
        <p class="text-muted small mb-0">Ringkasan sistem ECOCASH.</p>
    </div>

    {{-- ===== System stats ===== --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4 col-lg-2-4">
            <div class="eco-card card p-3 h-100" style="border-radius:8px;border:1px solid #dce8df;">
                <p class="small text-muted mb-1">Total Pengguna</p>
                <p class="fs-3 fw-bold mb-0" style="color:#17211b;">{{ number_format($stats['total_users']) }}</p>
                <p class="small text-muted mb-0">akun aktif</p>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2-4">
            <div class="eco-card card p-3 h-100" style="border-radius:8px;border:1px solid #dce8df;">
                <p class="small text-muted mb-1">Total Setoran</p>
                <p class="fs-3 fw-bold mb-0" style="color:#17211b;">{{ number_format($stats['total_deposits']) }}</p>
                <p class="small text-muted mb-0">semua status</p>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2-4">
            <div class="eco-card card p-3 h-100" style="border-radius:8px;border:1px solid #fef9c3;">
                <p class="small text-muted mb-1">Menunggu Verifikasi</p>
                <p class="fs-3 fw-bold mb-0" style="color:#92400e;">{{ number_format($stats['pending_deposits']) }}</p>
                <p class="small text-muted mb-0">setoran</p>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2-4">
            <div class="eco-card card p-3 h-100" style="border-radius:8px;border:1px solid #dce8df;">
                <p class="small text-muted mb-1">Total Sampah</p>
                <p class="fs-3 fw-bold mb-0" style="color:#15803D;">
                    {{ number_format((float) $stats['total_kg'], 2) }}
                    <span class="fs-6 fw-normal">kg</span>
                </p>
                <p class="small text-muted mb-0">berat terverifikasi</p>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2-4">
            <div class="eco-card card p-3 h-100" style="border-radius:8px;border:1px solid #dce8df;">
                <p class="small text-muted mb-1">ECOPOINT Diterbitkan</p>
                <p class="fs-3 fw-bold mb-0" style="color:#F59E0B;">{{ number_format($stats['total_ecopoints_issued']) }}</p>
                <p class="small text-muted mb-0">total kredit</p>
            </div>
        </div>
    </div>

    {{-- ===== Recent deposits table ===== --}}
    <div class="eco-card card mb-4" style="border-radius:8px;border:1px solid #dce8df;">
        <div class="card-body p-3">
            <h2 class="h6 fw-semibold mb-3" style="color:#17211b;">10 Setoran Terbaru</h2>
            @if($recentDeposits->isEmpty())
                <p class="text-muted small mb-0">Belum ada setoran.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-sm mb-0" style="font-size:.875rem;">
                        <thead>
                            <tr style="color:#5a7060;">
                                <th scope="col" class="fw-normal">ID</th>
                                <th scope="col" class="fw-normal">Pengguna</th>
                                <th scope="col" class="fw-normal">Mitra</th>
                                <th scope="col" class="fw-normal">Status</th>
                                <th scope="col" class="fw-normal">Item</th>
                                <th scope="col" class="fw-normal">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentDeposits as $deposit)
                                <tr>
                                    <td class="text-muted">#{{ $deposit->id }}</td>
                                    <td>{{ $deposit->user?->name ?? '-' }}</td>
                                    <td>{{ $deposit->partner?->name ?? '-' }}</td>
                                    <td>
                                        @if($deposit->status === 'verified')
                                            <span class="badge" style="background:#dcfce7;color:#15803d;border-radius:4px;">Terverifikasi</span>
                                        @elseif($deposit->status === 'rejected')
                                            <span class="badge" style="background:#fee2e2;color:#991b1b;border-radius:4px;">Ditolak</span>
                                        @else
                                            <span class="badge" style="background:#fef9c3;color:#92400e;border-radius:4px;">Menunggu</span>
                                        @endif
                                    </td>
                                    <td>{{ $deposit->items->count() }}</td>
                                    <td>{{ $deposit->submitted_at?->format('d M Y') ?? $deposit->created_at->format('d M Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- ===== Category activity table ===== --}}
    <div class="eco-card card mb-4" style="border-radius:8px;border:1px solid #dce8df;">
        <div class="card-body p-3">
            <h2 class="h6 fw-semibold mb-3" style="color:#17211b;">Aktivitas per Kategori Sampah</h2>
            @if($categoryStats->isEmpty())
                <p class="text-muted small mb-0">Belum ada data kategori.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-sm mb-0" style="font-size:.875rem;">
                        <thead>
                            <tr style="color:#5a7060;">
                                <th scope="col" class="fw-normal">Kategori</th>
                                <th scope="col" class="fw-normal">Jumlah Item Setoran</th>
                                <th scope="col" class="fw-normal">Total Berat Deklarasi (kg)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categoryStats as $cat)
                                <tr>
                                    <td class="fw-medium" style="color:#17211b;">{{ $cat->name }}</td>
                                    <td>{{ number_format((int) $cat->total_items) }}</td>
                                    <td>{{ number_format((float) $cat->total_weight, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- ===== Admin sub-pages (coming soon) ===== --}}
    <div class="eco-card card" style="border-radius:8px;border:1px solid #dce8df;">
        <div class="card-body p-3">
            <h2 class="h6 fw-semibold mb-3" style="color:#17211b;">Halaman Manajemen</h2>
            <div class="row g-2">
                <div class="col-12 col-sm-6 col-md-4">
                    <a href="{{ route('admin.withdrawals') }}" class="p-3 h-100 d-block text-decoration-none border rounded-2 bg-light hover-bg-white transition-all">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span class="fw-bold small text-dark">Kelola Penarikan Saldo</span>
                            <i class="bi bi-arrow-right text-success"></i>
                        </div>
                        <p class="text-muted small mb-0" style="font-size:.78rem;">Verifikasi dan transfer pencairan dana pengguna.</p>
                    </a>
                </div>
                <div class="col-12 col-sm-6 col-md-4">
                    <a href="{{ route('admin.education') }}" class="p-3 h-100 d-block text-decoration-none border rounded-2 bg-light hover-bg-white transition-all">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span class="fw-bold small text-dark">Konten Edukasi</span>
                            <i class="bi bi-arrow-right text-success"></i>
                        </div>
                        <p class="text-muted small mb-0" style="font-size:.78rem;">Lihat dan kelola artikel panduan pilah sampah.</p>
                    </a>
                </div>
                @foreach([
                    ['label' => 'Manajemen Pengguna',    'desc' => 'Lihat, nonaktifkan, atau ubah role pengguna.'],
                    ['label' => 'Manajemen Mitra',        'desc' => 'Tambah dan kelola mitra bank sampah.'],
                    ['label' => 'Kategori Sampah',        'desc' => 'Atur kategori, harga, dan instruksi pilah.'],
                    ['label' => 'Konfigurasi AI',         'desc' => 'Kelola pemetaan kelas model AI.'],
                ] as $page)
                    <div class="col-12 col-sm-6 col-md-4">
                        <div class="p-3 h-100" style="border:1px solid #e2e8f0;border-radius:6px;background:#fafafa;">
                            <p class="fw-semibold small mb-1" style="color:#17211b;">{{ $page['label'] }}</p>
                            <p class="text-muted" style="font-size:.78rem;margin-bottom:.5rem;">{{ $page['desc'] }}</p>
                            <span class="badge" style="background:#f1f5f9;color:#94a3b8;border-radius:4px;font-size:.7rem;font-weight:400;">Segera hadir</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
