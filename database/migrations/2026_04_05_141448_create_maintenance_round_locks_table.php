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
        Schema::create('maintenance_round_locks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('location_area_id');
            $table->unsignedBigInteger('locked_by_user_id');
            $table->timestamp('locked_at');
            $table->timestamp('released_at')->nullable();
            $table->index(['location_area_id', 'released_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_round_locks');
    }
};
