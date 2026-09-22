<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1. Perluasan tabel users
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->boolean('has_completed_onboarding')->default(false)->after('role');
            $table->unsignedInteger('streak_days')->default(0)->after('has_completed_onboarding');
            $table->date('last_activity_date')->nullable()->after('streak_days');
        });

        // 2. Sistem Dompet (Wallets & Ledger)
        Schema::create('wallet_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('balance')->default(0); // in IDR (integer)
            $table->unsignedBigInteger('pending_balance')->default(0); // in IDR (pending deposits)
            $table->timestamps();
        });

        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type'); // 'credit_deposit', 'debit_withdrawal', 'refund', 'adjustment', 'reversal'
            $table->bigInteger('amount'); // positive for credit, negative for debit
            $table->string('reference_type')->nullable(); // e.g. 'App\Models\Deposit', 'App\Models\WithdrawalRequest'
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->string('description');
            $table->unsignedBigInteger('balance_after');
            $table->string('status')->default('completed'); // 'completed', 'pending', 'reversed'
            $table->timestamps();
            $table->index(['user_id', 'created_at']);
        });

        // 3. Metode & Permintaan Penarikan Saldo (Withdrawals)
        Schema::create('withdrawal_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. 'BCA', 'Bank Mandiri', 'GoPay', 'DANA', 'OVO'
            $table->string('type'); // 'bank' | 'ewallet'
            $table->string('code')->unique();
            $table->unsignedBigInteger('min_amount')->default(10000);
            $table->unsignedBigInteger('fee')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('withdrawal_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('withdrawal_method_id')->constrained()->cascadeOnDelete();
            $table->string('account_name');
            $table->string('account_number');
            $table->unsignedBigInteger('amount');
            $table->unsignedBigInteger('fee')->default(0);
            $table->unsignedBigInteger('net_amount');
            $table->string('status')->default('pending'); // 'pending', 'processing', 'paid', 'rejected', 'cancelled'
            $table->text('rejection_reason')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index(['user_id', 'status']);
        });

        // 4. Gamifikasi: Level & XP
        Schema::create('levels', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. 'Pemilah Pemula', 'Pemilah Aktif', 'Penjaga Lingkungan', dll.
            $table->text('description')->nullable();
            $table->unsignedInteger('minimum_xp');
            $table->string('badge_icon')->default('bi-award');
            $table->unsignedInteger('level_order')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('xp_ledgers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->integer('amount');
            $table->integer('balance_after');
            $table->string('source_type'); // 'deposit_verified', 'mission_completed', 'education_read'
            $table->unsignedBigInteger('source_id')->nullable();
            $table->string('description');
            $table->timestamps();
            $table->index(['user_id', 'created_at']);
        });

        // 5. Misi & Pencapaian (Achievements)
        Schema::create('missions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('type'); // 'daily', 'weekly', 'hunting'
            $table->string('action_type'); // 'deposit_count', 'weight_target', 'scan_count', 'education_read'
            $table->unsignedInteger('target_count')->default(1);
            $table->unsignedInteger('reward_xp')->default(50);
            $table->unsignedInteger('reward_ecopoint')->default(10);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('user_missions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('mission_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('current_progress')->default(0);
            $table->boolean('is_completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'mission_id']);
        });

        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('icon')->default('bi-trophy');
            $table->string('criterion_type'); // 'total_deposits', 'total_weight', 'first_deposit'
            $table->unsignedInteger('criterion_value')->default(1);
            $table->unsignedInteger('reward_xp')->default(100);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('user_achievements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('achievement_id')->constrained()->cascadeOnDelete();
            $table->timestamp('unlocked_at');
            $table->timestamps();
            $table->unique(['user_id', 'achievement_id']);
        });

        // 6. Sistem Notifikasi In-App
        Schema::create('in_app_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('message');
            $table->string('type'); // 'deposit_verified', 'wallet_credit', 'withdrawal_status', 'mission_completed', 'level_up'
            $table->string('action_url')->nullable();
            $table->boolean('is_read')->default(false)->index();
            $table->timestamps();
            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('in_app_notifications');
        Schema::dropIfExists('user_achievements');
        Schema::dropIfExists('achievements');
        Schema::dropIfExists('user_missions');
        Schema::dropIfExists('missions');
        Schema::dropIfExists('xp_ledgers');
        Schema::dropIfExists('levels');
        Schema::dropIfExists('withdrawal_requests');
        Schema::dropIfExists('withdrawal_methods');
        Schema::dropIfExists('wallet_transactions');
        Schema::dropIfExists('wallet_accounts');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'has_completed_onboarding', 'streak_days', 'last_activity_date']);
        });
    }
};
