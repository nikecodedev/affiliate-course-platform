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
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('matrix_parent_id')->nullable()->after('active_network');
            $table->string('matrix_position')->nullable()->after('matrix_parent_id');
            $table->integer('matrix_level')->nullable()->after('matrix_position');
            
            $table->index(['matrix_parent_id']);
            $table->index(['matrix_position']);
            $table->index(['matrix_level']);
            
            $table->foreign('matrix_parent_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['matrix_parent_id']);
            $table->dropIndex(['matrix_parent_id']);
            $table->dropIndex(['matrix_position']);
            $table->dropIndex(['matrix_level']);
            $table->dropColumn(['matrix_parent_id', 'matrix_position', 'matrix_level']);
        });
    }
};
