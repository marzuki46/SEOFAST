<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TenantApiCredential;
use App\Models\GscSyncLog;
use App\Models\GscUrlInspection;
use App\Models\GscSearchAnalytics;
use App\Models\Content;
use App\Jobs\Gsc\SyncUrlInspectionJob;
use App\Jobs\Gsc\SyncSearchAnalyticsJob;
use App\Jobs\Gsc\SubmitToIndexingApiJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class GscAdminController extends Controller
{
    /**
     * Show the GSC Management page.
     */
    public function index(Request $request): View
    {
        $tenant = null;

        // Fetch credentials
        $gscCred = TenantApiCredential::where('tenant_id', 0)
            ->where('service', 'google_search_console')
            ->first();

        $indexingCred = TenantApiCredential::where('tenant_id', 0)
            ->where('service', 'google_indexing_api')
            ->first();

        // Decrypt values safely for form display
        $gscAccessToken = '';
        $gscRefreshToken = '';
        $serviceAccountJson = '';

        if ($gscCred) {
            try {
                $gscAccessToken = Crypt::decryptString($gscCred->access_token);
            } catch (\Exception $e) {
                $gscAccessToken = $gscCred->access_token;
            }

            try {
                $gscRefreshToken = Crypt::decryptString($gscCred->refresh_token);
            } catch (\Exception $e) {
                $gscRefreshToken = $gscCred->refresh_token;
            }
        }

        if ($indexingCred && $indexingCred->service_account_json) {
            // Note: service_account_json is already cast to 'encrypted' in the model
            $serviceAccountJson = $indexingCred->service_account_json;
        }

        // Fetch sync logs
        $syncLogs = GscSyncLog::latest()
            ->take(10)
            ->get();

        // Fetch published content indexation states
        $contents = Content::withoutGlobalScopes()
            ->with(['latestUrlInspection', 'feedback'])
            ->orderBy('slug')
            ->get();

        return view('admin.gsc.index', compact(
            'tenant',
            'gscCred',
            'indexingCred',
            'gscAccessToken',
            'gscRefreshToken',
            'serviceAccountJson',
            'syncLogs',
            'contents'
        ));
    }

    /**
     * Save GSC and Indexing API credentials.
     */
    public function saveCredentials(Request $request): RedirectResponse
    {

        $request->validate([
            'property_url' => 'required|string|url',
            'access_token' => 'nullable|string',
            'refresh_token' => 'nullable|string',
            'service_account_json' => 'nullable|string',
        ]);

        // Save GSC Creds
        if ($request->filled('access_token')) {
            TenantApiCredential::updateOrCreate(
                [
                    'tenant_id' => 0,
                    'service' => 'google_search_console',
                ],
                [
                    'property_url' => $request->property_url,
                    'access_token' => Crypt::encryptString($request->access_token),
                    'refresh_token' => $request->filled('refresh_token') 
                        ? Crypt::encryptString($request->refresh_token) 
                        : null,
                    'token_expires_at' => now()->addHour(),
                    'is_active' => true,
                ]
            );
        }

        // Save Indexing Creds
        if ($request->filled('service_account_json')) {
            TenantApiCredential::updateOrCreate(
                [
                    'tenant_id' => 0,
                    'service' => 'google_indexing_api',
                ],
                [
                    'property_url' => $request->property_url,
                    'service_account_json' => $request->service_account_json,
                    'is_active' => true,
                ]
            );
        }

        return redirect()->route('admin.gsc.index')
            ->with('success', 'Google credentials saved successfully.');
    }

    /**
     * Manually trigger URL inspection sync.
     */
    public function syncInspections(Request $request): RedirectResponse
    {
        try {
            // Check credentials exist
            TenantApiCredential::where('tenant_id', 0)
                ->where('service', 'google_search_console')
                ->where('is_active', true)
                ->firstOrFail();

            SyncUrlInspectionJob::dispatch(0);

            return redirect()->route('admin.gsc.index')
                ->with('success', 'URL inspection sync job dispatched to background.');
        } catch (\Exception $e) {
            return redirect()->route('admin.gsc.index')
                ->with('error', 'Failed to dispatch: ' . $e->getMessage());
        }
    }

    /**
     * Manually trigger search analytics sync.
     */
    public function syncAnalytics(Request $request): RedirectResponse
    {
        try {
            TenantApiCredential::where('tenant_id', 0)
                ->where('service', 'google_search_console')
                ->where('is_active', true)
                ->firstOrFail();

            SyncSearchAnalyticsJob::dispatch(0);

            return redirect()->route('admin.gsc.index')
                ->with('success', 'Search analytics sync job dispatched to background.');
        } catch (\Exception $e) {
            return redirect()->route('admin.gsc.index')
                ->with('error', 'Failed to dispatch: ' . $e->getMessage());
        }
    }

    /**
     * Manually submit page to Google Indexing API.
     */
    public function submitIndexing(Request $request): RedirectResponse
    {
        $request->validate(['content_id' => 'required|exists:contents,id']);

        try {
            $content = Content::findOrFail($request->content_id);
            
            SubmitToIndexingApiJob::dispatch($content);

            return redirect()->route('admin.gsc.index')
                ->with('success', 'URL queued for submission to Google Indexing API.');
        } catch (\Exception $e) {
            return redirect()->route('admin.gsc.index')
                ->with('error', 'Failed to submit: ' . $e->getMessage());
        }
    }
}
