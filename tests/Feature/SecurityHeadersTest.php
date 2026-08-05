<?php

namespace Tests\Feature;

use App\Http\Middleware\SecurityHeaders;
use App\Http\Middleware\ValidateHost;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class SecurityHeadersTest extends TestCase
{
    use RefreshDatabase;

    public function test_security_headers_are_present(): void
    {
        $response = $this->withoutMiddleware(\App\Http\Middleware\ForceHttps::class)
            ->withServerVariables(['HTTPS' => 'on'])
            ->get('/sitemap.xml');

        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->assertHeader('Cross-Origin-Opener-Policy', 'same-origin');
        $response->assertHeader('Content-Security-Policy');
    }

    public function test_validate_host_rejects_unknown_host(): void
    {
        $previous = app()->environment();
        app()->detectEnvironment(fn () => 'production');

        try {
            $request = Request::create('https://juki.eu.org.renovasirumah.my.id/', 'GET');
            $middleware = new ValidateHost();

            $this->expectException(HttpException::class);
            $middleware->handle($request, fn ($req) => response('ok'));
        } finally {
            app()->detectEnvironment(fn () => $previous);
        }
    }

    public function test_validate_host_accepts_known_host(): void
    {
        $previous = app()->environment();
        app()->detectEnvironment(fn () => 'production');

        try {
            $request = Request::create('https://juki.eu.org/', 'GET');
            $middleware = new ValidateHost();

            $response = $middleware->handle($request, fn ($req) => response('ok'));
            $this->assertEquals('ok', $response->getContent());
        } finally {
            app()->detectEnvironment(fn () => $previous);
        }
    }

    public function test_validate_host_accepts_www_variant(): void
    {
        $previous = app()->environment();
        app()->detectEnvironment(fn () => 'production');

        try {
            $request = Request::create('https://www.juki.eu.org/', 'GET');
            $middleware = new ValidateHost();

            $response = $middleware->handle($request, fn ($req) => response('ok'));
            $this->assertEquals('ok', $response->getContent());
        } finally {
            app()->detectEnvironment(fn () => $previous);
        }
    }
}
