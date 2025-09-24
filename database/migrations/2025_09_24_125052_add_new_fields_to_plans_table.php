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
            // Add new fields if they don't exist
            if (!Schema::hasColumn('plans', 'direct_bonus_enabled')) {
                $table->boolean('direct_bonus_enabled')->default(false)->after('cost_price');
            }
            if (!Schema::hasColumn('plans', 'direct_bonus_mode')) {
                $table->enum('direct_bonus_mode', ['fixed', 'percentage'])->nullable()->after('direct_bonus_enabled');
            }
            if (!Schema::hasColumn('plans', 'direct_bonus_value')) {
                $table->decimal('direct_bonus_value', 10, 2)->nullable()->after('direct_bonus_mode');
            }
            if (!Schema::hasColumn('plans', 'commission_unilevel')) {
                $table->json('commission_unilevel')->nullable()->after('direct_bonus_value');
            }
            if (!Schema::hasColumn('plans', 'commission_matrix')) {
                $table->json('commission_matrix')->nullable()->after('commission_unilevel');
            }
            if (!Schema::hasColumn('plans', 'commission_profit_sharing')) {
                $table->decimal('commission_profit_sharing', 10, 2)->nullable()->after('commission_matrix');
            }
            if (!Schema::hasColumn('plans', 'external_url')) {
                $table->string('external_url')->nullable()->after('commission_profit_sharing');
            }
            if (!Schema::hasColumn('plans', 'course_id')) {
                $table->foreignId('course_id')->nullable()->constrained('courses')->onDelete('set null')->after('external_url');
            }
            if (!Schema::hasColumn('plans', 'status')) {
                $table->boolean('status')->default(true)->after('course_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn([
                'direct_bonus_enabled',
                'direct_bonus_mode',
                'direct_bonus_value',
                'commission_unilevel',
                'commission_matrix',
                'commission_profit_sharing',
                'external_url',
                'course_id',
                'status'
            ]);
        });
    }
};