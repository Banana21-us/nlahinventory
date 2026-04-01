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
        Schema::create('biometric_logs', function (Blueprint $table) {
            $table->id();
            $table->string('biometric_id'); // ID from machine
            $table->unsignedBigInteger('user_id'); // Mapped User
            $table->dateTime('punch_time');
            $table->integer('punch_type'); // 0=In, 1=Out
            $table->boolean('is_processed')->default(false); // Flag for summary logic
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('biometric_logs');
    }
};
