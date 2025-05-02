<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use Illuminate\Session\Middleware\StartSession;
use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\CustomVerifyCsrfToken;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // middleware de sessão deve estar no grupo `web`
        $middleware->web(
            prepend: [
                StartSession::class,
                CustomVerifyCsrfToken::class,
            ],
            append: [
                EnsureFrontendRequestsAreStateful::class,
                HandleInertiaRequests::class,
            ]
        );

        $middleware->alias([
            'role' => \App\Http\Middleware\EnsureUserIsPartner::class,
            'redirect.role' => \App\Http\Middleware\RedirectIfAuthenticatedToRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
