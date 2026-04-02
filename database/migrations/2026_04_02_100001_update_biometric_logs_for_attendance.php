<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('biometric_logs', function (Blueprint $table) {
            $table->string('source_file')->nullable()->after('is_processed'); // MDB filename
            $table->unique(['biometric_id', 'punch_time'], 'unique_biometric_punch'); // prevent re-import duplicates
        });
    }

    public function down(): void
    {
        Schema::table('biometric_logs', function (Blueprint $table) {
            $table->dropUnique('unique_biometric_punch');
            $table->dropColumn('source_file');
        });
    }
};
