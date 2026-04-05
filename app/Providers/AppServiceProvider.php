<?php

namespace App\Providers;

use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

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
        $this->configureDefaults();
        if (config('app.env') !== 'local') {
            URL::forceScheme('https');
        }
        Gate::before(function (User $user, string $ability): ?bool {
            if (! $user->is_active) {
                return false;
            }
            if ($user->employmentDetail?->position === 'HR Manager') {
                return true;
            }

            return null;
        });

        Gate::define('access-medical', fn (User $user): bool => $user->is_active && $user->employmentDetail?->position === 'Staff');
        Gate::define('access-maintenance', fn (User $user): bool => $user->is_active && $user->employmentDetail?->position === 'Housekeeping');
        Gate::define('access-verify', fn (User $user): bool => $user->is_active && $user->employmentDetail?->position === 'Maintenance_Head');
        Gate::define('access-hr-only', fn (User $user): bool => $user->is_active && $user->employmentDetail?->position === 'HR Manager');
        Gate::define('access-payroll', fn (User $user): bool => $user->is_active && $user->employmentDetail?->position === 'HR Manager');
        Gate::define('access-cashier-only', fn (User $user): bool => $user->is_active && $user->employmentDetail?->position === 'Cashier');

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
            ? Password::min(8)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null
        );
    }
}
