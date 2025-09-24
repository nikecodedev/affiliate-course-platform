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
        Schema::create('bonus_settings', function (Blueprint $table) {
            $table->id();
            $table->enum('bonus_type', ['direct_referral', 'unilevel', 'matrix']);
            $table->boolean('is_active')->default(false);
            $table->enum('payment_mode', ['fixed', 'percentage'])->nullable();
            $table->integer('width')->nullable(); // for matrix laterality
            $table->integer('depth')->nullable(); // for matrix depth
            $table->json('levels')->nullable(); // store config like { "1": {"mode":"fixed","value":10}, "2": {"mode":"percent","value":5} }
            $table->timestamps();
            
            $table->unique('bonus_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bonus_settings');
    }
};