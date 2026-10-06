<?php

namespace App\Http\Middleware;

use App\Models\PageError;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
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
        if (
            ! in_array($request->getMethod(), ['GET', 'HEAD'], true)
            || str_starts_with($path, '/admin')
            || str_starts_with($path, '/buyer')
            || $request->expectsJson()
            || $this->isScannerProbe($request)
        ) {
            return;
        }

        // Do not let a bot flood the 404 tracker and turn it into a database DoS.
        $rateLimitKey = '404-tracker:'.$request->ip();
        if (RateLimiter::tooManyAttempts($rateLimitKey, 60)) {
            return;
        }
        RateLimiter::hit($rateLimitKey, 60);

        $rawUrl = $request->fullUrl();
        $url = Str::limit($rawUrl, 2048, '');
        $urlHash = md5($rawUrl);
        $referer = Str::limit((string) $request->header('referer'), 2048, '');

        $pageError = PageError::firstOrCreate(
            ['url_hash' => $urlHash],
            [
                'url' => $url,
                'referer' => $referer ?: null,
                'count' => 0,
                'first_seen' => now(),
                'last_seen' => now(),
            ]
        );

        $pageError->forceFill([
            'url' => $url,
            'referer' => $referer ?: null,
            'last_seen' => now(),
        ])->save();
        $pageError->increment('count');
    }

    private function isScannerProbe(Request $request): bool
    {
        $path = strtolower(rawurldecode($request->getPathInfo()));
        $query = strtolower(rawurldecode((string) $request->getQueryString()));

        if (preg_match('/(?:^|\/)(?:\.env|\.git|\.svn|\.hg|wp-admin|wp-includes|wp-login\.php|xmlrpc\.php|phpmyadmin|adminer|_profiler|cgi-bin|server-status)(?:\/|$)/', $path)) {
            return true;
        }

        if (preg_match('/\.(?:php|phar|sql|sqlite|bak|old|log|ini|conf|yml|yaml|pem|key|p12)(?:\/|$)/', $path)) {
            return true;
        }

        return $query !== '' && (bool) preg_match('/(?:\.env|\.git|union\s+select|information_schema|xp_cmdshell|base64_decode|\.\.\/)/', $query);
    }
}
