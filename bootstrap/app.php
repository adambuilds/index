<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Providers\AppServiceProvider;
use App\Providers\Auth0ServiceProvider;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withProviders([
        AppServiceProvider::class,
        Auth0ServiceProvider::class,
    ])
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Ensure all exceptions get logged to storage/logs/laravel.log for easier debugging
        $exceptions->report(function (Throwable $e) {
            try {
                logger()->error('Unhandled exception', [
                    'type' => get_class($e),
                    'message' => $e->getMessage(),
                ]);
            } catch (Throwable) {
                // noop
            }
        });
    })->create();
