<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('overtime_applications', function (Blueprint $table) {
            if (! Schema::hasColumn('overtime_applications', 'dept_head_status')) {
                $table->string('dept_head_status', 20)->default('pending')->after('status');
            }
            if (! Schema::hasColumn('overtime_applications', 'dept_head_approved_by')) {
                $table->foreignId('dept_head_approved_by')->nullable()->constrained('users')->nullOnDelete()->after('dept_head_status');
            }
        });

        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE overtime_applications MODIFY COLUMN status ENUM('pending','dept_approved','hr_approved','approved','rejected') NOT NULL DEFAULT 'pending'");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE overtime_applications MODIFY COLUMN status ENUM('pending','hr_approved','approved','rejected') NOT NULL DEFAULT 'pending'");
        }

        Schema::table('overtime_applications', function (Blueprint $table) {
            $table->dropForeign(['dept_head_approved_by']);
            $table->dropColumn(['dept_head_status', 'dept_head_approved_by']);
        });
    }
};
