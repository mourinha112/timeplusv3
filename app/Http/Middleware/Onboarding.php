<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Route};
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class Onboarding
{
    public function handle(Request $request, Closure $next, string $guard): Response
    {
        $currentStep = Auth::guard($guard)->user()->onboarding_step;

        $currentRoute   = $request->route()->getName();
        $expectedRoute  = "$guard.onboarding.$currentStep";
        $dashboardRoute = $guard === 'user' ? "$guard.dashboard.show" : "$guard.appointment.index";

        if ($currentStep !== 'completed' && $currentRoute !== $expectedRoute) {
            abort_unless(Route::has($expectedRoute), 500, "Route $expectedRoute does not exist.");

            return redirect()->route($expectedRoute);
        }

        if ($currentStep === 'completed' && Str::contains($currentRoute, "$guard.onboarding.")) {
            return redirect()->route($dashboardRoute);
        }

        return $next($request);
    }
}
