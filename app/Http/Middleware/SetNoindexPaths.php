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

        // Default: area privat/transaksi tidak layak diindeks.
        // Dapat diubah/diperluas dari admin (SEO Settings → Indexation).
        $defaultNoindexPaths = "/login\n/register\n/payment/*\n/search";
        // Jika setting tersimpan tapi kosong → fallback ke default,
        // agar pengguna yang belum mengisi setting tetap terlindungi.
        $paths = SystemSetting::get('noindex_paths');
        if (empty(trim((string) $paths))) {
            $paths = $defaultNoindexPaths;
        }
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
