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
        Schema::create('referral_networks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('sponsor_id')->nullable()->constrained('clients')->onDelete('set null');
            $table->foreignId('parent_id')->nullable()->constrained('referral_networks')->onDelete('set null');
            $table->enum('position', ['left', 'right'])->nullable(); // For binary tree structure
            $table->integer('level')->default(1); // Depth level in the network
            $table->integer('left_count')->default(0); // Count of left leg
            $table->integer('right_count')->default(0); // Count of right leg
            $table->decimal('left_volume', 15, 2)->default(0); // Volume in left leg
            $table->decimal('right_volume', 15, 2)->default(0); // Volume in right leg
            $table->boolean('is_active')->default(true);
            $table->timestamp('activated_at')->nullable();
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['client_id', 'sponsor_id']);
            $table->index(['parent_id', 'position']);
            $table->index(['level', 'is_active']);
            $table->unique('client_id'); // Each client can only be in the network once
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referral_networks');
    }
};