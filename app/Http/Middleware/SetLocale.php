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
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->segment(1);

        if ($locale === 'en') {
            app()->setLocale('en');
        } else {
            app()->setLocale(config('app.locale', 'id'));
        }

        return $next($request);
    }
}
