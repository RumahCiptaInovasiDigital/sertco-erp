<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'CheckRoleUser' => App\Http\Middleware\CheckRoleUser::class,
            'CheckMaintenance' => App\Http\Middleware\MaintenanceMode::class,
            'check.api.key' => App\Http\Middleware\CheckApiKey::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
    })->create();
