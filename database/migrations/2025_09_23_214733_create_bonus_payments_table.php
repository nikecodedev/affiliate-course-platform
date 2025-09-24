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
        Schema::create('bonus_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('sale_id')->nullable()->constrained('sales')->onDelete('cascade');
            $table->string('bonus_type'); // direct_referral, unilevel, forced_matrix, profit_sharing
            $table->integer('level')->nullable(); // Level for unilevel/matrix bonuses
            $table->decimal('amount', 15, 2);
            $table->decimal('percentage', 5, 2)->nullable(); // Percentage used for calculation
            $table->decimal('base_amount', 15, 2)->nullable(); // Original sale amount
            $table->enum('status', ['pending', 'approved', 'paid', 'cancelled'])->default('pending');
            $table->enum('eligibility_status', ['eligible', 'ineligible', 'partial'])->default('eligible');
            $table->text('eligibility_reason')->nullable(); // Reason for eligibility status
            
            // Reference information
            $table->foreignId('from_client_id')->nullable()->constrained('clients')->onDelete('set null');
            $table->foreignId('bonus_configuration_id')->nullable()->constrained('bonus_configurations')->onDelete('set null');
            
            // Payment information
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('admins')->onDelete('set null');
            $table->text('notes')->nullable();
            
            // Period tracking
            $table->date('period_start');
            $table->date('period_end');
            $table->string('period_key'); // Unique key for period tracking (e.g., "2024-01")
            
            $table->timestamps();
            
            // Indexes
            $table->index(['client_id', 'status']);
            $table->index(['bonus_type', 'status']);
            $table->index(['period_start', 'period_end']);
            $table->index('period_key');
            $table->index(['created_at', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bonus_payments');
    }
};