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
     * Called on the employee's hiring anniversary (Feb 19 each year).
     * Grants SL prorated from the anniversary date to Dec 31 of that year.
     * Only applies on the FIRST anniversary after hiring.
     */
    public function processAnniversary(User $user): void
    {
        $employeeId = DB::table('employee')->where('user_id', $user->id)->value('id');

        $detail = $employeeId
            ? DB::table('employment_details')->where('employee_id', $employeeId)->first()
            : null;

        $hiringDate = $detail?->hiring_date ? Carbon::parse($detail->hiring_date) : null;

        if (! $hiringDate) {
            return;
        }

        $today = now()->startOfDay();
        $anniversaryThisYear = $hiringDate->copy()->year($today->year);

        // Only run on the actual anniversary date
        if (! $today->is($anniversaryThisYear)) {
            return;
        }

        $firstAnniversary = $hiringDate->copy()->addYear();

        // Only grant on the FIRST anniversary
        if (! $today->is($firstAnniversary)) {
            return;
        }

        DB::transaction(function () use ($user, $hiringDate) {
            $slType = LeaveType::where('code', 'SL')->first();

            if (! $slType) {
                return;
            }

            // SL proration based on hiring date (table-driven)
            $m = $hiringDate->month;
            $d = $hiringDate->day;

            $slProrated = match (true) {
                ($m === 1) || ($m === 2) || ($m === 3 && $d <= 14) => 5,
                ($m === 3 && $d >= 15) || ($m === 4) || ($m === 5 && $d <= 27) => 4,
                ($m === 5 && $d >= 28) || ($m === 6) || ($m === 7) || ($m === 8 && $d <= 9) => 3,
                ($m === 8 && $d >= 10) || ($m === 9) || ($m === 10 && $d <= 22) => 2,
                default => 1, // Oct 23 – Dec 31
            };

            LeaveBalance::updateOrCreate(
                ['user_id' => $user->id, 'leave_type_id' => $slType->id],
                ['total' => $slProrated, 'consumed' => 0],
            );

            Log::info('LeaveAccrualService::processAnniversary — SL prorated on first anniversary', [
                'user_id' => $user->id,
                'hiring_date' => $hiringDate->toDateString(),
                'anniversary' => $hiringDate->copy()->addYear()->toDateString(),
                'sl_granted' => $slProrated,
            ]);
        });
    }

    /**
     * Run every January 1. Handles two things:
     *
     * 1. VL annual grant (calendar-year based):
     *    - First Jan 1 after regularization:
     *        Table-based proration using regularization_date
     *    - Subsequent Jan 1s:
     *        +10 VL if < 7 years of service
     *        +15 VL if 7–14 years of service
     *        +20 VL if 15+ years of service
     *    Years of service use the NLAH AWY formula: pre-2023 period (4 AWY = 1 yr), 2023+ (1 AWY = 1 yr).
     *    Unused VL carries over, capped at 20 total remaining.
     *
     * 2. Annual reset for SL, BL, SYL, and SPL (reset to standard allocation).
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

        $hiringDate = $detail?->hiring_date ? Carbon::parse($detail->hiring_date) : null;

        DB::transaction(function () use ($user, $employeeId, $regDate, $hiringDate) {
            // ── VL grant ──────────────────────────────────────────────────────
            if ($regDate) {
                $currentYear = now()->year;
                $yearDiff = $currentYear - $regDate->year;
                $vlGrant = 0;

                if ($yearDiff === 1) {
                    // First Jan 1 after regularization — table-based proration
                    // based on regularization_date
                    $m = $regDate->month;
                    $d = $regDate->day;

                    $vlGrant = match (true) {
                        ($m === 1) || ($m === 2) || ($m === 3 && $d <= 14) => 5,
                        ($m === 3 && $d >= 15) || ($m === 4) || ($m === 5 && $d <= 26) => 4,
                        ($m === 5 && $d >= 27) || ($m === 6) || ($m === 7) || ($m === 8 && $d <= 7) => 3,
                        ($m === 8 && $d >= 8) || ($m === 9) || ($m === 10 && $d <= 19) => 2,
                        default => 1, // Oct 20 – Dec 31
                    };
                } elseif ($yearDiff >= 2) {
                    // Use NLAH AWY formula: pre-2023 period is 4 AWY = 1 yr of service;
                    // 2023 onwards is 1 AWY = 1 yr. Falls back to calendar diff if no hiring date.
                    $serviceYears = $hiringDate
                        ? $this->computeYearsOfService($hiringDate, Carbon::create($currentYear, 1, 1))
                        : (int) $regDate->diffInYears(Carbon::create($currentYear, 1, 1));

                    $vlGrant = match (true) {
                        $serviceYears >= 15 => 20,
                        $serviceYears >= 7 => 15,
                        default => 10,
                    };
                }
                // yearDiff === 0: regularized this year — no Jan 1 VL yet.

                if ($vlGrant > 0) {
                    $vlType = LeaveType::where('code', 'VL')->first();

                    if ($vlType) {
                        $balance = LeaveBalance::firstOrCreate(
                            ['user_id' => $user->id, 'leave_type_id' => $vlType->id],
                            ['total' => 0, 'consumed' => 0],
                        );

                        // Cap remaining at 20; add only enough to reach the cap.
                        $remaining = max(0, (float) $balance->total - (float) $balance->consumed);
                        $toAdd = max(0, min(20, $remaining + $vlGrant) - $remaining);

                        if ($toAdd > 0) {
                            $balance->increment('total', $toAdd);
                        }

                        $excess = max(0, ($remaining + $vlGrant) - 20);

                        Log::info('LeaveAccrualService::processAnnualReset — VL granted', [
                            'user_id' => $user->id,
                            'year' => $currentYear,
                            'grant' => $vlGrant,
                            'added' => $toAdd,
                            'excess_for_cash' => $excess,
                            'new_remaining' => min(20, $remaining + $vlGrant),
                        ]);
                    }
                }
            }

            // ── SL reset (Jan 1 grant for years after first anniversary) ─────
            $slGrant = 0;

            if ($regDate && $hiringDate) {
                $firstAnniversary = $hiringDate->copy()->addYear();
                $currentYear = now()->year;

                if ($firstAnniversary->year < $currentYear) {
                    // First anniversary was before this year: full 5 SL
                    $slGrant = 5;
                } else {
                    // First anniversary is this year or later: 0 (granted on anniversary)
                    $slGrant = 0;
                }
            }

            $slType = LeaveType::where('code', 'SL')->first();
            if ($slType) {
                LeaveBalance::updateOrCreate(
                    ['user_id' => $user->id, 'leave_type_id' => $slType->id],
                    ['total' => $slGrant, 'consumed' => 0],
                );
            }

            // ── SYL reset (3 days every Jan 1) ────────────────────────────
            $sylType = LeaveType::where('code', 'SYL')->first();
            if ($sylType) {
                LeaveBalance::updateOrCreate(
                    ['user_id' => $user->id, 'leave_type_id' => $sylType->id],
                    ['total' => 3, 'consumed' => 0],
                );
            }

            // ── BL / SPL reset ────────────────────────────────────────────────
            $isSoloParent = $employeeId
                ? (bool) DB::table('employee')->where('id', $employeeId)->value('is_solo_parent')
                : false;

            $resets = [
                'BL' => ['total' => 1, 'consumed' => 0],
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
     * NLAH AWY (Actual Working Years) formula for years of service.
     *
     * Pre-2023 period: 4 AWY = 1 year of service (hospital-specific policy).
     * 2023 onwards: 1 AWY = 1 year of service.
     *
     * Each "AWY" is counted by hiring-date anniversary completions within each period.
     * Example: Hired Jan 2014, asOf Jan 2025 →
     *   pre-2023 anniversaries (Jan2015–Jan2022) = 8 → floor(8/4) = 2 service years
     *   post-2022 anniversaries (Jan2023–Jan2024) = 2 service years
     *   Total = 4 years of service
     */
    public function computeYearsOfService(Carbon $hiringDate, Carbon $asOf): int
    {
        $cutoff = Carbon::create(2023, 1, 1);
        $pre2023Count = 0;
        $post2022Count = 0;

        $anniversary = $hiringDate->copy()->addYear();
        while ($anniversary->lt($asOf)) {
            if ($anniversary->lt($cutoff)) {
                $pre2023Count++;
            } else {
                $post2022Count++;
            }
            $anniversary->addYear();
        }

        return (int) floor($pre2023Count / 4) + $post2022Count;
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
