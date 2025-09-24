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
        Schema::create('bonus_configurations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->constrained('plans')->onDelete('cascade');
            $table->string('bonus_type'); // direct_referral, unilevel, forced_matrix, profit_sharing
            
            // Direct Referral Bonus
            $table->decimal('direct_referral_percentage', 5, 2)->nullable(); // Percentage of sale
            $table->decimal('direct_referral_fixed', 15, 2)->nullable(); // Fixed amount
            
            // Unilevel Bonus (up to 10 levels)
            $table->json('unilevel_percentages')->nullable(); // Array of percentages for each level
            $table->json('unilevel_fixed_amounts')->nullable(); // Array of fixed amounts for each level
            
            // Forced Matrix Bonus
            $table->integer('matrix_width')->default(2); // Binary tree = 2, ternary = 3, etc.
            $table->integer('matrix_depth')->default(10); // Number of levels deep
            $table->json('matrix_percentages')->nullable(); // Array of percentages for each level
            $table->json('matrix_fixed_amounts')->nullable(); // Array of fixed amounts for each level
            
            // Profit Sharing
            $table->decimal('profit_sharing_percentage', 5, 2)->nullable();
            $table->enum('profit_sharing_basis', ['total_volume', 'personal_volume', 'group_volume'])->default('total_volume');
            
            // Eligibility and Limits
            $table->boolean('requires_active_invoice')->default(true);
            $table->decimal('minimum_volume', 15, 2)->default(0);
            $table->decimal('maximum_bonus_per_period', 15, 2)->nullable();
            $table->enum('period', ['daily', 'weekly', 'monthly'])->default('monthly');
            
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Indexes
            $table->index(['plan_id', 'bonus_type', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bonus_configurations');
    }
};