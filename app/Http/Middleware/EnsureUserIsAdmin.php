<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user() || !$request->user()->hasAnyRole(['Super Admin', 'Admin', 'Finance', 'Branch Admin', 'Committee Admin'])) {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}
