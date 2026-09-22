<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('user')->index();
        });

        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });
        Schema::create('waste_categories', function (Blueprint $table) {
            $table->id(); $table->string('name'); $table->string('material')->nullable();
            $table->text('sorting_instruction')->nullable(); $table->string('processing_route')->nullable();
            $table->decimal('impact_factor', 10, 4)->default(0); $table->timestamps(); $table->softDeletes();
        });
        Schema::create('ai_class_mappings', function (Blueprint $table) {
            $table->id(); $table->unsignedInteger('class_id'); $table->string('class_name');
            $table->foreignId('waste_category_id')->constrained()->cascadeOnDelete(); $table->timestamps();
            $table->unique(['class_id', 'class_name']);
        });
        Schema::create('waste_prices', function (Blueprint $table) {
            $table->id(); $table->foreignId('waste_category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('partner_id')->nullable()->constrained()->nullOnDelete(); $table->unsignedInteger('price_per_kg');
            $table->date('effective_from'); $table->date('effective_until')->nullable(); $table->boolean('is_active')->default(true); $table->timestamps();
        });
        Schema::create('ai_scans', function (Blueprint $table) {
            $table->id(); $table->foreignId('user_id')->constrained()->cascadeOnDelete(); $table->string('image_path');
            $table->string('model_name'); $table->string('model_version')->nullable(); $table->unsignedInteger('processing_time_ms')->nullable(); $table->timestamps();
        });
        Schema::create('ai_detections', function (Blueprint $table) {
            $table->id(); $table->foreignId('ai_scan_id')->constrained()->cascadeOnDelete(); $table->unsignedInteger('class_id');
            $table->string('class_name'); $table->decimal('confidence', 6, 5); $table->decimal('x1', 10, 2); $table->decimal('y1', 10, 2); $table->decimal('x2', 10, 2); $table->decimal('y2', 10, 2);
            $table->foreignId('corrected_class_id')->nullable()->constrained('waste_categories')->nullOnDelete(); $table->foreignId('corrected_by')->nullable()->constrained('users')->nullOnDelete(); $table->timestamp('corrected_at')->nullable(); $table->timestamps();
        });
        Schema::create('deposits', function (Blueprint $table) {
            $table->id(); $table->foreignId('user_id')->constrained()->cascadeOnDelete(); $table->foreignId('partner_id')->constrained()->cascadeOnDelete();
            $table->string('status')->default('pending_verification')->index(); $table->timestamp('submitted_at')->nullable(); $table->timestamp('verified_at')->nullable(); $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete(); $table->text('rejection_reason')->nullable(); $table->timestamps();
        });
        Schema::create('deposit_items', function (Blueprint $table) {
            $table->id(); $table->foreignId('deposit_id')->constrained()->cascadeOnDelete(); $table->foreignId('ai_detection_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('waste_category_id')->constrained()->cascadeOnDelete(); $table->string('ai_category')->nullable(); $table->string('verified_category')->nullable();
            $table->decimal('declared_weight', 10, 3); $table->decimal('verified_weight', 10, 3)->nullable(); $table->unsignedInteger('price_per_kg'); $table->unsignedBigInteger('estimated_value')->default(0); $table->unsignedBigInteger('final_value')->nullable(); $table->unsignedInteger('estimated_ecopoint')->default(0); $table->unsignedInteger('final_ecopoint')->nullable(); $table->timestamps();
        });
        Schema::create('ecopoint_ledgers', function (Blueprint $table) {
            $table->id(); $table->foreignId('user_id')->constrained()->cascadeOnDelete(); $table->foreignId('deposit_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type'); $table->integer('amount'); $table->integer('balance_after'); $table->string('description'); $table->timestamps();
        });
        Schema::create('partner_waste_categories', function (Blueprint $table) { $table->foreignId('partner_id')->constrained()->cascadeOnDelete(); $table->foreignId('waste_category_id')->constrained()->cascadeOnDelete(); $table->primary(['partner_id','waste_category_id']); });
    }
    public function down(): void
    {
        Schema::dropIfExists('partner_waste_categories'); Schema::dropIfExists('ecopoint_ledgers'); Schema::dropIfExists('deposit_items'); Schema::dropIfExists('deposits'); Schema::dropIfExists('ai_detections'); Schema::dropIfExists('ai_scans'); Schema::dropIfExists('waste_prices'); Schema::dropIfExists('ai_class_mappings'); Schema::dropIfExists('waste_categories'); Schema::dropIfExists('partners');
        Schema::table('users', fn (Blueprint $table) => $table->dropColumn('role'));
    }
};
