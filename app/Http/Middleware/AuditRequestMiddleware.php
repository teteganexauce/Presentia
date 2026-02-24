<?php

namespace App\Http\Middleware;

use App\Services\AuditService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuditRequestMiddleware
{
    // Routes à surveiller avec leur action correspondante
    private array $watchedRoutes = [
        'login' => 'login',
        'logout' => 'logout',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Vérifier si c'est une route surveillée
        $routeName = $request->route()?->getName();

        if ($routeName && isset($this->watchedRoutes[$routeName])) {
            // Logguer seulement si la réponse est un succès
            if ($response->isSuccessful() || $response->isRedirection()) {
                AuditService::log($this->watchedRoutes[$routeName]);
            }
        }

        return $response;
    }
}
