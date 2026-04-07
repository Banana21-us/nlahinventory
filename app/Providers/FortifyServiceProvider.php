<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
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
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        // Block disabled users at login
        Fortify::authenticateUsing(function (Request $request) {
            $user = User::where('username', $request->username)->first();

            if ($user && Hash::check($request->password, $user->password)) {
                if (! $user->is_active) {
                    return null; // Reject login silently, shows auth.failed message
                }

                return $user;
            }

            return null;
        });

        $this->configureActions();
        $this->configureViews();
        $this->configureRateLimiting();

        $this->app->singleton(RegisterResponseContract::class, function () {
            return new class implements RegisterResponseContract
            {
                public function toResponse($request)
                {
                    $request->user()->sendEmailVerificationNotification();
                    // Force logout any user the Fortify Controller just logged in
                    Auth::guard('web')->logout();

                    // Clear the session to be safe
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    return redirect()->route('login')
                        ->with('status', 'Registration successful! Please check your email to verify your account before logging in.');
                }
            };
        });
        $this->app->singleton(LoginResponseContract::class, function () {
            return new class implements LoginResponseContract
            {
                public function toResponse($request)
                {
                    $user = Auth::user();

                    if (! $user->hasVerifiedEmail()) {
                        return redirect()->route('verification.notice');
                    }

                    // Access the position column via the relationship
                    $position = $user->employmentDetail?->position;

                    return match ($position) {
                        'HR Manager' => redirect()->route('HR.hrdashboard'),
                        'Housekeeping' => redirect()->route('Maintenance.dashboard'),
                        'Maintenance_Head' => redirect()->route('Maintenance.checklist.verify'),
                        'Cashier' => redirect()->route('pos.dashboard'),
                        'Staff' => redirect()->route('users.leaveform'),
                        default        => redirect()->route('users.waiting')
                            ->withErrors(['email' => 'Your position is not assigned to a dashboard.']),
                    };
                }
            };
        });
    }

    /**
     * Configure Fortify actions.
     */
    private function configureActions(): void
    {
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::createUsersUsing(CreateNewUser::class);
    }

    /**
     * Configure Fortify views.
     */
    private function configureViews(): void
    {
        Fortify::loginView(fn () => view('pages::auth.login'));
        Fortify::verifyEmailView(fn () => view('pages::auth.verify-email'));
        Fortify::twoFactorChallengeView(fn () => view('pages::auth.two-factor-challenge'));
        Fortify::confirmPasswordView(fn () => view('pages::auth.confirm-password'));
        Fortify::registerView(fn () => view('pages::auth.register'));
        Fortify::resetPasswordView(fn () => view('pages::auth.reset-password'));
        Fortify::requestPasswordResetLinkView(fn () => view('pages::auth.forgot-password'));
    }

    /**
     * Configure rate limiting.
     */
    private function configureRateLimiting(): void
    {
        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });
    }
}
