<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdateUserTimezone
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()) {
            // Check for timezone in header or request data
            $timezone = $request->header('X-User-Timezone')
                ?? $request->input('timezone')
                ?? null;

            // Validate and update timezone if provided and different
            if ($timezone && $timezone !== $request->user()->timezone) {
                try {
                    // Validate timezone
                    new \DateTimeZone($timezone);

                    // Update user's timezone
                    $request->user()->update(['timezone' => $timezone]);
                } catch (\Exception $e) {
                    // Invalid timezone, ignore
                }
            }
        }

        return $next($request);
    }
}
