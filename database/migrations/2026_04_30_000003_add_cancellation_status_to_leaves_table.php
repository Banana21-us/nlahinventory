<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            DB::statement('ALTER TABLE leaves ADD COLUMN cancellation_status TEXT NULL DEFAULT NULL');
        } else {
            DB::statement("ALTER TABLE leaves ADD COLUMN cancellation_status ENUM('pending','dhead_approved','dhead_rejected','cancelled','hr_rejected') NULL DEFAULT NULL AFTER cancellation_dhead_status");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement('ALTER TABLE leaves DROP COLUMN cancellation_status');
        }
    }
};
