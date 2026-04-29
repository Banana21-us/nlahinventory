<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('overtime_applications', function (Blueprint $table) {
            if (! Schema::hasColumn('overtime_applications', 'hr_status')) {
                $table->string('hr_status', 20)->default('pending')->after('status');
            }
            if (! Schema::hasColumn('overtime_applications', 'hr_approved_by')) {
                $table->foreignId('hr_approved_by')->nullable()->after('hr_status')->constrained('users')->nullOnDelete();
            }
            if (! Schema::hasColumn('overtime_applications', 'accounting_status')) {
                $table->string('accounting_status', 20)->default('pending')->after('hr_approved_by');
            }
            if (! Schema::hasColumn('overtime_applications', 'accounting_approved_by')) {
                $table->foreignId('accounting_approved_by')->nullable()->after('accounting_status')->constrained('users')->nullOnDelete();
            }
        });

        Schema::table('payoff_applications', function (Blueprint $table) {
            if (! Schema::hasColumn('payoff_applications', 'hr_status')) {
                $table->string('hr_status', 20)->default('pending')->after('status');
            }
            if (! Schema::hasColumn('payoff_applications', 'hr_approved_by')) {
                $table->foreignId('hr_approved_by')->nullable()->after('hr_status')->constrained('users')->nullOnDelete();
            }
            if (! Schema::hasColumn('payoff_applications', 'accounting_status')) {
                $table->string('accounting_status', 20)->default('pending')->after('hr_approved_by');
            }
            if (! Schema::hasColumn('payoff_applications', 'accounting_approved_by')) {
                $table->foreignId('accounting_approved_by')->nullable()->after('accounting_status')->constrained('users')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('overtime_applications', function (Blueprint $table) {
            $table->dropForeign(['hr_approved_by']);
            $table->dropForeign(['accounting_approved_by']);
            $table->dropColumn(['hr_status', 'hr_approved_by', 'accounting_status', 'accounting_approved_by']);
        });

        Schema::table('payoff_applications', function (Blueprint $table) {
            $table->dropForeign(['hr_approved_by']);
            $table->dropForeign(['accounting_approved_by']);
            $table->dropColumn(['hr_status', 'hr_approved_by', 'accounting_status', 'accounting_approved_by']);
        });
    }
};
