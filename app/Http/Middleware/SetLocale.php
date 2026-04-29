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
        // Priority 1: Query parameter ?lang=ar (temporary override)
        if ($request->has('lang') && in_array($request->get('lang'), ['en', 'ar'])) {
            $locale = $request->get('lang');
            app()->setLocale($locale);
            session(['locale' => $locale]);
        }
        // Priority 2: User preference (for authenticated users)
        elseif ($request->user() && isset($request->user()->preferred_locale)) {
            app()->setLocale($request->user()->preferred_locale);
        }
        // Priority 3: Accept-Language header (for API requests)
        elseif ($request->hasHeader('Accept-Language')) {
            $headerLocale = substr($request->header('Accept-Language'), 0, 2);
            if (in_array($headerLocale, ['en', 'ar'])) {
                app()->setLocale($headerLocale);
            }
        }
        // Priority 4: Session (for web requests)
        elseif (session()->has('locale')) {
            app()->setLocale(session('locale'));
        }

        return $next($request);
    }
}
