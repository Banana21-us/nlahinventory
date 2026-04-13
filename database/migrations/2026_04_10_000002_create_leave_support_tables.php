<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leave_types', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('label');
            $table->boolean('is_paid')->default(true);
            $table->boolean('requires_attachment')->default(false);
            $table->boolean('solo_parent_only')->default(false);
            $table->boolean('requires_admin_approval')->default(false);
            $table->decimal('annual_days', 4, 1)->nullable();
            $table->enum('reset_type', ['anniversary', 'january', 'birth_month', 'none']);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('holidays', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('date');
            $table->enum('type', ['regular', 'special_non_working', 'special_working']);
            $table->boolean('is_recurring')->default(true);
            $table->string('remarks')->nullable();
            $table->timestamps();
        });

        Schema::create('payoff_applications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->dateTime('start_datetime');
            $table->dateTime('end_datetime');
            $table->decimal('hours', 5, 2);
            $table->text('reason')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamps();
        });

        Schema::create('overtime_applications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->enum('type', ['overtime', 'on_call']);
            $table->dateTime('start_datetime');
            $table->dateTime('end_datetime');
            $table->text('reason')->nullable();
            $table->text('remarks')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('overtime_applications');
        Schema::dropIfExists('payoff_applications');
        Schema::dropIfExists('holidays');
        Schema::dropIfExists('leave_types');
    }
};
