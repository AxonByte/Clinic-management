<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuperAdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user is logged in and is super admin
        if (Auth::check() && Auth::user()->is_super_admin) {
            return $next($request);
        }

        // Otherwise redirect or abort
        abort(403, 'Unauthorized - You must be a super admin.');
    }
}
