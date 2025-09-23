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
        Schema::create('client_leads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('email');
            $table->string('phone', 20)->nullable();
            $table->string('source')->default('website');
            $table->enum('status', ['active', 'inactive', 'pending'])->default('pending');
            $table->text('notes')->nullable();
            $table->boolean('contacted')->default(false);
            $table->timestamp('contacted_at')->nullable();
            $table->text('contact_notes')->nullable();
            $table->boolean('converted')->default(false);
            $table->timestamp('converted_at')->nullable();
            $table->decimal('conversion_value', 10, 2)->nullable();
            $table->json('custom_fields')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index(['client_id', 'status']);
            $table->index(['status', 'contacted']);
            $table->index(['email', 'client_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_leads');
    }
};
