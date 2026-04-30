<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // SQLite stores enums as TEXT and accepts any value — no schema change needed.
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE leaves MODIFY COLUMN dept_head_status ENUM('pending','approved','rejected','cancellation_requested','cancelled') NOT NULL DEFAULT 'pending'");
        }
    }

    public function down(): void
    {
        // Revert any rows using the new statuses back to 'approved'
        DB::table('leaves')->whereIn('dept_head_status', ['cancellation_requested', 'cancelled'])->update(['dept_head_status' => 'approved']);

        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE leaves MODIFY COLUMN dept_head_status ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending'");
        }
    }
};
