<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateHost
{
    public function handle(Request $request, Closure $next): Response
    {
        if (app()->environment('local', 'testing')) {
            return $next($request);
        }

        $host = $request->getHost();

        $configuredHosts = array_filter(array_map(
            'trim',
            explode(',', (string) env('ALLOWED_HOSTS', 'juki.eu.org'))
        ));

        $appHost = parse_url((string) config('app.url'), PHP_URL_HOST);
        $allowed = array_unique(array_filter([...$configuredHosts, $appHost]));

        foreach ($allowed as $domain) {
            $domain = ltrim($domain, '.');

            if ($host === $domain) {
                return $next($request);
            }

            if ($domain !== 'www.'.$domain && $host === 'www.'.$domain) {
                return $next($request);
            }
        }

        abort(403, 'Invalid Host header.');
    }
}
