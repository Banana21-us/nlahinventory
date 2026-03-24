<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // $this->configureDefaults();
        if (config('app.env') !== 'local') {
        URL::forceScheme('https');
    }
        Gate::before(function (User $user, string $ability) {
        if ($user->role === 'HR') {
            return true;
        }
    });

    // Define the specific gates for other roles
    Gate::define('access-medical', fn(User $user) => $user->role === 'Staff');
    Gate::define('access-maintenance', fn(User $user) => $user->role === 'Maintenance');
    Gate::define('access-verify', fn(User $user) => $user->role === 'Inspector');
    Gate::define('access-hr-only', fn(User $user) => $user->role === 'HR');

        
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null
        );
    }
}
