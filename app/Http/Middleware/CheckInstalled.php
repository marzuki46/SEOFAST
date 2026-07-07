<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckInstalled
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!file_exists(storage_path('installed'))) {
            if (!$request->is('install*')) {
                return redirect()->route('install.welcome');
            }
        } else {
            if ($request->is('install*') && $request->route()->getName() !== 'install.complete') {
                return redirect()->route('home');
            }
        }

        return $next($request);
    }
}
