<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Models\Activity;
use App\Models\Invoice;
use App\Models\User;
use App\Models\Product;
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
        ];
    }
}