<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: static function () {
            Route::middleware('web')
                ->prefix('user')
                ->name('user.')
                ->group(base_path('routes/web_user.php'));

            Route::middleware('web')
                ->prefix('admin')
                ->name('admin.')
                ->group(base_path('routes/web_admin.php'));

            Route::middleware('api')
                ->prefix('api')
                ->name('api.')
                ->group(base_path('routes/api.php'));
        },

    )
    ->withMiddleware(static function (Middleware $middleware) {
        //
    })
    ->withExceptions(static function (Exceptions $exceptions) {
        //
    })->create();
