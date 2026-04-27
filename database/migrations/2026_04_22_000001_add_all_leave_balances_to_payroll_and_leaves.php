<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payroll_and_leaves', function (Blueprint $table) {
            $table->decimal('ml_total', 8, 2)->default(0)->after('bl_consumed');
            $table->decimal('ml_consumed', 8, 2)->default(0)->after('ml_total');

            $table->decimal('pl_total', 8, 2)->default(0)->after('ml_consumed');
            $table->decimal('pl_consumed', 8, 2)->default(0)->after('pl_total');

            $table->decimal('syl_total', 8, 2)->default(0)->after('pl_consumed');
            $table->decimal('syl_consumed', 8, 2)->default(0)->after('syl_total');

            $table->decimal('cal_total', 8, 2)->default(0)->after('syl_consumed');
            $table->decimal('cal_consumed', 8, 2)->default(0)->after('cal_total');

            $table->decimal('stl_total', 8, 2)->default(0)->after('cal_consumed');
            $table->decimal('stl_consumed', 8, 2)->default(0)->after('stl_total');

            $table->decimal('mwl_total', 8, 2)->default(0)->after('stl_consumed');
            $table->decimal('mwl_consumed', 8, 2)->default(0)->after('mwl_total');
        });
    }

    public function down(): void
    {
        Schema::table('payroll_and_leaves', function (Blueprint $table) {
            $table->dropColumn([
                'ml_total', 'ml_consumed',
                'pl_total', 'pl_consumed',
                'syl_total', 'syl_consumed',
                'cal_total', 'cal_consumed',
                'stl_total', 'stl_consumed',
                'mwl_total', 'mwl_consumed',
            ]);
        });
    }
};
