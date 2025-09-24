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
        Schema::table('bonus_configurations', function (Blueprint $table) {
            // Enhanced level configurations (JSON) - will replace existing unilevel and matrix arrays
            $table->json('unilevel_levels')->nullable()->after('unilevel_fixed_amounts');
            $table->json('matrix_levels')->nullable()->after('matrix_fixed_amounts');
            
            // Payment type configuration
            $table->enum('unilevel_payment_type', ['fixed_amount', 'percentage'])->default('percentage')->after('unilevel_levels');
            $table->enum('matrix_payment_type', ['fixed_amount', 'percentage'])->default('percentage')->after('matrix_levels');
            
            // Direct referral configuration enhancement
            $table->boolean('direct_referral_enabled')->default(false)->after('direct_referral_fixed');
            $table->enum('direct_referral_payment_type', ['fixed_amount', 'percentage'])->default('percentage')->after('direct_referral_enabled');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bonus_configurations', function (Blueprint $table) {
            $table->dropColumn([
                'unilevel_levels',
                'matrix_levels',
                'unilevel_payment_type',
                'matrix_payment_type',
                'direct_referral_enabled',
                'direct_referral_payment_type'
            ]);
        });
    }
};