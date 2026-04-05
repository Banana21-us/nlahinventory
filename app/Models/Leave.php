<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    use HasFactory;

    protected $table = 'leaves';

    protected $fillable = [
        'user_id',
        'leave_type',
        'start_date',
        'end_date',
        'total_days',
        'day_part',
        'reason',
        'reliever',
        'attachment',
        'date_requested',
        'dept_head_status',
        'dept_head_approved_at',
        'dept_head_id',
        'hr_status',
        'hr_approved_at',
        'approved_by',
        'remarks',
        'rejection_reason',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
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

    /**
     * Total lifetime VL entitlement for a user.
     *
     * Each calendar year from hire year → current year the employee earns a
     * tier-based allocation.  Unused days carry forward indefinitely.
     *
     * Tiers (years of service at Jan 1 of each year):
     *   < 7 yrs  → 10 days/yr
     *   7–14 yrs → 15 days/yr
     *   ≥ 15 yrs → 20 days/yr
     */
    public static function availableVLCredits(int $userId): float
    {
        $vlTypes = ['Vacation Leave', 'Birthday Leave'];

        $employeeId = \DB::table('employee')->where('user_id', $userId)->value('id');
        $emp = $employeeId ? \DB::table('employment_details')->where('employee_id', $employeeId)->first() : null;
        $user = User::find($userId);
        $hireDate = Carbon::parse($emp?->hiring_date ?? $user->created_at);

        $hireYear = (int) $hireDate->format('Y');
        $currentYear = (int) now()->format('Y');

        // Sum up entitlement for every year from hire to now (VL carries over)
        $totalEarned = 0;
        for ($year = $hireYear; $year <= $currentYear; $year++) {
            // Years of service at the start of this year (0 for hire year)
            $yearsService = $year === $hireYear
                ? 0
                : (int) $hireDate->diffInYears(Carbon::create($year, 1, 1));

            $totalEarned += match (true) {
                $yearsService >= 15 => 20,
                $yearsService >= 7 => 15,
                default => 10,
            };
        }

        // All-time VL used (carryover means we look at total usage, not just this year)
        // cancellation_requested still counts until HR confirms the cancellation
        $totalUsed = self::where('user_id', $userId)
            ->whereIn('leave_type', $vlTypes)
            ->whereIn('hr_status', ['pending', 'approved', 'cancellation_requested'])
            ->sum('total_days');

        return max(0, $totalEarned - $totalUsed);
    }

    /**
     * Available SL credits for the current year only (resets every Jan 1).
     */
    public static function availableSLCredits(int $userId): float
    {
        $slTypes = ['Sick Leave'];

        $usedSL = self::where('user_id', $userId)
            ->whereIn('leave_type', $slTypes)
            ->whereIn('hr_status', ['pending', 'approved', 'cancellation_requested'])
            ->whereYear('start_date', now()->year)
            ->sum('total_days');

        return max(0, 3 - $usedSL);
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
