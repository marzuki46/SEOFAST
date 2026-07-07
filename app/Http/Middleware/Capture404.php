<?php

namespace App\Http\Middleware;

use App\Models\PageError;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Capture404
{
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }

    public function terminate(Request $request, Response $response): void
    {
        if ($response->getStatusCode() !== 404) {
            return;
        }

        // Skip common non-page requests
        $path = $request->getPathInfo();
        if (str_starts_with($path, '/admin') || str_starts_with($path, '/buyer') || $request->expectsJson()) {
            return;
        }

        $url = $request->fullUrl();
        $urlHash = md5($url);

        PageError::updateOrCreate(
            ['url_hash' => $urlHash],
            [
                'url' => $url,
                'referer' => $request->header('referer'),
                'count' => \DB::raw('count + 1'),
                'last_seen' => now(),
            ]
        );
    }
}
