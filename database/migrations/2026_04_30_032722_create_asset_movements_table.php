<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_movements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('asset_id')->constrained('assets')->onDelete('cascade');

            $table->foreignId('from_department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->foreignId('to_department_id')->nullable()->constrained('departments')->nullOnDelete();

            $table->foreignId('from_location_id')->nullable()->constrained('locations')->nullOnDelete();
            $table->foreignId('to_location_id')->nullable()->constrained('locations')->nullOnDelete();

            $table->foreignId('moved_by')->constrained('users')->onDelete('cascade');

            $table->text('remarks')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_movements');
    }
};