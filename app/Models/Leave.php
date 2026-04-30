<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    use HasFactory;

    protected $table = 'leaves';

    protected $fillable = [
        'user_id',
        'leave_type',
        'is_paid',
        'start_date',
        'end_date',
        'total_days',
        'day_part',
        'reason',
        'reliever',
        'attachment',
        'child_number',
        'child_birth_date',
        'deceased_name',
        'deceased_relation',
        'date_of_death',
        'lwop_duration',
        'date_requested',
        'dept_head_status',
        'dept_head_approved_at',
        'dept_head_id',
        'hr_status',
        'hr_approved_at',
        'approved_by',
        'remarks',
        'rejection_reason',
        'cancellation_dhead_status',
        'cancellation_status',
    ];

    protected $casts = [
        'is_paid' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
        'child_birth_date' => 'date',
        'date_of_death' => 'date',
        'date_requested' => 'date',
        'dept_head_approved_at' => 'datetime',
        'hr_approved_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function deptHead()
    {
        return $this->belongsTo(User::class, 'dept_head_id');
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopePending($query)
    {
        return $query->where('hr_status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('hr_status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('hr_status', 'rejected');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('start_date', [$startDate, $endDate])
            ->orWhereBetween('end_date', [$startDate, $endDate]);
    }

    // ─── Credit Helpers ───────────────────────────────────────────────────────

    public static function availableVLCredits(int $userId): float
    {
        $payroll = PayrollAndLeave::where('user_id', $userId)->first();
        if (! $payroll) {
            return 0;
        }

        return max(0, $payroll->vl_total - $payroll->vl_consumed);
    }

    public static function availableSLCredits(int $userId): float
    {
        $payroll = PayrollAndLeave::where('user_id', $userId)->first();
        if (! $payroll) {
            return 0;
        }

        return max(0, $payroll->sl_total - $payroll->sl_consumed);
    }

    public static function availableBLCredits(int $userId): float
    {
        $payroll = PayrollAndLeave::where('user_id', $userId)->first();
        if (! $payroll) {
            return 0;
        }

        return max(0, $payroll->bl_total - $payroll->bl_consumed);
    }

    public static function availableSPLCredits(int $userId): float
    {
        $payroll = PayrollAndLeave::where('user_id', $userId)->first();
        if (! $payroll) {
            return 0;
        }

        return max(0, $payroll->spl_total - $payroll->spl_consumed);
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    public function isPending(): bool
    {
        return $this->hr_status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->hr_status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->hr_status === 'rejected';
    }

    public function calculateDuration(): int
    {
        return $this->start_date->diffInDays($this->end_date) + 1;
    }
}
