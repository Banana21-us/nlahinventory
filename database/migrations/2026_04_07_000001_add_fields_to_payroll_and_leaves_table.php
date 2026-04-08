<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payroll_and_leaves', function (Blueprint $table) {
            $table->string('min_scale')->default('0')->after('salary_rate');
            $table->string('max_scale')->default('0')->after('min_scale');
            $table->string('wage_factor')->default('1.00')->after('max_scale');
            $table->string('po_consumed')->default('0')->after('el_total');
            $table->string('po_total')->default('0')->after('po_consumed');
            $table->string('probi_rate')->default('1.00')->after('po_total');
        });
    }

    public function down(): void
    {
        Schema::table('payroll_and_leaves', function (Blueprint $table) {
            $table->dropColumn(['min_scale', 'max_scale', 'wage_factor', 'po_consumed', 'po_total', 'probi_rate']);
        });
    }
};
