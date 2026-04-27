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
        Schema::table('payroll_and_leaves', function (Blueprint $table) {
            $table->decimal('wage_factor', 10, 2)->default(1.00)->change();
        });
    }

    public function down(): void
    {
        Schema::table('payroll_and_leaves', function (Blueprint $table) {
            $table->decimal('wage_factor', 8, 4)->default(1.0)->change();
        });
    }
};
