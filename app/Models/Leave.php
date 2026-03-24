<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'leaves';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'leavetype',
        'department',
        'startdate',
        'enddate',
        'totaldays',
        'reason',
        'status',
        'approved_by',
        'remarks',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'startdate' => 'date',
        'enddate' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['formatted_leave_type', 'formatted_status'];

    /**
     * Get the user that owns the leave request.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the approver of the leave request.
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the leave type in readable format.
     */
    public function getFormattedLeaveTypeAttribute()
    {
        return ucfirst($this->leavetype);
    }

    /**
     * Get the status in readable format.
     */
    public function getFormattedStatusAttribute()
    {
        return ucfirst($this->status);
    }

    /**
     * Get the status badge class.
     */
    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            'approved' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
            'pending' => 'bg-yellow-100 text-yellow-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    /**
     * Get the leave type badge class.
     */
    public function getLeaveTypeBadgeClassAttribute()
    {
        return match(strtolower($this->leavetype)) {
            'sick leave' => 'bg-red-50 text-red-600',
            'vacation leave' => 'bg-blue-50 text-blue-600',
            'emergency leave' => 'bg-orange-50 text-orange-600',
            default => 'bg-green-50 text-green-600',
        };
    }

    /**
     * Check if the leave is pending.
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if the leave is approved.
     */
    public function isApproved()
    {
        return $this->status === 'approved';
    }

    /**
     * Check if the leave is rejected.
     */
    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    /**
     * Calculate the duration in days.
     */
    public function calculateDuration()
    {
        $start = \Carbon\Carbon::parse($this->startdate);
        $end = \Carbon\Carbon::parse($this->enddate);
        return $start->diffInDays($end) + 1;
    }

    /**
     * Scope a query to only include pending leaves.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include approved leaves.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope a query to only include rejected leaves.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Scope a query to only include leaves for a specific user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope a query to only include leaves for a specific department.
     */
    public function scopeForDepartment($query, $department)
    {
        return $query->where('department', $department);
    }

    /**
     * Scope a query to only include leaves within a date range.
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('startdate', [$startDate, $endDate])
                     ->orWhereBetween('enddate', [$startDate, $endDate]);
    }
}