<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayoffApplication extends Model
{
    protected $fillable = [
        'user_id',
        'start_datetime',
        'end_datetime',
        'hours',
        'lunch_break_deducted',
        'reason',
        'status',
        'approved_by',
        'dept_head_status',
        'dept_head_approved_by',
        'hr_status',
        'hr_approved_by',
        'accounting_status',
        'accounting_approved_by',
    ];

    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
        'hours' => 'float',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function deptHeadApprover(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dept_head_approved_by');
    }

    public function hrApprover(): BelongsTo
    {
        return $this->belongsTo(User::class, 'hr_approved_by');
    }

    public function accountingApprover(): BelongsTo
    {
        return $this->belongsTo(User::class, 'accounting_approved_by');
    }
}
