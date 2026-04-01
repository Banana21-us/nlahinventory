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
        Schema::create('records', function (Blueprint $table) {
            $table->id();
            $table->integer('location_area_part_id');
            $table->date('cleaning_date');
            $table->enum('period_type', ['daily', 'weekly', 'monthly']);
            $table->enum('shift', ['AM', 'PM'])->nullable();
            $table->enum('status', ['YES', 'NO']);
            $table->string('remarks');
            $table->string('proof')->nullable();
            $table->string('maintenance_name')->nullable();
            $table->string('verifier_name')->nullable();
            $table->enum('verifier_status', ['YES', 'NO'])->nullable();
            $table->text('maintenance_comments')->nullable();
            $table->text('verifier_comments')->nullable();

            $table->unique(
                            ['location_area_part_id', 'period_type', 'cleaning_date', 'shift'],
                            'records_unique_combo'
                        );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('records');
    }
};
