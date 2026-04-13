<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\LeaveAccrualService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ProcessLeaveAnniversaries extends Command
{
    protected $signature = 'leave:process-anniversaries';

    protected $description = 'Process VL anniversary increments for employees whose hiring date matches today';

    public function handle(LeaveAccrualService $service): void
    {
        $today = Carbon::today();
        $month = $today->month;
        $day = $today->day;

        // Leap-year guard: if today is Feb 28 in a non-leap year,
        // also process employees hired on Feb 29.
        $includeFeb29 = ($month === 2 && $day === 28 && ! $today->isLeapYear());

        $users = User::where('is_active', true)
            ->whereHas('employmentDetail', function ($q) use ($month, $day, $includeFeb29) {
                $q->whereMonth('hiring_date', $month)
                    ->whereDay('hiring_date', $day);

                if ($includeFeb29) {
                    $q->orWhere(function ($sub) {
                        $sub->whereMonth('hiring_date', 2)
                            ->whereDay('hiring_date', 29);
                    });
                }
            })
            ->get();

        $count = 0;
        foreach ($users as $user) {
            $service->processAnniversary($user);
            $count++;
        }

        $this->info("Processed VL anniversary increments for {$count} employee(s).");
        Log::info('leave:process-anniversaries — completed', ['count' => $count, 'date' => $today->toDateString()]);
    }
}
