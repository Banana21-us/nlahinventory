<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payroll_and_leaves', function (Blueprint $table) {
            // Make user_id nullable so Finance can be saved before a user account is linked
            $table->unsignedBigInteger('user_id')->nullable()->change();
            // Add employee_id as the stable key for payroll records
            $table->unsignedBigInteger('employee_id')->nullable()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('payroll_and_leaves', function (Blueprint $table) {
            $table->dropColumn('employee_id');
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
        });
    }
};
