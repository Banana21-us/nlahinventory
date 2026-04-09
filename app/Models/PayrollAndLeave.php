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
        'daily_rate',
        'monthly_rate',
        'cola',
        'grocery_allowance',
        'night_diff_factor',
        'vl_total',
        'vl_consumed',
        'sl_total',
        'sl_consumed',
        'spl_total',
        'el_total',
        'min_scale',
        'max_scale',
        'wage_factor',
        'po_consumed',
        'po_total',
        'probi_rate',
    ];

    protected $casts = [
        'salary_rate' => 'decimal:2',
        'daily_rate' => 'decimal:2',
        'monthly_rate' => 'decimal:2',
        'cola' => 'decimal:2',
        'grocery_allowance' => 'decimal:2',
        'night_diff_factor' => 'decimal:2',
        'vl_total' => 'decimal:2',
        'vl_consumed' => 'decimal:2',
        'sl_total' => 'decimal:2',
        'sl_consumed' => 'decimal:2',
        'spl_total' => 'decimal:2',
        'el_total' => 'decimal:2',
        'min_scale' => 'decimal:2',
        'max_scale' => 'decimal:2',
        'wage_factor' => 'decimal:2',
        'po_consumed' => 'decimal:2',
        'po_total' => 'decimal:2',
        'probi_rate' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Employee::class);
    }
}
