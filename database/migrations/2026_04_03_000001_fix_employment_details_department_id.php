<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employment_details', function (Blueprint $table) {
            if (Schema::hasColumn('employment_details', 'department')) {
                $table->dropColumn('department');
            }
            if (Schema::hasColumn('employment_details', 'dept_code')) {
                $table->dropColumn('dept_code');
            }
            if (! Schema::hasColumn('employment_details', 'department_id')) {
                $table->unsignedBigInteger('department_id')->nullable()->after('employee_id');
            }
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
