<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employment_details', function (Blueprint $table) {
            $table->dropColumn(['department', 'dept_code']);
        });

        Schema::table('employment_details', function (Blueprint $table) {
            $table->unsignedBigInteger('department_id')->nullable()->after('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('employment_details', function (Blueprint $table) {
            $table->dropColumn('department_id');
        });

        Schema::table('employment_details', function (Blueprint $table) {
            $table->string('department')->nullable()->after('user_id');
            $table->string('dept_code')->nullable()->after('department');
        });
    }
};
