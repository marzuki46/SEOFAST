<?php

namespace App\Http\Middleware;

use App\Models\Redirect;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRedirects
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->isMethod('GET') && ! $request->isMethod('HEAD')) {
            return $next($request);
        }

        $path = $request->getPathInfo();

        // Don't redirect admin routes
        if (str_starts_with($path, '/admin')) {
            return $next($request);
        }

        $redirect = Redirect::active()->matchUrl($path)->first();

        if ($redirect) {
            $redirect->increment('hits');

            return redirect($redirect->new_url, $redirect->status_code);
        }

        return $next($request);
    }
}
