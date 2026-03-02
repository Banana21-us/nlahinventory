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
        Schema::create('location_area_parts', function (Blueprint $table) {
            $table->id();
            $table->integer('location_id');
            $table->integer('area_part_id');
            $table->enum('frequency', ['daily', 'weekly', 'monthly']);

            $table->unique(['location_id', 'area_part_id', 'frequency']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('location_area_parts');
    }
};
