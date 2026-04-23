<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class CanAccessMaintenanceOrVerify
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Gate::check('access-verify') || Gate::check('access-hr-only')) {
            return $next($request);
        }

        abort(403);
    }
}
