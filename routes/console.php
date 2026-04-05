<?php

use App\Services\MaintenanceRoundService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    app(MaintenanceRoundService::class)->releaseStaleLocks();
})->daily()->name('maintenance.release-stale-locks');
