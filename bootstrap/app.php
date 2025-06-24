<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use Sentry\Laravel\Integration;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        // channels: __DIR__.'/../routes/channels.php',
        // api: __DIR__.'/../routes/api.php',
        // using: function () {
        //     Route::middleware('web')
        //         ->prefix('admin')
        //         ->namespace('App\Http\Controllers\Admin')
        //         ->group(base_path('routes/admin.php'));
        // }
        then: function () {
            Route::middleware('web')
                ->prefix('admin')
                ->name('admin.')
                ->namespace('App\Http\Controllers\Admin')
                ->group(base_path('routes/admin.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->appendToGroup('web', [
            \App\Http\Middleware\SetLocale::class,
            \App\Http\Middleware\AdminControllerMiddleware::class,
            \App\Http\Middleware\AdminUtilityMiddleware::class,
        ]);
        $middleware->alias([
            'guest.admin' => \App\Http\Middleware\AdminRedirectIfAdminAuthenticated::class,
            'auth.admin' => \App\Http\Middleware\AdminAuthenticate::class,
            'log.admin' => \App\Http\Middleware\LogAdminMiddleware::class,
            'authorization.admin' => \App\Http\Middleware\AdminAuthorization::class,
            'admin.controller' => \App\Http\Middleware\AdminControllerMiddleware::class,
            'admin.utility' => \App\Http\Middleware\AdminUtilityMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        Integration::handles($exceptions);
    })->create();
