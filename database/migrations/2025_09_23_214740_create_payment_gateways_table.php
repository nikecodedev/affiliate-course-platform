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
        Schema::create('payment_gateways', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Asaas, Stone, PagSeguro, etc.
            $table->string('code'); // asaas, stone, pagseguro
            $table->string('type'); // credit_card, pix, boleto, bank_transfer
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            
            // Configuration (encrypted)
            $table->text('api_key')->nullable(); // Encrypted
            $table->text('api_secret')->nullable(); // Encrypted
            $table->text('webhook_secret')->nullable(); // Encrypted
            $table->string('api_url')->nullable();
            $table->string('webhook_url')->nullable();
            
            // Settings
            $table->decimal('fee_percentage', 5, 2)->default(0); // Gateway fee
            $table->decimal('fee_fixed', 10, 2)->default(0); // Fixed fee
            $table->boolean('fee_charged_to_customer')->default(false);
            $table->json('supported_currencies')->nullable(); // Array of supported currencies
            $table->json('supported_countries')->nullable(); // Array of supported countries
            
            // Processing settings
            $table->integer('processing_time_days')->default(1); // Days to process
            $table->boolean('auto_approve')->default(false); // Auto approve payments
            $table->boolean('requires_webhook')->default(true);
            
            // Limits
            $table->decimal('min_amount', 10, 2)->default(0);
            $table->decimal('max_amount', 15, 2)->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['code', 'is_active']);
            $table->index(['type', 'is_active']);
            $table->unique('code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_gateways');
    }
};