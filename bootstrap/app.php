<?php

use App\Http\Middleware\AuditRequestMiddleware;
use App\Http\Middleware\ForcePasswordChange;
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
        // Audit sur toutes les routes web
        $middleware->appendToGroup('web', AuditRequestMiddleware::class);

        // Forcer le changement de mot de passe si statut PENDING
        $middleware->appendToGroup('web', ForcePasswordChange::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();