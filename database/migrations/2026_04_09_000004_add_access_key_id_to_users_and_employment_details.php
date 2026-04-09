<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Users get the active access key (what they can actually open now)
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('access_key_id')->nullable()->after('is_active');
        });

        // Employment details store the *intended* access key HR pre-configures
        // — copied to users.access_key_id when the employee registers/links
        Schema::table('employment_details', function (Blueprint $table) {
            $table->unsignedBigInteger('access_key_id')->nullable()->after('position');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('access_key_id');
        });
        Schema::table('employment_details', function (Blueprint $table) {
            $table->dropColumn('access_key_id');
        });
    }
};
