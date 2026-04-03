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
        Schema::create('employment_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id'); // FK to employee table
            
            // Professional Details
            $table->unsignedBigInteger('department_id')->nullable(); // FK to departments table
            $table->string('position');
            $table->string('rank')->nullable();
            $table->enum('employment_status', ['Probationary', 'Regular', 'Contractual', 'Casual']);
            $table->date('hiring_date');
            $table->date('regularization_date')->nullable();
            
            // Licensing (Important for Hospital Staff)
            $table->string('license_no')->nullable(); // PRC License
            $table->date('license_expiry')->nullable();
            $table->boolean('re_membership')->default(false);

            // Government IDs (Sensitive Data)
            $table->string('philhealth_no')->nullable();
            $table->string('pagibig_no')->nullable();
            $table->string('tin_no')->nullable();
            $table->string('sss_no')->nullable();
            $table->string('gsis_no')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employment_details');
    }
};
