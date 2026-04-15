<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('asset_id');         // Just an integer
            $table->unsignedBigInteger('location_id_from')->nullable(); // Just an integer
            $table->unsignedBigInteger('location_id_to')->nullable();   // Just an integer
            $table->enum('type', ['check-in', 'check-out', 'transfer', 'repair']);
            $table->text('notes')->nullable();
            $table->dateTime('datetime');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
