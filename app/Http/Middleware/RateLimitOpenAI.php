<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class RateLimitOpenAI
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $key = 'openai-api:' . $user->id;

        // Limit to 20 requests per minute per user
        $executed = RateLimiter::attempt(
            $key,
            20,  // max attempts
            function() {
                // This runs if rate limit not exceeded
            },
            60   // decay seconds (1 minute)
        );

        if (!$executed) {
            return response()->json([
                'error' => 'Too many API requests. Please wait a moment before trying again.',
                'retry_after' => RateLimiter::availableIn($key)
            ], 429);
        }

        // Add rate limit headers to response
        $response = $next($request);

        $response->headers->set('X-RateLimit-Limit', '20');
        $response->headers->set('X-RateLimit-Remaining', RateLimiter::remaining($key, 20));

        return $response;
    }
}