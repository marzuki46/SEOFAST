<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (method_exists($response, 'header')) {
            $response->header('X-Content-Type-Options', 'nosniff');
            $response->header('X-Frame-Options', 'SAMEORIGIN');
            $response->header('Referrer-Policy', 'strict-origin-when-cross-origin');
            $response->header('Permissions-Policy', 'geolocation=(), microphone=(), camera=(), payment=()');
            $response->header('X-Permitted-Cross-Domain-Policies', 'none');
            $response->header('Cross-Origin-Opener-Policy', 'same-origin');
            $response->header('X-XSS-Protection', '0');
            $response->header('Content-Security-Policy', $this->csp());
        }

        return $response;
    }

    protected function csp(): string
    {
        return implode('; ', [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://www.googletagmanager.com https://www.google-analytics.com https://www.googleadservices.com https://cdn.jsdelivr.net https://cdn.tailwindcss.com https://connect.facebook.net https://www.google.com https://www.gstatic.com https://app.midtrans.com https://app.sandbox.midtrans.com",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net",
            "img-src 'self' data: blob: https:",
            "font-src 'self' https://fonts.gstatic.com data:",
            "connect-src 'self' https://www.googletagmanager.com https://www.google-analytics.com https://www.googleadservices.com https://app.midtrans.com https://app.sandbox.midtrans.com https://api.sandbox.midtrans.com",
            "frame-src 'self' https://app.midtrans.com https://app.sandbox.midtrans.com https://www.google.com https://www.youtube.com",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
        ]);
    }
}
