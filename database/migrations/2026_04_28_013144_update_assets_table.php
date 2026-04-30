<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // ✅ Step 1: Fix existing values BEFORE changing ENUM
        DB::statement("UPDATE assets SET status = 'available' WHERE status = 'active'");
        DB::statement("UPDATE assets SET status = 'disposed' WHERE status = 'retired'");

        // ✅ Step 2: Modify columns
        DB::statement("
            ALTER TABLE assets
            MODIFY status ENUM(
                'available',
                'in_use',
                'out_of_service',
                'maintenance',
                'disposed',
                'lost'
            ) NOT NULL DEFAULT 'available'
        ");

        DB::statement("
            ALTER TABLE assets
            MODIFY condition_status ENUM(
                'excellent',
                'good',
                'fair',
                'poor',
                'critical',
                'damaged'
            ) NOT NULL DEFAULT 'good'
        ");
    }

    public function down(): void
    {
        // rollback to old ENUM (optional)
        DB::statement("
            ALTER TABLE assets
            MODIFY status ENUM(
                'active',
                'in_use',
                'maintenance',
                'retired'
            ) NOT NULL DEFAULT 'active'
        ");

        DB::statement("
            ALTER TABLE assets
            MODIFY condition_status ENUM(
                'good',
                'fair',
                'poor'
            ) NOT NULL DEFAULT 'good'
        ");
    }
};
