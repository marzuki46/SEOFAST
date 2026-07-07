<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CachePublicPages
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (
            method_exists($response, 'header')
            && $response->isSuccessful()
            && $request->isMethod('GET')
            && !$request->is('admin*')
            && !$request->is('install*')
            && !$request->is('master/*')
            && !$request->is('login*')
            && !$request->is('register*')
            && !$request->is('logout*')
            && !$request->is('buyer*')
            && !$request->is('force-migrate*')
            && !$request->is('auth/*')
        ) {
            $response->header('Cache-Control', 'public, max-age=3600, s-maxage=86400');
            $response->header('CDN-Cache-Control', 'public, s-maxage=86400');
            $response->header('Cloudflare-CDN-Cache-Control', 'public, s-maxage=86400');
        }

        return $response;
    }
}
