<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');
        $middleware->append(\App\Http\Middleware\HideServerHeaders::class);
        $middleware->web(append: [
            \App\Http\Middleware\CheckInstalled::class,
            \App\Http\Middleware\ForceHttps::class,
            \App\Http\Middleware\TrailingSlashNormalizer::class,
            \App\Http\Middleware\CheckRedirects::class,
            \App\Http\Middleware\SetLocale::class,
            \App\Http\Middleware\Capture404::class,
            \App\Http\Middleware\CachePublicPages::class,
            \App\Http\Middleware\SetNoindexPaths::class,
        ]);

        $middleware->alias([
            'auth.admin' => \App\Http\Middleware\AdminMiddleware::class,
            'check.installed' => \App\Http\Middleware\CheckInstalled::class,
            'cache.public' => \App\Http\Middleware\CachePublicPages::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            'admin/midtrans/callback',
        ]);

        $middleware->redirectGuestsTo(function (Request $request) {
            if ($request->is('buyer') || $request->is('buyer/*')) {
                return route('buyer.login');
            }
            return route('login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson()
        );
    })->create();
