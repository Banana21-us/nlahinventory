<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnforceAccessKey
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && ! Auth::user()->accessKey) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->withErrors(['username' => 'Your account access has been removed. Please contact HR.']);
        }

        return $next($request);
    }
}
