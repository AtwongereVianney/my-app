<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserDataAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Ensure user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // For payment statistics routes, ensure user has access to their own data
        if ($request->routeIs('paymentStatistics.*')) {
            // The controller will handle the specific authorization
            // This middleware just ensures the user is authenticated
            return $next($request);
        }

        return $next($request);
    }
}
