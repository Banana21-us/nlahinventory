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
        Schema::create('employee', function (Blueprint $table) {
            $table->id();
            $table->string('employee_number')->unique();

            $table->unsignedBigInteger('user_id')->nullable(); // Manual Reference
            $table->string('biometric_id')->unique()->nullable(); // Link to ZKTeco ID

            $table->string('last_name');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('extension')->nullable();

            $table->date('birth_date');
            $table->string('place_of_birth')->nullable();
            $table->enum('gender', ['Male', 'Female']);
            $table->string('civil_status')->nullable();
            $table->string('citizenship')->default('Filipino');
            $table->string('religion')->nullable();
            $table->string('blood_type')->nullable();

            $table->string('height')->nullable();
            $table->string('weight')->nullable();

            $table->string('mobile_no')->nullable();
            $table->string('telephone')->nullable();
            $table->string('email_add')->nullable();
            $table->text('p_address')->nullable(); // Permanent
            $table->text('c_address')->nullable();

            $table->string('contact_person')->nullable(); // Emergency
            $table->string('contact_number')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
