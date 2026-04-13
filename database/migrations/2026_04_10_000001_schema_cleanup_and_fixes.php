<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── payroll_and_leaves: drop obsolete payoff columns ──────────────────
        Schema::table('payroll_and_leaves', function (Blueprint $table) {
            $table->dropColumn(['po_total', 'po_consumed']);
        });

        // ── payroll_and_leaves: fix varchar → decimal ─────────────────────────
        Schema::table('payroll_and_leaves', function (Blueprint $table) {
            $table->decimal('min_scale', 15, 2)->default(0)->change();
            $table->decimal('max_scale', 15, 2)->default(0)->change();
            $table->decimal('wage_factor', 8, 4)->default(1.0)->change();
            $table->decimal('probi_rate', 5, 4)->default(1.0)->change();
        });

        // ── payroll_and_leaves: add new leave-balance columns ─────────────────
        Schema::table('payroll_and_leaves', function (Blueprint $table) {
            $table->decimal('initial_transition_grant', 4, 1)->default(0)->after('vl_consumed');
            $table->unsignedTinyInteger('years_accrued_count')->default(0)->after('initial_transition_grant');
            $table->date('vl_last_reset_at')->nullable()->after('years_accrued_count');
            $table->decimal('spl_consumed', 8, 2)->default(0)->after('spl_total');
            $table->decimal('el_consumed', 8, 2)->default(0)->after('el_total');
            $table->decimal('bl_total', 4, 1)->default(1)->after('el_consumed');
            $table->decimal('bl_consumed', 4, 1)->default(0)->after('bl_total');
        });

        // ── employee: add is_solo_parent ──────────────────────────────────────
        Schema::table('employee', function (Blueprint $table) {
            $table->boolean('is_solo_parent')->default(false)->after('contact_relationship');
        });

        // ── leaves: add extended fields ───────────────────────────────────────
        Schema::table('leaves', function (Blueprint $table) {
            $table->boolean('is_paid')->default(true)->after('leave_type');
            $table->unsignedTinyInteger('child_number')->nullable()->after('attachment');
            $table->date('child_birth_date')->nullable()->after('child_number');
            $table->string('deceased_name')->nullable()->after('child_birth_date');
            $table->string('deceased_relation')->nullable()->after('deceased_name');
            $table->date('date_of_death')->nullable()->after('deceased_relation');
            $table->string('lwop_duration')->nullable()->after('date_of_death');
        });

        // ── attendance_summary: replace boolean is_holiday with holiday_type string ──
        Schema::table('attendance_summary', function (Blueprint $table) {
            $table->dropColumn('is_holiday');
        });
        Schema::table('attendance_summary', function (Blueprint $table) {
            // null = regular work day
            // 'regular' = regular holiday (200% pay)
            // 'special_non_working' = special non-working (130% if work, 0% if absent)
            // 'special_working' = special working day (130% if work)
            $table->string('holiday_type')->nullable()->after('late_minutes');
        });

        // ── customers: fix varchar → decimal ──────────────────────────────────
        Schema::table('customers', function (Blueprint $table) {
            $table->decimal('balance', 10, 2)->default(500)->change();
            $table->decimal('charges', 10, 2)->default(0)->change();
        });
    }

    public function down(): void
    {
        Schema::table('payroll_and_leaves', function (Blueprint $table) {
            $table->string('po_total')->nullable();
            $table->string('po_consumed')->nullable();
            $table->string('min_scale')->nullable();
            $table->string('max_scale')->nullable();
            $table->string('wage_factor')->nullable();
            $table->string('probi_rate')->nullable();
            $table->dropColumn([
                'initial_transition_grant',
                'years_accrued_count',
                'vl_last_reset_at',
                'spl_consumed',
                'el_consumed',
                'bl_total',
                'bl_consumed',
            ]);
        });

        Schema::table('employee', function (Blueprint $table) {
            $table->dropColumn('is_solo_parent');
        });

        Schema::table('leaves', function (Blueprint $table) {
            $table->dropColumn([
                'is_paid',
                'child_number',
                'child_birth_date',
                'deceased_name',
                'deceased_relation',
                'date_of_death',
                'lwop_duration',
            ]);
        });

        Schema::table('attendance_summary', function (Blueprint $table) {
            $table->dropColumn('holiday_type');
        });
        Schema::table('attendance_summary', function (Blueprint $table) {
            $table->boolean('is_holiday')->default(false);
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->string('balance')->nullable();
            $table->string('charges')->nullable();
        });
    }
};
