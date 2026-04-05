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
        Schema::create('leaves', function (Blueprint $table) {
            $table->id(); // id pk
            $table->unsignedBigInteger('user_id'); // reference to employee/login
            // Leave Specifics
            $table->string('leave_type'); // Sick, Vacation, Emergency, etc.
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('total_days', 4, 1); // Handles 2.5, 1.0, etc.
            $table->enum('day_part', ['Full', 'AM', 'PM'])->default('Full');
            $table->text('reason');
            $table->string('reliever')->nullable(); // Optional: Who will cover during the leave
            $table->string('attachment')->nullable(); // Path to Medical Cert

            // Date tracking
            $table->date('date_requested')->useCurrent();

            // Step 1: Department Head Approval
            $table->enum('dept_head_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamp('dept_head_approved_at')->nullable();
            $table->unsignedBigInteger('dept_head_id')->nullable(); // Who signed off first

            // Step 2: HR Final Approval
            $table->enum('hr_status', ['pending', 'approved', 'rejected', 'cancelled'])->default('pending');
            $table->timestamp('hr_approved_at')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();

            // Communication
            $table->text('remarks')->nullable(); // Internal Office Notes (Hidden from Staff)
            $table->text('rejection_reason')->nullable(); // Feedback for the Staff (Visible)

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leaves');
    }
};
