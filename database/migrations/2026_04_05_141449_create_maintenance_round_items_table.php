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
        Schema::create('maintenance_round_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('round_id');
            $table->unsignedBigInteger('location_area_id');
            $table->unsignedBigInteger('location_area_part_id');
            $table->enum('status', ['pending', 'completed', 'skipped'])->default('pending');
            $table->string('photo_path')->nullable();
            $table->string('skip_reason')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->unsignedInteger('order_number')->default(0);
            $table->unsignedBigInteger('verified_by')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->enum('verification_status', ['pending', 'approved', 'flagged', 'rejected'])->default('pending');
            $table->string('verification_comment')->nullable();
            $table->timestamps();
            $table->index(['round_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_round_items');
    }
};
