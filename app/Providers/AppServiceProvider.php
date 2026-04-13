<?php

namespace App\Providers;

use App\Models\AccessKey;
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
        // $this->configureDefaults();
        if (config('app.env') !== 'local') {
            URL::forceScheme('https');
        }
        // Super-access: access keys marked is_super bypass all gate checks
        Gate::before(function (User $user, string $ability) {
            return $user->accessKey?->is_super ? true : null;
        });

        // Each gate checks if the user's access key grants the matching permission slug.
        // Falls back to false (users.waiting) if no access key is assigned.
        $permissionGate = function (string $slug) {
            return fn (User $user) => $user->accessKey?->hasPermission($slug) ?? false;
        };

        Gate::define('access-medical',       $permissionGate('access-medical'));
        Gate::define('access-maintenance',   $permissionGate('access-maintenance'));
        Gate::define('access-verify',        $permissionGate('access-verify'));
        Gate::define('access-hr-only',       $permissionGate('access-hr-only'));
        Gate::define('access-payroll',       $permissionGate('access-payroll'));
        Gate::define('access-cashier-only',  $permissionGate('access-cashier-only'));

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
