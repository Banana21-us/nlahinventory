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
        Schema::table('overtime_applications', function (Blueprint $table) {
            $table->decimal('hours', 5, 2)->after('end_datetime')->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('overtime_applications', function (Blueprint $table) {
            $table->dropColumn('hours');
        });
    }
};
