<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Guest
{
    public function handle(Request $request, Closure $next, string $guard = 'user'): Response
    {
        if (Auth::guard($guard)->check()) {
            $dashboardRoute = $guard === 'user' ? "$guard.dashboard.show" : "$guard.appointment.index";

            return redirect()->guest(route($dashboardRoute));
        }

        return $next($request);
    }
}
