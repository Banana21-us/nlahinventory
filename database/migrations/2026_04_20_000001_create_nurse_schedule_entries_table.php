<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nurse_schedule_entries', function (Blueprint $table) {
            $table->id();
            $table->date('schedule_date');
            $table->string('section', 20);       // ward | or | hn
            $table->string('slot', 20);           // 1st | 2nd | 3rd | OPD | 8-3 | 3-11 | IPCN
            $table->string('period', 5);          // am | pm
            $table->unsignedBigInteger('employee_id')->nullable(); // plain join, no FK constraint
            $table->string('custom_name')->nullable(); // free-text name when no employee record
            $table->timestamps();

            // Prevent duplicate assignments for the same employee on same date/section/slot/period
            $table->unique(['schedule_date','section','slot','period','employee_id'], 'unique_emp_slot');

            $table->index('schedule_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nurse_schedule_entries');
    }
};
