<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('overtime_applications', function (Blueprint $table) {
            $table->boolean('lunch_break_deducted')->default(false)->after('hours');
        });

        Schema::table('payoff_applications', function (Blueprint $table) {
            $table->boolean('lunch_break_deducted')->default(false)->after('hours');
        });
    }

    public function down(): void
    {
        Schema::table('overtime_applications', function (Blueprint $table) {
            $table->dropColumn('lunch_break_deducted');
        });

        Schema::table('payoff_applications', function (Blueprint $table) {
            $table->dropColumn('lunch_break_deducted');
        });
    }
};
