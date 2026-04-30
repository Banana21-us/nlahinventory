<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE leaves MODIFY COLUMN dept_head_status ENUM('pending','approved','rejected','cancellation_requested','cancellation_approved','cancellation_rejected','cancelled') NOT NULL DEFAULT 'pending'");
        }
    }

    public function down(): void
    {
        DB::table('leaves')->where('dept_head_status', 'cancellation_approved')->update(['dept_head_status' => 'cancellation_requested']);
        DB::table('leaves')->where('dept_head_status', 'cancellation_rejected')->update(['dept_head_status' => 'approved']);

        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE leaves MODIFY COLUMN dept_head_status ENUM('pending','approved','rejected','cancellation_requested','cancelled') NOT NULL DEFAULT 'pending'");
        }
    }
};
