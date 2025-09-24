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
        Schema::table('plans', function (Blueprint $table) {
            $table->boolean('has_direct_referral_bonus')->default(false);
            $table->boolean('direct_referral_is_percentage')->default(false);
            $table->decimal('direct_referral_amount', 10, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn([
                'has_direct_referral_bonus',
                'direct_referral_is_percentage',
                'direct_referral_amount'
            ]);
        });
    }
};