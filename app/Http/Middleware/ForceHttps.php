<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceHttps
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->isSecure() && !app()->environment('local')) {
            return redirect()->secure($request->getRequestUri(), 301);
        }

        $response = $next($request);

        if (method_exists($response, 'header')) {
            $response->header('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }

        return $response;
    }
}
