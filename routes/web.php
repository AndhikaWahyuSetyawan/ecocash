<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PartnerVerificationController;
use Illuminate\Support\Facades\Route;

// Public
Route::get('/', fn () => view('welcome'))->name('landing');

// Authenticated user routes
Route::middleware('auth')->group(function () {
    // Full-page Livewire components
    Route::get('/dashboard', \App\Livewire\Dashboard::class)->name('dashboard');
    Route::get('/scanner', \App\Livewire\WasteScanner::class)->name('scanner');
    Route::get('/setoran', \App\Livewire\DepositHistory::class)->name('setoran');
    Route::get('/setoran/{deposit}', \App\Livewire\DepositDetail::class)->name('setoran.detail');

    // Dompet & Penarikan Saldo Riil
    Route::get('/wallet', \App\Livewire\WalletView::class)->name('wallet');
    Route::get('/wallet/withdraw', \App\Livewire\WithdrawalFlow::class)->name('wallet.withdraw');

    // Gamifikasi & Misi
    Route::get('/reward', \App\Livewire\RewardCenter::class)->name('reward');
    Route::get('/missions', \App\Livewire\MissionsView::class)->name('missions');

    // Edukasi Lingkungan
    Route::get('/education', \App\Livewire\EducationList::class)->name('education');
    Route::get('/education/{slug}', \App\Livewire\EducationDetail::class)->name('education.detail');

    // Pusat Notifikasi In-App
    Route::get('/notifications', \App\Livewire\NotificationsCenter::class)->name('notifications');

    // Profile & Pengaturan Akun
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Protected image endpoint
    Route::get('/scan-image/{scan}', function (\App\Models\AiScan $scan) {
        abort_unless(
            $scan->user_id === auth()->id()
                || auth()->user()->isRole('admin')
                || auth()->user()->isRole('bank_partner'),
            403
        );
        return \Illuminate\Support\Facades\Storage::download($scan->image_path);
    })->name('scan-image');
});

// Partner routes
Route::middleware(['auth', 'role:bank_partner'])
    ->prefix('partner')
    ->name('partner.')
    ->group(function () {
        Route::get('/dashboard', \App\Livewire\PartnerDashboard::class)->name('dashboard');
        Route::patch('/deposits/{deposit}/verify', [PartnerVerificationController::class, 'update'])->name('deposits.verify');
    });

// Admin routes
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', \App\Livewire\AdminDashboard::class)->name('dashboard');
        Route::get('/withdrawals', \App\Livewire\AdminWithdrawals::class)->name('withdrawals');
        Route::get('/education', \App\Livewire\EducationList::class)->name('education');
    });

require __DIR__.'/auth.php';
