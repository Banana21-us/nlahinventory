<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payroll_and_leaves', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');

            // Financials
            $table->decimal('salary_rate', 15, 2)->default(0);
            $table->decimal('daily_rate', 10, 2)->default(0);
            $table->decimal('monthly_rate', 15, 2)->default(0);
            $table->decimal('cola', 10, 2)->default(0);
            $table->decimal('grocery_allowance', 10, 2)->default(0);

            // Night Differential Factor
            $table->decimal('night_diff_factor', 5, 2)->default(1.10); // 10% Premium

            // Leave Balances (Running Totals)
            $table->decimal('vl_total', 8, 2)->default(0); // Vacation
            $table->decimal('vl_consumed', 8, 2)->default(0);

            $table->decimal('sl_total', 8, 2)->default(0); // Sick
            $table->decimal('sl_consumed', 8, 2)->default(0);

            $table->decimal('spl_total', 8, 2)->default(0); // Solo Parent
            $table->decimal('el_total', 8, 2)->default(0);  // Emergency

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_and_leaves');
    }
};
