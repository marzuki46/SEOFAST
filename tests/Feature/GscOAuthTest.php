<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\User;
use App\Models\TenantApiCredential;
use Google\Client as GoogleClient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Mockery;
use Tests\TestCase;

class GscOAuthTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Tenant $tenant;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant = Tenant::create([
            'name' => 'Test Tenant',
            'slug' => 'test-tenant',
            'domain' => 'test-tenant.test',
            'subscription_plan' => 'pro',
            'is_active' => true,
        ]);

        $this->user = User::create([
            'name' => 'Tenant User',
            'email' => 'tenant@user.test',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'tenant_id' => $this->tenant->id,
        ]);

        config([
            'services.google.client_id' => 'test-client-id',
            'services.google.client_secret' => 'test-client-secret',
            'services.google.redirect_uri' => 'http://seofast.test/admin/gsc/callback',
        ]);
    }

    public function test_auth_route_requires_login(): void
    {
        $response = $this->get(route('admin.gsc.auth'));

        $response->assertRedirect('/login');
    }

    public function test_redirects_to_google_oauth_when_authenticated_with_tenant(): void
    {
        $mockClient = Mockery::mock(GoogleClient::class);
        $mockClient->shouldReceive('setClientId')->once()->with('test-client-id');
        $mockClient->shouldReceive('setClientSecret')->once()->with('test-client-secret');
        $mockClient->shouldReceive('setRedirectUri')->once()->with('http://seofast.test/admin/gsc/callback');
        $mockClient->shouldReceive('setAccessType')->once()->with('offline');
        $mockClient->shouldReceive('setPrompt')->once()->with('consent');
        $mockClient->shouldReceive('addScope')->twice();
        
        $mockClient->shouldReceive('setState')->once()->with(Mockery::on(function ($state) {
            return is_string($state) && strlen($state) === 40;
        }));
        
        $mockClient->shouldReceive('createAuthUrl')
            ->once()
            ->andReturn('https://accounts.google.com/o/oauth2/auth?client_id=test-client-id');

        $this->app->instance(GoogleClient::class, $mockClient);

        $response = $this->actingAs($this->user)->get(route('admin.gsc.auth'));

        $response->assertRedirect('https://accounts.google.com/o/oauth2/auth?client_id=test-client-id');
        $this->assertTrue(session()->has('gsc_oauth_state'));
    }

    public function test_handles_callback_successfully(): void
    {
        $state = 'random-oauth-state-string-here';
        session(['gsc_oauth_state' => $state]);

        $mockClient = Mockery::mock(GoogleClient::class);
        $mockClient->shouldReceive('setClientId')->once()->with('test-client-id');
        $mockClient->shouldReceive('setClientSecret')->once()->with('test-client-secret');
        $mockClient->shouldReceive('setRedirectUri')->once()->with('http://seofast.test/admin/gsc/callback');

        $mockClient->shouldReceive('fetchAccessTokenWithAuthCode')
            ->once()
            ->with('auth-code-123')
            ->andReturn([
                'access_token' => 'mock-access-token',
                'refresh_token' => 'mock-refresh-token',
                'expires_in' => 3600,
            ]);

        $this->app->instance(GoogleClient::class, $mockClient);

        $response = $this->actingAs($this->user)
            ->get(route('admin.gsc.callback', [
                'code' => 'auth-code-123',
                'state' => $state,
            ]));

        $response->assertRedirect(route('admin.gsc.index'));
        $response->assertSessionHas('success', 'Google Search Console connected successfully!');

        $this->assertDatabaseHas('tenant_api_credentials', [
            'tenant_id' => $this->tenant->id,
            'service' => 'google_search_console',
            'is_active' => true,
        ]);

        $credential = TenantApiCredential::where('tenant_id', $this->tenant->id)
            ->where('service', 'google_search_console')
            ->first();

        $this->assertEquals('mock-access-token', Crypt::decryptString($credential->access_token));
        $this->assertEquals('mock-refresh-token', Crypt::decryptString($credential->refresh_token));
        $this->assertNotNull($credential->token_expires_at);
    }

    public function test_handles_callback_invalid_state(): void
    {
        session(['gsc_oauth_state' => 'expected-state']);

        $response = $this->actingAs($this->user)
            ->get(route('admin.gsc.callback', [
                'code' => 'auth-code-123',
                'state' => 'invalid-state',
            ]));

        $response->assertRedirect(route('admin.gsc.index'));
        $response->assertSessionHas('error', 'Invalid OAuth state token. Possible CSRF attempt.');
    }
}
