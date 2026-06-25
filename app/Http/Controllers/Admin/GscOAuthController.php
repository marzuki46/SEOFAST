<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TenantApiCredential;
use Google\Client as GoogleClient;
use Google\Service\SearchConsole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Http\RedirectResponse;

class GscOAuthController extends Controller
{
    /**
     * Redirect to Google OAuth consent screen.
     */
    public function redirectToGoogle(Request $request): RedirectResponse
    {

        $clientId = config('services.google.client_id');
        $clientSecret = config('services.google.client_secret');
        $redirectUri = config('services.google.redirect');

        if (empty($clientId) || empty($clientSecret) || empty($redirectUri)) {
            return redirect()->route('admin.gsc.index')
                ->with('error', 'Google API Client is not configured. Please check your credentials config.');
        }

        $client = app(GoogleClient::class);
        $client->setClientId($clientId);
        $client->setClientSecret($clientSecret);
        $client->setRedirectUri($redirectUri);
        
        // Request offline access so we receive a refresh token
        $client->setAccessType('offline');
        $client->setPrompt('consent');
        
        // Add scopes: webmasters (read only or full depending on need) and indexing
        $client->addScope('https://www.googleapis.com/auth/webmasters.readonly');
        $client->addScope('https://www.googleapis.com/auth/indexing');

        // Create state parameter for CSRF protection
        $state = Str::random(40);
        $request->session()->put('gsc_oauth_state', $state);
        $client->setState($state);

        $authUrl = $client->createAuthUrl();

        return redirect()->away($authUrl);
    }

    /**
     * Handle the Google OAuth callback.
     */
    public function handleCallback(Request $request): RedirectResponse
    {

        // Verify CSRF state token
        $storedState = $request->session()->pull('gsc_oauth_state');
        if (empty($storedState) || $storedState !== $request->state) {
            return redirect()->route('admin.gsc.index')
                ->with('error', 'Invalid OAuth state token. Possible CSRF attempt.');
        }

        if ($request->has('error')) {
            return redirect()->route('admin.gsc.index')
                ->with('error', 'Google authentication failed: ' . $request->error);
        }

        if (!$request->has('code')) {
            return redirect()->route('admin.gsc.index')
                ->with('error', 'OAuth authorization code not found.');
        }

        try {
            $client = app(GoogleClient::class);
            $client->setClientId(config('services.google.client_id'));
            $client->setClientSecret(config('services.google.client_secret'));
            $client->setRedirectUri(config('services.google.redirect'));

            // Exchange authorization code for token
            $token = $client->fetchAccessTokenWithAuthCode($request->code);

            if (isset($token['error'])) {
                throw new \RuntimeException('Failed to exchange code: ' . ($token['error_description'] ?? $token['error']));
            }

            // Save credentials
            $accessToken = $token['access_token'];
            $refreshToken = $token['refresh_token'] ?? null;
            $expiresIn = $token['expires_in'] ?? 3600;

            // Prepare update array
            $updateData = [
                'access_token' => Crypt::encryptString($accessToken),
                'token_expires_at' => now()->addSeconds($expiresIn),
                'is_active' => true,
            ];

            // Only update refresh token if it was returned by Google
            if ($refreshToken) {
                $updateData['refresh_token'] = Crypt::encryptString($refreshToken);
            }

            TenantApiCredential::updateOrCreate(
                [
                    'tenant_id' => 0,
                    'service' => 'google_search_console',
                ],
                $updateData
            );

            return redirect()->route('admin.gsc.index')
                ->with('success', 'Google Search Console connected successfully!');

        } catch (\Exception $e) {
            Log::error('Google Search Console callback error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('admin.gsc.index')
                ->with('error', 'Authentication failed: ' . $e->getMessage());
        }
    }
}
