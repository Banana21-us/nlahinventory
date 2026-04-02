<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendance_summary', function (Blueprint $table) {
            // Make clock_in nullable (absent employees have no punches)
            $table->dateTime('clock_in')->nullable()->change();

            // Shift identification
            $table->enum('shift_type', ['office', 'nurse'])->default('office')->after('user_id');

            // Office-worker AM/PM split (stored as TIME)
            $table->time('am_in')->nullable()->after('clock_out');
            $table->time('am_out')->nullable()->after('am_in');
            $table->time('pm_in')->nullable()->after('am_out');
            $table->time('pm_out')->nullable()->after('pm_in');

            // Status
            $table->string('status', 20)->default('absent')->after('pm_out');
            // on_time | late_am | late_pm | late_both | half_day_am | half_day_pm | absent | overtime | late (nurse)

            // Email alert tracking
            $table->boolean('email_sent')->default(false)->after('status');

            // Prevent duplicate records per employee per day
            $table->unique(['user_id', 'attendance_date'], 'unique_user_date');
        });
    }

    public function down(): void
    {
        Schema::table('attendance_summary', function (Blueprint $table) {
            $table->dropUnique('unique_user_date');
            $table->dropColumn(['shift_type', 'am_in', 'am_out', 'pm_in', 'pm_out', 'status', 'email_sent']);
            $table->dateTime('clock_in')->nullable(false)->change();
        });
    }
};
