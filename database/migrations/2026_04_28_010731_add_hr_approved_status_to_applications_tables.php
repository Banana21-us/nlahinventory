<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE overtime_applications MODIFY COLUMN status ENUM('pending','hr_approved','approved','rejected') NOT NULL DEFAULT 'pending'");
            DB::statement("ALTER TABLE payoff_applications MODIFY COLUMN status ENUM('pending','hr_approved','approved','rejected') NOT NULL DEFAULT 'pending'");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE overtime_applications MODIFY COLUMN status ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending'");
            DB::statement("ALTER TABLE payoff_applications MODIFY COLUMN status ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending'");
        }
    }
};
