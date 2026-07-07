<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiGenerationJob;
use App\Models\Content;
use App\Models\Activity;
use App\Models\GscSearchAnalytics;
use App\Models\GscUrlInspection;
use App\Models\Invoice;
use App\Models\Redirect;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show the admin dashboard.
     */
    public function index(): View
    {
        $stats = $this->getDashboardStats();

        return view('admin.dashboard', compact('stats'));
    }

    /**
     * Run cache-html artisan command and redirect back.
     */
    public function cacheHtml(): RedirectResponse
    {
        Artisan::call('seofast:cache-html');

        $output = Artisan::output();
        $lines = array_filter(explode("\n", trim($output)));
        $message = '';
        foreach ($lines as $line) {
            if (str_starts_with($line, 'Done!')) {
                $message = $line;
                break;
            }
        }

        return redirect()->back()->with(
            'success',
            'Cache HTML berhasil diperbarui! ' . ($message ?: '')
        );
    }

    /**
     * Send queue restart signal.
     */
    public function restartQueue(): RedirectResponse
    {
        Artisan::call('queue:restart');

        return redirect()->back()->with(
            'success',
            'Sinyal restart Queue Worker berhasil dikirim! Worker akan restart setelah job selesai.'
        );
    }

    /**
     * Get dashboard statistics.
     */
    private function getDashboardStats(): array
    {
        $totalUsers    = User::where('role', 'user')->count();
        $totalContent  = Content::count();
        $publishedContent = Content::where('status', 'published')->count();
        $totalOrders   = Invoice::count();
        $totalRevenue  = Invoice::where('status', 'paid')->sum('total');

        // Recent Activities
        $recentActivities = Activity::with('causer')
            ->latest()
            ->take(10)
            ->get();

        // Content status distribution
        $contentByStatus = Content::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        // Monthly new content for current year
        $monthlyContent = Content::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as total')
        )
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $monthlyContentData = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyContentData[] = $monthlyContent[$i] ?? 0;
        }

        // System Server Stats
        $phpSapi        = php_sapi_name();
        $opcacheEnabled = function_exists('opcache_get_status') && opcache_get_status() !== false;
        $phpVersion     = PHP_VERSION;

        // Top products
        $topProducts = Product::withCount('invoices')
            ->orderByDesc('invoices_count')
            ->take(5)
            ->get();

        // Queue Worker Health
        $pendingJobs   = AiGenerationJob::where('status', 'pending')->count();
        $activeJobs    = AiGenerationJob::whereIn('status', ['processing', 'phase_1', 'phase_2', 'phase_3', 'phase_4', 'phase_5', 'phase_6', 'phase_7'])->count();
        $stuckJobs     = AiGenerationJob::where('status', 'pending')
            ->where('updated_at', '<', now()->subMinutes(10))
            ->count();

        $queueStatus = $stuckJobs > 0 ? 'stuck' : ($activeJobs > 0 ? 'running' : 'idle');

        // ─── SEO Dashboard Stats ───────────────────────────────

        // GSC Search Analytics — aggregated last 28 days
        $gscQuery = GscSearchAnalytics::byDateRange(now()->subDays(28), now()->today());
        $gscAggregate = (clone $gscQuery)
            ->selectRaw('COALESCE(SUM(clicks),0) as total_clicks')
            ->selectRaw('COALESCE(SUM(impressions),0) as total_impressions')
            ->selectRaw('CASE WHEN SUM(impressions)>0 THEN ROUND(SUM(clicks)/SUM(impressions)*100,2) ELSE 0 END as avg_ctr')
            ->selectRaw('COALESCE(ROUND(AVG(position),1),0) as avg_position')
            ->first();

        // Top 10 queries by clicks
        $topQueries = (clone $gscQuery)
            ->select('query', DB::raw('SUM(clicks) as clicks'), DB::raw('SUM(impressions) as impressions'), DB::raw('ROUND(AVG(position),1) as avg_pos'))
            ->groupBy('query')
            ->orderByDesc('clicks')
            ->take(10)
            ->get();

        // GSC Index Coverage
        $indexCoverage = GscUrlInspection::select('coverage_state', DB::raw('COUNT(*) as total'))
            ->groupBy('coverage_state')
            ->pluck('total', 'coverage_state')
            ->toArray();

        // Content freshness
        $now = now();
        $freshness = [
            'last_7'  => Content::where('status', 'published')->where('published_at', '>=', $now->copy()->subDays(7))->count(),
            'last_30' => Content::where('status', 'published')->where('published_at', '>=', $now->copy()->subDays(30))->count(),
            'last_90' => Content::where('status', 'published')->where('published_at', '>=', $now->copy()->subDays(90))->count(),
            'older'   => Content::where('status', 'published')->where('published_at', '<', $now->copy()->subDays(90))->count(),
        ];

        // Top redirects by hits (active only)
        $topRedirects = Redirect::active()->orderByDesc('hits')->take(5)->get(['old_url', 'new_url', 'hits', 'status_code']);

        // Readability distribution
        $readability = [
            'low'   => Content::where('status', 'published')->whereNotNull('readability_score')->where('readability_score', '<', 40)->count(),
            'medium'=> Content::where('status', 'published')->whereNotNull('readability_score')->whereBetween('readability_score', [40, 70])->count(),
            'high'  => Content::where('status', 'published')->whereNotNull('readability_score')->where('readability_score', '>', 70)->count(),
        ];

        return [
            'total_users'       => $totalUsers,
            'total_content'     => $totalContent,
            'published_content' => $publishedContent,
            'total_orders'      => $totalOrders,
            'total_revenue'     => $totalRevenue,
            'recent_activities' => $recentActivities,
            'content_by_status' => $contentByStatus,
            'monthly_content'   => $monthlyContentData,
            'top_products'      => $topProducts,
            'server'            => [
                'sapi'        => $phpSapi,
                'opcache'     => $opcacheEnabled,
                'php_version' => $phpVersion,
            ],
            'queue'             => [
                'status'       => $queueStatus,
                'pending'      => $pendingJobs,
                'active'       => $activeJobs,
                'stuck'        => $stuckJobs,
            ],
            'gsc'               => [
                'clicks'       => (int) $gscAggregate?->total_clicks ?? 0,
                'impressions'  => (int) $gscAggregate?->total_impressions ?? 0,
                'avg_ctr'      => (float) $gscAggregate?->avg_ctr ?? 0,
                'avg_position' => (float) $gscAggregate?->avg_position ?? 0,
            ],
            'top_queries'       => $topQueries,
            'index_coverage'    => $indexCoverage,
            'content_freshness' => $freshness,
            'top_redirects'     => $topRedirects,
            'readability'       => $readability,
        ];
    }
}