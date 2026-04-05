<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceSummary extends Model
{
    protected $table = 'attendance_summary';

    protected $fillable = [
        'user_id', 'attendance_date', 'shift_type',
        'clock_in', 'clock_out',
        'am_in', 'am_out', 'pm_in', 'pm_out',
        'total_hours', 'regular_hours', 'night_diff_hours', 'overtime_hours',
        'late_minutes', 'is_holiday', 'status', 'email_sent',
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'clock_in' => 'datetime',
        'clock_out' => 'datetime',
        'is_holiday' => 'boolean',
        'email_sent' => 'boolean',
        'total_hours' => 'decimal:2',
        'regular_hours' => 'decimal:2',
        'night_diff_hours' => 'decimal:2',
        'overtime_hours' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'on_time' => 'On Time',
            'late_am' => 'Late AM',
            'late_pm' => 'Late PM',
            'late_both' => 'Late AM & PM',
            'late' => 'Late',
            'half_day_am' => 'Half Day (AM)',
            'half_day_pm' => 'Half Day (PM)',
            'overtime' => 'Overtime',
            default => 'Absent',
        };
    }
}
