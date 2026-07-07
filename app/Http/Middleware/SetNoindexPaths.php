<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\SystemSetting;

class SetNoindexPaths
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (!method_exists($response, 'header')) {
            return $response;
        }

        $paths = SystemSetting::get('noindex_paths', '');
        $paths = preg_split('/\r\n|\r|\n/', trim($paths));

        foreach ($paths as $pattern) {
            $pattern = trim($pattern);
            if ($pattern === '') continue;

            $regex = '/^' . str_replace('\*', '.*', preg_quote($pattern, '/')) . '$/';
            if (preg_match($regex, $request->path())) {
                $response->header('X-Robots-Tag', 'noindex');
                break;
            }
        }

        return $response;
    }
}
