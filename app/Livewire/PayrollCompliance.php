<?php

namespace App\Livewire;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PayrollCompliance extends Component
{
    // ── Shift Differential Calculator ─────────────────────────────────────
    public string $shiftIn = '22:00'; // default 7 PM

    public string $shiftOut = '06:00'; // default 7 AM next day

    public string $shiftDate = '';

    public string $shiftDayType = 'regular'; // regular | special | regular_holiday

    // Calculated results
    public float $regularHours = 0;

    public float $nightDiffHours = 0;

    public float $overtimeHours = 0;

    public float $grossPay = 0;

    // Sample hourly rate for preview
    public float $hourlyRate = 0;

    // ── OT Burn Rate ───────────────────────────────────────────────────────
    public float $otBudgetHours = 200; // HR-configurable monthly budget

    // ── 13th Month ─────────────────────────────────────────────────────────
    // No interactive inputs needed — computed from payroll_and_leaves

    public function mount(): void
    {
        $this->shiftDate = now()->toDateString();
    }

    // ─── Shift Differential ───────────────────────────────────────────────

    public function calculateShift(): void
    {
        if (! $this->shiftIn || ! $this->shiftOut || ! $this->shiftDate) {
            return;
        }

        $nightStart = Carbon::parse($this->shiftDate.' 22:00:00'); // 7 PM
        $nightEnd = Carbon::parse($this->shiftDate.' 06:00:00')->addDay(); // 6 AM next day

        $clockIn = Carbon::parse($this->shiftDate.' '.$this->shiftIn.':00');
        // If shiftOut < shiftIn time, it means next-day
        $clockOut = Carbon::parse($this->shiftDate.' '.$this->shiftOut.':00');
        if ($clockOut->lte($clockIn)) {
            $clockOut->addDay();
        }

        $totalHours = $clockIn->diffInMinutes($clockOut) / 60;

        // Regular shift is 8 hours; anything beyond is OT
        $regularShiftHours = 8.0;
        $this->overtimeHours = max(0, round($totalHours - $regularShiftHours, 2));
        $this->regularHours = min($totalHours, $regularShiftHours);

        // Night differential: overlap between worked period and 7PM–7AM window
        $ndStart = $clockIn->max($nightStart);
        $ndEnd = $clockOut->min($nightEnd);
        $this->nightDiffHours = $ndStart->lt($ndEnd)
            ? round($ndStart->diffInMinutes($ndEnd) / 60, 2)
            : 0;

        // Multipliers (DOLE PH)
        $multipliers = match ($this->shiftDayType) {
            'special' => ['regular' => 1.30, 'nd' => 1.375, 'ot' => 1.69],  // +30% rest day / special NWH
            'regular_holiday' => ['regular' => 2.00, 'nd' => 2.10,  'ot' => 2.60],  // +100% regular holiday
            default => ['regular' => 1.00, 'nd' => 1.10,  'ot' => 1.25],  // ordinary day
        };

        $rate = $this->hourlyRate;

        $ndOnlyHours = max(0, $this->nightDiffHours - $this->overtimeHours);
        $ndOtHours = min($this->nightDiffHours, $this->overtimeHours);
        $regOnlyHours = max(0, $this->regularHours - $this->nightDiffHours);

        $this->grossPay = round(
            ($regOnlyHours * $rate * $multipliers['regular'])
          + ($ndOnlyHours * $rate * $multipliers['nd'])
          + ($ndOtHours * $rate * $multipliers['ot'] * $multipliers['nd'])
          + (($this->overtimeHours - $ndOtHours) * $rate * $multipliers['ot']), 2);
    }

    // ─── Dashboard Data ────────────────────────────────────────────────────

    private function getGovernmentContributions(): array
    {
        // Reads from employment_details — SSS, PhilHealth, Pag-IBIG numbers exist
        $total = DB::table('employment_details')->count();
        $withSSS = DB::table('employment_details')->whereNotNull('sss_no')->where('sss_no', '!=', '')->count();
        $withPH = DB::table('employment_details')->whereNotNull('philhealth_no')->where('philhealth_no', '!=', '')->count();
        $withPI = DB::table('employment_details')->whereNotNull('pagibig_no')->where('pagibig_no', '!=', '')->count();
        $withTIN = DB::table('employment_details')->whereNotNull('tin_no')->where('tin_no', '!=', '')->count();

        return [
            'total' => $total,
            'sss' => $withSSS,
            'philhealth' => $withPH,
            'pagibig' => $withPI,
            'tin' => $withTIN,
        ];
    }

    private function getOTBurnRate(): array
    {
        $month = now()->month;
        $year = now()->year;

        $totalOT = DB::table('attendance_summary')
            ->whereMonth('attendance_date', $month)
            ->whereYear('attendance_date', $year)
            ->sum('overtime_hours');

        $totalOT = (float) $totalOT;

        return [
            'used' => round($totalOT, 1),
            'budget' => $this->otBudgetHours,
            'pct' => $this->otBudgetHours > 0
                            ? min(100, round(($totalOT / $this->otBudgetHours) * 100, 1))
                            : 0,
            'status' => $totalOT >= $this->otBudgetHours * 0.9 ? 'critical'
                       : ($totalOT >= $this->otBudgetHours * 0.7 ? 'warning' : 'ok'),
        ];
    }

    private function get13thMonthAccrual(): array
    {
        $year = now()->year;

        // 13th month = total basic pay for the year / 12
        // Approximated from monthly_rate in payroll_and_leaves
        $totalMonthlyRates = DB::table('payroll_and_leaves')->sum('monthly_rate');
        $totalMonthlyRates = (float) $totalMonthlyRates;

        // Months elapsed this year (Jan = 1 ... Dec = 12)
        $monthsElapsed = now()->month;
        $accrued = round(($totalMonthlyRates / 12) * $monthsElapsed, 2);
        $fullLiability = round($totalMonthlyRates, 2);

        return [
            'accrued' => $accrued,
            'full_liability' => $fullLiability,
            'months_elapsed' => $monthsElapsed,
            'pct' => $fullLiability > 0
                                ? round(($accrued / $fullLiability) * 100, 1)
                                : 0,
        ];
    }

    private function getTopNightDiffWorkers(): Collection
    {
        return DB::table('attendance_summary')
            ->join('users', 'attendance_summary.user_id', '=', 'users.id')
            ->whereMonth('attendance_date', now()->month)
            ->whereYear('attendance_date', now()->year)
            ->select('users.name', DB::raw('SUM(night_diff_hours) as total_nd'), DB::raw('SUM(overtime_hours) as total_ot'))
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('total_nd')
            ->limit(7)
            ->get();
    }

    public function render()
    {
        $this->calculateShift();

        return view('pages.HR.payroll-compliance', [
            'contributions' => $this->getGovernmentContributions(),
            'otBurnRate' => $this->getOTBurnRate(),
            'thirteenthMonth' => $this->get13thMonthAccrual(),
            'topNDWorkers' => $this->getTopNightDiffWorkers(),
        ])->layout('layouts.app');
    }
}
