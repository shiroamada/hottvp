<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (session()->has('locale')) {
            $locale = session()->get('locale');
            // \Log::info('Attempting to set locale to: ' . $locale); // Add this line
            if (in_array($locale, config('app.supported_locales', []))) {
                app()->setLocale($locale);
                // \Log::info('Locale successfully set to: ' . $locale); // Add this line
            } else {
                // \Log::warning('Unsupported locale attempted: ' . $locale); // Add this line
            }
        } else {
            // \Log::info('No locale found in session. Using default: ' . app()->getLocale()); // Add this line
        }

        return $next($request);
    }
}
