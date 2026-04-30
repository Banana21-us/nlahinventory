<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payoff_applications', function (Blueprint $table) {
            $table->enum('redemption_type', ['cash', 'leave'])->default('cash')->after('reason');
            $table->string('dept_head_status', 20)->default('pending')->after('redemption_type');
            $table->foreignId('dept_head_approved_by')->nullable()->constrained('users')->nullOnDelete()->after('dept_head_status');
        });

        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE payoff_applications MODIFY COLUMN status ENUM('pending','dept_approved','hr_approved','approved','rejected') NOT NULL DEFAULT 'pending'");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE payoff_applications MODIFY COLUMN status ENUM('pending','hr_approved','approved','rejected') NOT NULL DEFAULT 'pending'");
        }

        Schema::table('payoff_applications', function (Blueprint $table) {
            $table->dropForeign(['dept_head_approved_by']);
            $table->dropColumn(['redemption_type', 'dept_head_status', 'dept_head_approved_by']);
        });
    }
};
