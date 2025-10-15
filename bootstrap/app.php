<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\{Exceptions, Middleware};

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'auth'       => \App\Http\Middleware\Authenticate::class,
            'guest'      => \App\Http\Middleware\Guest::class,
            'onboarding' => \App\Http\Middleware\Onboarding::class,
        ]);

        // Excluir rotas de webhook da proteção CSRF
        $middleware->validateCsrfTokens(except: [
            'asaas/webhook',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
