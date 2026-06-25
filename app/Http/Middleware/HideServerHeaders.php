<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HideServerHeaders
{
    /**
     * Handle an incoming request.
     * Adds security headers and masks server identity.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (!method_exists($response, 'headers')) {
            return $response;
        }

        // --- Mask Server Identity ---
        $response->headers->remove('X-Powered-By');
        $response->headers->remove('Server');
        $response->headers->set('X-Powered-By', 'SEOFAST Engine');
        $response->headers->set('Server', 'SEOFAST');

        // --- Clickjacking Protection ---
        // Prevents the site from being embedded in an <iframe> on other domains
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // --- MIME Sniffing Protection ---
        // Forces browser to respect declared Content-Type (prevents file-type confusion attacks)
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // --- Referrer Policy ---
        // Sends full URL only for same-origin, only origin for cross-origin (good for analytics)
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // --- Permissions Policy ---
        // Disables dangerous browser features that aren't used by this site
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), payment=(self)');

        // --- Content Security Policy (CSP) ---
        // Controls which external resources the browser is allowed to load.
        // This is the most powerful XSS protection header available.
        $csp = implode('; ', [
            "default-src 'self'",
            // Scripts: self, Google Analytics, GTM, Alpine.js CDN, Midtrans
            "script-src 'self' 'unsafe-inline' https://www.googletagmanager.com https://www.google-analytics.com https://cdn.jsdelivr.net https://app.midtrans.com https://api.midtrans.com",
            // Styles: self, Google Fonts
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com",
            // Fonts: self, Google Fonts CDN
            "font-src 'self' https://fonts.gstatic.com",
            // Images: self, data URIs (for inline images), HTTPS anywhere (for CDN images)
            "img-src 'self' data: https:",
            // Connections: self, Google Analytics, GSC API
            "connect-src 'self' https://www.google-analytics.com https://analytics.google.com https://api.midtrans.com",
            // Frames: Midtrans uses iframes for payment
            "frame-src 'self' https://app.midtrans.com",
            // Block all plugins (Flash, etc.)
            "object-src 'none'",
            // Base URI restriction
            "base-uri 'self'",
            // Form submissions only to self
            "form-action 'self'",
        ]);
        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}
