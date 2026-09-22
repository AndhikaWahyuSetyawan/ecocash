<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Education content
        Schema::create('education_contents', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category'); // 'sorting_guide' | 'waste_info' | 'tips' | 'news'
            $table->text('summary')->nullable();
            $table->longText('body');
            $table->string('slug')->unique();
            $table->foreignId('author_id')->constrained('users')->cascadeOnDelete();
            $table->boolean('is_published')->default(false)->index();
            $table->timestamps();
            $table->softDeletes();
        });

        // Impact factors (separate configurable table)
        Schema::create('impact_factors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('waste_category_id')->constrained()->cascadeOnDelete();
            $table->decimal('co2e_per_kg', 10, 4)->comment('kg CO2e saved per kg of waste processed');
            $table->string('source')->nullable()->comment('Reference/source for this factor');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('impact_factors');
        Schema::dropIfExists('education_contents');
    }
};
