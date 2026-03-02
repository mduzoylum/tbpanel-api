<?php

use App\Http\Middleware\CorsMiddleware;
use App\Http\Middleware\EnsureAuthenticatedUserType;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\CheckPermission;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        apiPrefix: '',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->use([
            'cors' => CorsMiddleware::class,
        ]);

        $middleware->alias([
            'check.permission' => CheckPermission::class,
            'auth.user.type' => EnsureAuthenticatedUserType::class,
        ]);


    })->withSingletons([
        \Illuminate\Contracts\Debug\ExceptionHandler::class => \App\Exceptions\Handler::class,
    ])->withExceptions(function (Exceptions $exceptions) {


    })->create();
