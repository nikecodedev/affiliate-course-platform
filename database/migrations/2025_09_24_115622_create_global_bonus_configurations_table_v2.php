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
        Schema::create('global_bonus_configurations', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Unilevel Bonus", "Forced Matrix Bonus"
            $table->string('type'); // 'unilevel', 'forced_matrix', 'direct_referral'
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_percentage')->default(false); // false = fixed amount, true = percentage
            $table->integer('max_depth')->default(10); // Maximum depth levels
            $table->integer('max_width')->default(2); // Maximum width (for forced matrix)
            $table->decimal('min_sale_amount', 10, 2)->default(0); // Minimum sale amount to qualify
            $table->boolean('requires_active_invoice')->default(true); // Only active invoices qualify
            $table->json('eligibility_rules')->nullable(); // Additional eligibility rules
            $table->timestamps();
            
            $table->index(['type', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('global_bonus_configurations');
    }
};