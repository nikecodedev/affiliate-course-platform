<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bonus_levels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bonus_configuration_id')->constrained('global_bonus_configurations')->onDelete('cascade');
            $table->integer('level'); // Level number (1, 2, 3, etc.)
            $table->decimal('amount', 10, 2); // Bonus amount (fixed or percentage)
            $table->boolean('is_percentage')->default(false); // Override configuration setting
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->unique(['bonus_configuration_id', 'level']);
            $table->index(['bonus_configuration_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bonus_levels');
    }
};