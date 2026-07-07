<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrailingSlashNormalizer
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->isMethod('GET')) {
            return $next($request);
        }

        $path = $request->getPathInfo();

        // Skip admin routes and root path
        if (str_starts_with($path, '/admin') || $path === '/') {
            return $next($request);
        }

        // Remove trailing slash (Laravel convention: no trailing slash on non-root)
        if (str_ends_with($path, '/')) {
            $normalized = rtrim($path, '/');

            if ($request->getQueryString()) {
                $normalized .= '?' . $request->getQueryString();
            }

            return redirect($normalized, 301);
        }

        return $next($request);
    }
}
