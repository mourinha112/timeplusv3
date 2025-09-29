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
            match ($guard) {
                'user'    => $dashboardRoute = "$guard.dashboard.show",
                'specialist'   => $dashboardRoute = "$guard.appointment.index",
                'master'  => $dashboardRoute = "$guard.dashboard.show",
                'company' => $dashboardRoute = "$guard.dashboard.show",
                default   => throw new \InvalidArgumentException("Unknown guard: $guard"),
            };

            return redirect()->guest(route($dashboardRoute));
        }

        return $next($request);
    }
}
