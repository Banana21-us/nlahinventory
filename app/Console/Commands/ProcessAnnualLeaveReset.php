<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\LeaveAccrualService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ProcessAnnualLeaveReset extends Command
{
    protected $signature = 'leave:annual-reset {--force : Run even if today is not January 1}';

    protected $description = 'January 1 leave processing: VL annual grant + SL/BL/SPL reset for all regularized active employees';

    public function handle(LeaveAccrualService $service): void
    {
        if (! Carbon::today()->isStartOfYear() && ! $this->option('force')) {
            $this->info('Annual leave reset skipped — today is not January 1. Use --force to override.');

            return;
        }

        $users = User::where('is_active', true)
            ->whereHas('employmentDetail', fn ($q) => $q->whereNotNull('regularization_date'))
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
