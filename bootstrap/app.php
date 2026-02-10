<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use Sentry\Laravel\Integration;
use Throwable;

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
            'check.level' => \App\Http\Middleware\CheckLevel::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        Integration::handles($exceptions);

        // Redirect 419 (Token Mismatch) errors to login page
        $exceptions->render(function (Throwable $e, $request) {
            if ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpException && $e->getStatusCode() === 419) {
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Token expired. Please refresh and try again.'], 419);
                }

                // Redirect to appropriate login based on admin or web user
                if ($request->is('admin/*')) {
                    return redirect()->route('admin.login')->with('error', 'Your session has expired. Please login again.');
                } else {
                    return redirect()->route('login')->with('error', 'Your session has expired. Please login again.');
                }
            }
        });
    })
    ->withSchedule(function ($schedule) {
        // Refresh MetVBox code statuses every hour
        $schedule->command('metvbox:refresh-all-codes')
            ->hourly()
            ->withoutOverlapping()
            ->onFailure(function () {
                \Illuminate\Support\Facades\Log::error('MetVBox refresh command failed');
            })
            ->onSuccess(function () {
                \Illuminate\Support\Facades\Log::info('MetVBox refresh command completed successfully');
            });
    })
    ->create();
