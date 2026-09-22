<?php

require_once __DIR__.'/../app/helpers.php';

use App\Http\Middleware\DemoAuth;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'demo.auth' => DemoAuth::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Keep Laravel's default exception handling.
    })->create();
