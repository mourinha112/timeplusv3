<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthorizeTelescope
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('master')->check() || config('app.env') === 'local') {
            return $next($request);
        }

        return abort(403);
    }
}
