<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add cancellation_dhead_status column — tracks dept head review of a cancellation request.
        // null  = not yet requested / not applicable
        // pending  = staff requested, awaiting dept head review
        // approved = dept head signed off, forwarded to HR
        // rejected = dept head denied, leave restored to approved
        DB::statement("ALTER TABLE leaves ADD COLUMN cancellation_dhead_status ENUM('pending','approved','rejected') NULL DEFAULT NULL AFTER hr_status");
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE leaves DROP COLUMN cancellation_dhead_status');
    }
};
