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
        Schema::table('plan_products', function (Blueprint $table) {
            // Direct referral configuration
            $table->boolean('direct_referral_enabled')->default(false)->after('download_url');
            $table->decimal('direct_referral_amount', 10, 2)->nullable()->after('direct_referral_enabled');
            $table->decimal('direct_referral_percentage', 5, 2)->nullable()->after('direct_referral_amount');
            $table->enum('direct_referral_type', ['fixed_amount', 'percentage'])->default('percentage')->after('direct_referral_percentage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plan_products', function (Blueprint $table) {
            $table->dropColumn([
                'direct_referral_enabled',
                'direct_referral_amount',
                'direct_referral_percentage',
                'direct_referral_type'
            ]);
        });
    }
};