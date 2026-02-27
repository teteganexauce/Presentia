<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForcePasswordChange
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->status === 'PENDING') {

            $excludedRoutes = [
                'password.change',
                'password.change.update',
                'logout',
            ];

            $routeName = $request->route()?->getName();

            if (! in_array($routeName, $excludedRoutes)) {
                return redirect()->route('password.change');
            }
        }

        return $next($request);
    }
}
