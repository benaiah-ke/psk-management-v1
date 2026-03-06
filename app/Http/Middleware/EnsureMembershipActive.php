<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureMembershipActive
{
    public function handle(Request $request, Closure $next)
    {
        // Skip check for admin users
        if ($request->user() && $request->user()->hasAnyRole(['Super Admin', 'Admin', 'Finance', 'Branch Admin', 'Committee Admin'])) {
            return $next($request);
        }

        return $next($request);
    }
}
