<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayrollAndLeave extends Model
{
    protected $table = 'payroll_and_leaves';

    protected $fillable = [
        'employee_id',
        'user_id',
        'salary_rate',
        'min_scale',
        'max_scale',
        'wage_factor',
        'daily_rate',
        'monthly_rate',
        'cola',
        'grocery_allowance',
        'night_diff_factor',
        'probi_rate',
        'vl_total',
        'vl_consumed',
        'initial_transition_grant',
        'years_accrued_count',
        'vl_last_reset_at',
        'sl_total',
        'sl_consumed',
        'spl_total',
        'spl_consumed',
        'el_total',
        'el_consumed',
        'bl_total',
        'bl_consumed',
        'ml_total',
        'ml_consumed',
        'pl_total',
        'pl_consumed',
        'syl_total',
        'syl_consumed',
        'cal_total',
        'cal_consumed',
        'stl_total',
        'stl_consumed',
        'mwl_total',
        'mwl_consumed',
    ];

    protected $casts = [
        'salary_rate' => 'decimal:2',
        'min_scale' => 'decimal:2',
        'max_scale' => 'decimal:2',
        'wage_factor' => 'decimal:4',
        'daily_rate' => 'decimal:2',
        'monthly_rate' => 'decimal:2',
        'cola' => 'decimal:2',
        'grocery_allowance' => 'decimal:2',
        'night_diff_factor' => 'decimal:2',
        'probi_rate' => 'decimal:4',
        'vl_total' => 'decimal:2',
        'vl_consumed' => 'decimal:2',
        'initial_transition_grant' => 'decimal:1',
        'vl_last_reset_at' => 'date',
        'sl_total' => 'decimal:2',
        'sl_consumed' => 'decimal:2',
        'spl_total' => 'decimal:2',
        'spl_consumed' => 'decimal:2',
        'el_total' => 'decimal:2',
        'el_consumed' => 'decimal:2',
        'bl_total' => 'decimal:1',
        'bl_consumed' => 'decimal:1',
        'ml_total' => 'decimal:2',
        'ml_consumed' => 'decimal:2',
        'pl_total' => 'decimal:2',
        'pl_consumed' => 'decimal:2',
        'syl_total' => 'decimal:2',
        'syl_consumed' => 'decimal:2',
        'cal_total' => 'decimal:2',
        'cal_consumed' => 'decimal:2',
        'stl_total' => 'decimal:2',
        'stl_consumed' => 'decimal:2',
        'mwl_total' => 'decimal:2',
        'mwl_consumed' => 'decimal:2',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
