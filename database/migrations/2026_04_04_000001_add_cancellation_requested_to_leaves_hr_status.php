<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE leaves MODIFY COLUMN hr_status ENUM('pending','approved','rejected','cancelled','cancellation_requested') NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        // Revert any cancellation_requested rows to pending before removing the enum value
        DB::table('leaves')->where('hr_status', 'cancellation_requested')->update(['hr_status' => 'pending']);
        DB::statement("ALTER TABLE leaves MODIFY COLUMN hr_status ENUM('pending','approved','rejected','cancelled') NOT NULL DEFAULT 'pending'");
    }
};
