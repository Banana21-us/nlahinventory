<?php

namespace App\Services;

use App\Models\LeaveBalance;
use App\Models\LeaveType;
use App\Models\PayrollAndLeave;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LeaveAccrualService
{
    /**
     * Compute the expected regularization date based on hiring date.
     * Philippine Labor Code: probationary period is 6 months.
     */
    public function computeExpectedRegularizationDate(Carbon $hiringDate): Carbon
    {
        return $hiringDate->copy()->addMonths(6);
    }

    /**
     * Called when HR sets regularization_date on an employee.
     * No VL is granted immediately — the first VL grant happens on the next Jan 1
     * via processAnnualReset(). This method only ensures the payroll metadata
     * record and the VL balance row exist.
     */
    public function onRegularization(User $user): void
    {
        $employeeId = DB::table('employee')->where('user_id', $user->id)->value('id');
        $detail = $employeeId
            ? DB::table('employment_details')->where('employee_id', $employeeId)->first()
            : null;

        if (! $detail?->regularization_date) {
            Log::warning('LeaveAccrualService::onRegularization — no regularization_date', [
                'user_id' => $user->id,
            ]);

            return;
        }

        DB::transaction(function () use ($user, $employeeId) {
            // Ensure the payroll metadata record exists for accrual tracking.
            $payroll = $employeeId
                ? PayrollAndLeave::where('employee_id', $employeeId)->first()
                : null;

            $payroll ??= PayrollAndLeave::where('user_id', $user->id)->first();

            if (! $payroll) {
                $payroll = PayrollAndLeave::create([
                    'employee_id' => $employeeId,
                    'user_id' => $user->id,
                    'initial_transition_grant' => 0,
                    'years_accrued_count' => 0,
                ]);
            } else {
                $payroll->initial_transition_grant = 0;
                $payroll->years_accrued_count = 0;
                $payroll->save();
            }

            // Ensure VL balance row exists with 0 balance.
            // VL will be granted on the next Jan 1 by processAnnualReset().
            $vlType = LeaveType::where('code', 'VL')->first();

            if ($vlType) {
                LeaveBalance::firstOrCreate(
                    ['user_id' => $user->id, 'leave_type_id' => $vlType->id],
                    ['total' => 0, 'consumed' => 0],
                );
            }
        });

        Log::info('LeaveAccrualService::onRegularization — record initialized, VL granted on next Jan 1', [
            'user_id' => $user->id,
        ]);
    }

    /**
     * VL increments are now calendar-year based (processed every Jan 1 by
     * processAnnualReset). This method is kept for backwards compatibility
     * but does nothing.
     */
    public function processAnniversary(User $user): void
    {
        Log::info('LeaveAccrualService::processAnniversary — skipped, VL is now calendar-year based', [
            'user_id' => $user->id,
        ]);
    }

    /**
     * Run every January 1. Handles two things:
     *
     * 1. VL annual grant (calendar-year based):
     *    - First Jan 1 after regularization:
     *        floor(completed_full_months_as_regular_in_reg_year × 10 / 12)
     *    - Subsequent Jan 1s:
     *        +10 VL if < 8 completed years since regularization
     *        +15 VL if >= 8 completed years since regularization
     *    Unused VL carries over automatically (we increment total, never reset it).
     *
     * 2. Annual reset for SL, BL, and SPL (reset to standard allocation).
     */
    public function processAnnualReset(User $user): void
    {
        $employeeId = DB::table('employee')->where('user_id', $user->id)->value('id');

        $detail = $employeeId
            ? DB::table('employment_details')->where('employee_id', $employeeId)->first()
            : DB::table('employment_details')
                ->whereIn('employee_id', DB::table('employee')->where('user_id', $user->id)->pluck('id'))
                ->first();

        $regDate = $detail?->regularization_date
            ? Carbon::parse($detail->regularization_date)
            : null;

        DB::transaction(function () use ($user, $employeeId, $regDate) {
            // ── VL grant ──────────────────────────────────────────────────────
            if ($regDate) {
                $currentYear = now()->year;
                $yearDiff = $currentYear - $regDate->year;
                $vlGrant = 0;

                if ($yearDiff === 1) {
                    // First Jan 1 after regularization — prorate for the months
                    // the employee was already regular last year.
                    // Only full calendar months count: if regularized on the 1st,
                    // that month counts; otherwise the first full month is the next.
                    $firstFullMonth = ($regDate->day === 1)
                        ? $regDate->month
                        : $regDate->month + 1;

                    $fullMonths = max(0, 12 - $firstFullMonth + 1);
                    $vlGrant = (int) floor($fullMonths * 10 / 12);
                } elseif ($yearDiff >= 2) {
                    // Completed full years of regular service as of Jan 1.
                    $completedYears = (int) $regDate->diffInYears(Carbon::create($currentYear, 1, 1));

                    $vlGrant = match (true) {
                        $completedYears >= 15 => 20, // 15+ years
                        $completedYears >= 7 => 15, // 7–14 years
                        default => 10, // 1–6 years
                    };
                }
                // yearDiff === 0: regularized this year — no Jan 1 VL yet.

                if ($vlGrant > 0) {
                    $vlType = LeaveType::where('code', 'VL')->first();

                    if ($vlType) {
                        LeaveBalance::firstOrCreate(
                            ['user_id' => $user->id, 'leave_type_id' => $vlType->id],
                            ['total' => 0, 'consumed' => 0],
                        )->increment('total', $vlGrant);

                        Log::info('LeaveAccrualService::processAnnualReset — VL granted', [
                            'user_id' => $user->id,
                            'year' => $currentYear,
                            'grant' => $vlGrant,
                            'year_diff' => $yearDiff,
                            'completed_years' => $yearDiff >= 2
                                ? (int) $regDate->diffInYears(Carbon::create($currentYear, 1, 1))
                                : 0,
                        ]);
                    }
                }
            }

            // ── SL / BL / SPL reset ───────────────────────────────────────────
            $isSoloParent = $employeeId
                ? (bool) DB::table('employee')->where('id', $employeeId)->value('is_solo_parent')
                : false;

            $resets = [
                'SL' => ['total' => 10, 'consumed' => 0],
                'BL' => ['total' => 1,  'consumed' => 0],
            ];

            if ($isSoloParent) {
                $resets['SPL'] = ['total' => 7, 'consumed' => 0];
            }

            foreach ($resets as $code => $values) {
                $lt = LeaveType::where('code', $code)->first();

                if ($lt) {
                    LeaveBalance::updateOrCreate(
                        ['user_id' => $user->id, 'leave_type_id' => $lt->id],
                        $values,
                    );
                }
            }
        });

        Log::info('LeaveAccrualService::processAnnualReset — completed', [
            'user_id' => $user->id,
            'name' => $user->name,
        ]);
    }

    /**
     * DOLE holiday pay multiplier.
     *
     * @param  string  $holidayType  'regular' | 'special_non_working' | 'special_working'
     */
    public function getHolidayMultiplier(
        string $holidayType,
        bool $didWork,
        bool $isOvertime = false,
        bool $isRestDay = false
    ): float {
        return match (true) {
            $holidayType === 'regular' && $didWork && ($isOvertime || $isRestDay) => 2.60,
            $holidayType === 'regular' && $didWork => 2.00,
            $holidayType === 'regular' && ! $didWork => 1.00,
            $holidayType === 'special_non_working' && $didWork && $isOvertime => 1.69,
            $holidayType === 'special_non_working' && $didWork => 1.30,
            $holidayType === 'special_non_working' && ! $didWork => 0.00,
            $holidayType === 'special_working' && $didWork => 1.30,
            $holidayType === 'special_working' && ! $didWork => 1.00,
            default => 1.00,
        };
    }
}
