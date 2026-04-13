<?php

namespace App\Services;

use App\Models\PayrollAndLeave;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LeaveAccrualService
{
    /**
     * Compute the initial VL transition grant based on the month of regularization.
     * Month-based, leap-year safe.
     *
     * Jan–Mar → 5 days
     * Apr–May → 4 days
     * Jun–Aug → 3 days
     * Sep–Oct → 2 days
     * Nov–Dec → 1 day
     */
    public function computeTransitionGrant(Carbon $regularizationDate): int
    {
        $month = (int) $regularizationDate->month;

        return match (true) {
            $month <= 3  => 5,
            $month <= 5  => 4,
            $month <= 8  => 3,
            $month <= 10 => 2,
            default      => 1,
        };
    }

    /**
     * Compute the expected regularization date based on hiring date.
     * Philippine probationary period = 6 months (≈ 180 calendar days).
     * Hospital policy shortens this to 73 working days; we approximate as
     * 73 × 7/5 ≈ 102 calendar days for scheduling purposes.
     * The exact gate is tracked via employment_details.regularization_date.
     */
    public function computeExpectedRegularizationDate(Carbon $hiringDate): Carbon
    {
        // Philippine Labor Code: probationary period is 6 months from hiring date
        return $hiringDate->copy()->addMonths(6);
    }

    /**
     * Grant the transition VL when an employee is regularized.
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

        $grant = $this->computeTransitionGrant(Carbon::parse($detail->regularization_date));

        DB::transaction(function () use ($user, $employeeId, $grant) {
            $payroll = $employeeId
                ? PayrollAndLeave::where('employee_id', $employeeId)->first()
                : null;

            $payroll ??= PayrollAndLeave::where('user_id', $user->id)->first();

            if (! $payroll) {
                $payroll = PayrollAndLeave::create([
                    'employee_id'              => $employeeId,
                    'user_id'                  => $user->id,
                    'initial_transition_grant' => 0,
                    'vl_total'                 => 0,
                    'years_accrued_count'      => 0,
                ]);
            }

            $payroll->initial_transition_grant = $grant;
            $payroll->vl_total += $grant;
            $payroll->years_accrued_count = 0;
            $payroll->save();
        });

        Log::info('LeaveAccrualService::onRegularization — grant applied', [
            'user_id' => $user->id,
            'grant'   => $grant,
        ]);
    }

    /**
     * Process annual VL anniversary increment for an employee.
     *
     * years_accrued_count = 0 (Year 1):  skip — no increment yet
     * count 1–5  (Years 2–6):  +10 VL/yr, increment count
     * count 6–14 (Years 7–15): +15 VL/yr, increment count
     * count >= 15 (Year 15+):  cap at 20, reset consumed, mark date
     */
    public function processAnniversary(User $user): void
    {
        $employeeId = DB::table('employee')->where('user_id', $user->id)->value('id');

        $payroll = $employeeId
            ? PayrollAndLeave::where('employee_id', $employeeId)->first()
            : null;

        $payroll ??= PayrollAndLeave::where('user_id', $user->id)->first();

        if (! $payroll) {
            Log::warning('LeaveAccrualService::processAnniversary — no payroll record', [
                'user_id' => $user->id,
            ]);

            return;
        }

        DB::transaction(function () use ($user, $payroll) {
            $count = (int) $payroll->years_accrued_count;

            if ($count === 0) {
                // Year 1: no increment, but track the anniversary
                Log::info('LeaveAccrualService::processAnniversary — Year 1, no increment', [
                    'user_id' => $user->id,
                ]);

                return;
            }

            if ($count >= 15) {
                // Year 15+: cap at 20/yr, reset consumed, record date
                $payroll->vl_total       = 20;
                $payroll->vl_consumed    = 0;
                $payroll->vl_last_reset_at = now()->toDateString();
                $action = 'capped at 20, consumed reset';
            } elseif ($count >= 6) {
                // Years 7–15: +15/yr
                $payroll->vl_total          += 15;
                $payroll->years_accrued_count = $count + 1;
                $action = '+15 VL';
            } else {
                // Years 2–6: +10/yr
                $payroll->vl_total          += 10;
                $payroll->years_accrued_count = $count + 1;
                $action = '+10 VL';
            }

            $payroll->save();

            Log::info('LeaveAccrualService::processAnniversary — processed', [
                'user_id' => $user->id,
                'name'    => $user->name,
                'action'  => $action,
            ]);
        });
    }

    /**
     * Reset annual leave balances on January 1.
     * Resets SL, BL, and SPL (if solo parent). Does NOT touch VL.
     */
    public function processAnnualReset(User $user): void
    {
        $employeeId = DB::table('employee')->where('user_id', $user->id)->value('id');

        $payroll = $employeeId
            ? PayrollAndLeave::where('employee_id', $employeeId)->first()
            : null;

        $payroll ??= PayrollAndLeave::where('user_id', $user->id)->first();

        if (! $payroll) {
            Log::warning('LeaveAccrualService::processAnnualReset — no payroll record', [
                'user_id' => $user->id,
            ]);

            return;
        }

        DB::transaction(function () use ($user, $employeeId, $payroll) {
            $payroll->sl_total    = 10;
            $payroll->sl_consumed = 0;
            $payroll->bl_total    = 1;
            $payroll->bl_consumed = 0;

            $isSoloParent = $employeeId
                ? (bool) DB::table('employee')->where('id', $employeeId)->value('is_solo_parent')
                : false;

            if ($isSoloParent) {
                $payroll->spl_total    = 7;
                $payroll->spl_consumed = 0;
            }

            $payroll->save();
        });

        Log::info('LeaveAccrualService::processAnnualReset — reset applied', [
            'user_id' => $user->id,
            'name'    => $user->name,
        ]);
    }

    /**
     * DOLE holiday pay multiplier.
     *
     * @param  string  $holidayType  'regular' | 'special_non_working' | 'special_working'
     * @param  bool    $didWork      Whether the employee worked on the holiday
     * @param  bool    $isOvertime   Whether it is an overtime/rest-day scenario
     * @param  bool    $isRestDay    Whether the holiday falls on a rest day
     */
    public function getHolidayMultiplier(
        string $holidayType,
        bool $didWork,
        bool $isOvertime = false,
        bool $isRestDay = false
    ): float {
        return match (true) {
            $holidayType === 'regular'              && $didWork  && ($isOvertime || $isRestDay) => 2.60,
            $holidayType === 'regular'              && $didWork                                 => 2.00,
            $holidayType === 'regular'              && ! $didWork                               => 1.00,
            $holidayType === 'special_non_working'  && $didWork  && $isOvertime                 => 1.69,
            $holidayType === 'special_non_working'  && $didWork                                 => 1.30,
            $holidayType === 'special_non_working'  && ! $didWork                               => 0.00,
            $holidayType === 'special_working'      && $didWork                                 => 1.30,
            $holidayType === 'special_working'      && ! $didWork                               => 1.00,
            default                                                                             => 1.00,
        };
    }
}
