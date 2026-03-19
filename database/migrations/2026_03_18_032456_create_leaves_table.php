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
            $table->string('leavetype'); // type of leave
            $table->string('department'); // department
            $table->date('startdate'); // leave start date
            $table->date('enddate'); // leave end date
            $table->integer('totaldays'); // total days of leave
            $table->text('reason'); // reason for leave
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // status
            $table->unsignedBigInteger('approved_by')->nullable(); // user/admin who approved
            $table->text('remarks')->nullable(); // optional remarks
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