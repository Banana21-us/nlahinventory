<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // SQLite stores enums as TEXT and accepts any value — no schema change needed.
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE leaves MODIFY COLUMN hr_status ENUM('pending','approved','rejected','cancelled','cancellation_requested') NOT NULL DEFAULT 'pending'");
        }
    }

    public function down(): void
    {
        DB::table('leaves')->where('hr_status', 'cancellation_requested')->update(['hr_status' => 'pending']);
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE leaves MODIFY COLUMN hr_status ENUM('pending','approved','rejected','cancelled') NOT NULL DEFAULT 'pending'");
        }
    }
};
