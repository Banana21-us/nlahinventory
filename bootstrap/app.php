<?php

use App\Http\Middleware\CanAccessMaintenanceOrVerify;
use App\Http\Middleware\EnforceAccessKey;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // ADD THIS LINE BELOW
        $middleware->trustProxies(at: '*');

        $middleware->appendToGroup('web', EnforceAccessKey::class);

        $middleware->alias([
            'role' => RoleMiddleware::class,
            'can-maintenance-or-verify' => CanAccessMaintenanceOrVerify::class,
        ]);
    })
    ->withSchedule(function (Schedule $schedule): void {
        $schedule->command('leave:process-anniversaries')->daily();
        $schedule->command('leave:annual-reset')->yearlyOn(1, 1, '00:05');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
