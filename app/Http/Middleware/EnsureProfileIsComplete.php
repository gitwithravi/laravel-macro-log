<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfileIsComplete
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Allow access to profile completion routes, logout, and dashboard (where modal shows)
        $allowedRoutes = [
            'profile.complete',
            'profile.update-completion',
            'logout',
            'dashboard',
        ];

        if ($user && !$user->hasCompletedProfile() && !in_array($request->route()?->getName(), $allowedRoutes)) {
            // Redirect to dashboard where the profile completion modal will be shown
            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}
