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
    Schema::table('leaves', function (Blueprint $table) {
        // Only keep the columns that don't exist yet
        $table->timestamp('date_approved_dept')->after('dept_head_status')->nullable();
        $table->text('dept_head_remarks')->after('date_approved_dept')->nullable();
    });
}

public function down(): void
{
    Schema::table('leaves', function (Blueprint $table) {
        $table->dropColumn(['date_approved_dept', 'dept_head_remarks']);
    });
}
};
