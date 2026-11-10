<?php

namespace App\Http\Middleware;

use App\Models\PageError;
use App\Models\PageErrorVisit;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class Capture404
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($this->isTrackableRequest($request)) {
            $key = '4xx-throttle:'.($request->ip() ?: 'unknown');
            $limit = max(1, (int) config('security.four_xx_per_minute', 30));
            if (RateLimiter::tooManyAttempts($key, $limit)) {
                return redirect()->route('home');
            }
        }

        $response = $next($request);
        if ($this->isTrackableRequest($request) && $response->getStatusCode() >= 400 && $response->getStatusCode() < 500) {
            RateLimiter::hit('4xx-throttle:'.($request->ip() ?: 'unknown'), 60);
        }

        return $response;
    }

    public function terminate(Request $request, Response $response): void
    {
        if ($response->getStatusCode() < 400 || $response->getStatusCode() >= 500 || ! $this->isTrackableRequest($request)) {
            return;
        }

        $path = $request->getPathInfo();

        // Do not let a bot flood the 404 tracker and turn it into a database DoS.
        $rateLimitKey = '404-tracker:'.$request->ip();
        if (RateLimiter::tooManyAttempts($rateLimitKey, 60)) {
            return;
        }
        RateLimiter::hit($rateLimitKey, 60);

        $statusCode = $response->getStatusCode();
        $isProbe = $this->isScannerProbe($request);
        $rawUrl = $isProbe ? url($path) : $request->fullUrl();
        $url = Str::limit($rawUrl, 2048, '');
        $urlHash = md5($rawUrl.'|'.$statusCode);
        $referer = Str::limit((string) $request->header('referer'), 2048, '');
        $ip = Str::limit((string) ($request->ip() ?: 'unknown'), 45, '');
        $category = $this->classify($request, $isProbe);
        $userAgent = Str::limit((string) $request->userAgent(), 512, '');

        $pageError = PageError::firstOrCreate(
            ['url_hash' => $urlHash],
            [
                'url' => $url,
                'status_code' => $statusCode,
                'referer' => $referer ?: null,
                'last_ip_address' => $ip,
                'category' => $category,
                'last_user_agent' => $userAgent ?: null,
                'count' => 0,
                'first_seen' => now(),
                'last_seen' => now(),
            ]
        );

        $pageError->forceFill([
            'url' => $url,
            'status_code' => $statusCode,
            'referer' => $referer ?: null,
            'last_ip_address' => $ip,
            'category' => $category,
            'last_user_agent' => $userAgent ?: null,
            'last_seen' => now(),
        ])->save();
        $pageError->increment('count');

        PageErrorVisit::create([
            'page_error_id' => $pageError->id,
            'ip_address' => $ip,
            'path' => Str::limit($path, 2048, ''),
            'status_code' => $statusCode,
            'category' => $category,
            'user_agent' => $userAgent ?: null,
            'referer' => $referer ?: null,
            'created_at' => now(),
        ]);
    }

    private function isTrackableRequest(Request $request): bool
    {
        return in_array($request->getMethod(), ['GET', 'HEAD'], true)
            && ! str_starts_with($request->getPathInfo(), '/admin')
            && ! str_starts_with($request->getPathInfo(), '/buyer')
            && ! $request->expectsJson();
    }

    private function classify(Request $request, bool $isProbe): string
    {
        if ($isProbe) {
            return 'hacker';
        }

        $agent = strtolower((string) $request->userAgent());
        if (preg_match('/googlebot|bingbot|yandexbot|baiduspider|duckduckbot|slurp/', $agent)) {
            return 'search_engine_crawler';
        }
        if (preg_match('/bot|crawler|spider|headless|scrapy|curl|wget|python-requests/', $agent)) {
            return 'bot';
        }
        if (str_contains($agent, 'mozilla')) {
            return 'person';
        }

        return 'other';
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
