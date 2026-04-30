<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('asset_maintenance', function (Blueprint $table) {
            $table->id();

            $table->foreignId('asset_id')
                  ->constrained('assets')
                  ->cascadeOnDelete();

            $table->text('issue_description');
            $table->text('repair_action')->nullable();

            $table->enum('status', [
                'pending',
                'in_progress',
                'completed',
                'cancelled'
            ])->default('pending');

            $table->unsignedBigInteger('maintenance_department_id')->nullable();

            $table->decimal('cost', 10, 2)->nullable();

            $table->timestamp('reported_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_maintenance');
    }
};
