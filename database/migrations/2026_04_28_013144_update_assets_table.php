<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Widen ENUM → VARCHAR first so data updates can use new values
        Schema::table('assets', function (Blueprint $table) {
            $table->string('status')->default('available')->change();
            $table->string('condition_status')->default('good')->change();
        });

        // Rename legacy ENUM values to new canonical strings
        DB::table('assets')->where('status', 'active')->update(['status' => 'available']);
        DB::table('assets')->where('status', 'retired')->update(['status' => 'disposed']);
    }

    public function down(): void
    {
        // Revert data values before shrinking back to ENUM
        DB::table('assets')->where('status', 'available')->update(['status' => 'active']);
        DB::table('assets')->where('status', 'disposed')->update(['status' => 'retired']);

        Schema::table('assets', function (Blueprint $table) {
            $table->string('status')->default('active')->change();
            $table->string('condition_status')->default('good')->change();
        });
    }
};
