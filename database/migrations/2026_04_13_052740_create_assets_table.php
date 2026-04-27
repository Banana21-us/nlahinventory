<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_type_id');
            $table->unsignedBigInteger('location_id');
            $table->string('status')->default('available');
            $table->string('brand')->nullable();
            $table->date('purchase_date')->nullable();
            $table->string('sku')->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
