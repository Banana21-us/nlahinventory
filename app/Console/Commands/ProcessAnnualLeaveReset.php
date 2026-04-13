<?php

namespace App\Console\Commands;

use App\Models\PayrollAndLeave;
use App\Models\User;
use App\Services\LeaveAccrualService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ProcessAnnualLeaveReset extends Command
{
    protected $signature = 'leave:annual-reset';

    protected $description = 'Reset annual leave balances (SL, BL, SPL) for all active employees — runs January 1 only';

    public function handle(LeaveAccrualService $service): void
    {
        if (! Carbon::today()->isStartOfYear()) {
            $this->info('Annual leave reset skipped — today is not January 1.');

            return;
        }

        $users = User::where('is_active', true)
            ->whereHas('employmentDetail')
            ->whereExists(function ($query) {
                $query->from('payroll_and_leaves')
                      ->whereColumn('payroll_and_leaves.user_id', 'users.id');
            })
            ->get();

        $count = 0;
        foreach ($users as $user) {
            $service->processAnnualReset($user);
            $count++;
        }

        $this->info("Annual leave reset applied to {$count} employee(s).");
        Log::info('leave:annual-reset — completed', ['count' => $count, 'date' => Carbon::today()->toDateString()]);
    }
}
