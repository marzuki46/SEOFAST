<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Models\GscSearchAnalytics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class SerpRankController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter');
        $search = $request->get('search');

        // Latest position per content from GSC (last 28 days)
        $latestGsc = GscSearchAnalytics::select(
                'content_id',
                'query',
                DB::raw('MAX(data_date) as max_date')
            )
            ->byDateRange(now()->subDays(28), now()->today())
            ->groupBy('content_id', 'query');

        $ranks = Content::joinSub($latestGsc, 'latest_gsc', function ($join) {
                $join->on('contents.id', '=', 'latest_gsc.content_id');
            })
            ->join('gsc_search_analytics', function ($join) {
                $join->on('contents.id', '=', 'gsc_search_analytics.content_id')
                    ->on('latest_gsc.query', '=', 'gsc_search_analytics.query')
                    ->on('latest_gsc.max_date', '=', 'gsc_search_analytics.data_date');
            })
            ->whereIn('contents.status', ['published', 'draft'])
            ->select(
                'contents.id as content_id',
                'contents.target_keyword',
                'contents.slug',
                'contents.status',
                'gsc_search_analytics.query as gsc_query',
                'gsc_search_analytics.position',
                'gsc_search_analytics.clicks',
                'gsc_search_analytics.impressions',
                'gsc_search_analytics.ctr',
                'gsc_search_analytics.data_date'
            )
            ->orderBy('gsc_search_analytics.position')
            ->orderByDesc('gsc_search_analytics.clicks');

        $noDataContents = Content::whereNotExists(function ($q) {
                $q->select(DB::raw(1))
                    ->from('gsc_search_analytics')
                    ->whereColumn('gsc_search_analytics.content_id', 'contents.id')
                    ->where('data_date', '>=', now()->subDays(28));
            })
            ->whereIn('status', ['published', 'draft'])
            ->get(['id as content_id', 'target_keyword', 'slug', 'status']);

        if ($filter === 'top3') {
            $ranks->where('gsc_search_analytics.position', '<=', 3);
        } elseif ($filter === 'top10') {
            $ranks->where('gsc_search_analytics.position', '<=', 10);
        } elseif ($filter === 'poor') {
            $ranks->where('gsc_search_analytics.position', '>', 20);
        } elseif ($filter === 'not_ranking') {
            $ranks->where('gsc_search_analytics.position', '>', 50);
        }

        if ($search) {
            $ranks->where(function ($q) use ($search) {
                $q->where('contents.target_keyword', 'like', "%{$search}%")
                  ->orWhere('gsc_search_analytics.query', 'like', "%{$search}%")
                  ->orWhere('contents.slug', 'like', "%{$search}%");
            });
        }

        $paginated = $ranks->paginate(30)->withQueryString();

        // Trend: previous period data
        $contentIds = $paginated->pluck('content_id')->toArray();
        $keywords = $paginated->pluck('gsc_query')->toArray();
        $previousData = [];
        if ($contentIds && $keywords) {
            $prevRows = GscSearchAnalytics::byDateRange(now()->subDays(56), now()->subDays(28))
                ->whereIn('content_id', $contentIds)
                ->whereIn('query', $keywords)
                ->select('content_id', 'query', DB::raw('AVG(position) as avg_pos'))
                ->groupBy('content_id', 'query')
                ->get()
                ->keyBy(fn($r) => $r->content_id . '_' . $r->query);
            $previousData = $prevRows;
        }

        $stats = [
            'total_keywords' => Content::whereIn('status', ['published', 'draft'])->whereNotNull('target_keyword')->count(),
            'ranked_keywords' => GscSearchAnalytics::byDateRange(now()->subDays(28), now()->today())->distinct('query')->count('query'),
            'avg_position' => round(GscSearchAnalytics::byDateRange(now()->subDays(28), now()->today())->avg('position') ?? 0, 1),
            'no_data' => count($noDataContents),
        ];

        // ── Recommendations ──
        $recommendations = $this->computeRecommendations();

        return view('admin.serp-rank.index', compact(
            'paginated', 'noDataContents', 'stats', 'filter', 'search', 'previousData', 'recommendations'
        ));
    }

    protected function computeRecommendations(): array
    {
        $recs = [];
        $now = now();
        $start28 = $now->copy()->subDays(28);
        $start56 = $now->copy()->subDays(56);

        // 1. CTR Optimization: high impressions (>100) but low CTR (<2%)
        $lowCtr = GscSearchAnalytics::select(
                'content_id', 'query',
                DB::raw('SUM(impressions) as total_impressions'),
                DB::raw('SUM(clicks) as total_clicks'),
                DB::raw('CASE WHEN SUM(impressions)>0 THEN ROUND(SUM(clicks)/SUM(impressions)*100,2) ELSE 0 END as ctr_pct'),
                DB::raw('ROUND(AVG(position),1) as avg_pos')
            )
            ->byDateRange($start28, $now)
            ->groupBy('content_id', 'query')
            ->having('total_impressions', '>', 100)
            ->having('ctr_pct', '<', 2)
            ->orderByDesc('total_impressions')
            ->take(5)
            ->get();

        foreach ($lowCtr as $r) {
            $content = Content::find($r->content_id);
            $recs['ctr_optimize'][] = [
                'content_id' => $r->content_id,
                'query' => $r->query,
                'title' => $content?->title ?? $r->query,
                'impressions' => $r->total_impressions,
                'clicks' => $r->total_clicks,
                'ctr' => $r->ctr_pct,
                'position' => $r->avg_pos,
                'action' => 'Optimalkan title & meta description untuk meningkatkan CTR',
                'type' => 'ctr',
            ];
        }

        // 2. Dropping Ranks: position dropped >2 in 28-day comparison
        $currentAvg = GscSearchAnalytics::select(
                'content_id', 'query',
                DB::raw('AVG(position) as cur_pos')
            )
            ->byDateRange($start28, $now)
            ->groupBy('content_id', 'query');

        $prevAvg = GscSearchAnalytics::select(
                'content_id', 'query',
                DB::raw('AVG(position) as prev_pos')
            )
            ->byDateRange($start56, $start28->copy()->subDay())
            ->groupBy('content_id', 'query');

        $dropping = DB::table(DB::raw("({$currentAvg->toSql()}) as cur"))
            ->mergeBindings($currentAvg->getQuery())
            ->join(DB::raw("({$prevAvg->toSql()}) as prev"), function ($j) {
                $j->on('cur.content_id', '=', 'prev.content_id')
                   ->on('cur.query', '=', 'prev.query');
            })
            ->mergeBindings($prevAvg->getQuery())
            ->select(
                'cur.content_id', 'cur.query',
                'cur.cur_pos', 'prev.prev_pos',
                DB::raw('ROUND(cur.cur_pos - prev.prev_pos, 1) as drop_val')
            )
            ->having('drop_val', '>', 2)
            ->orderByDesc('drop_val')
            ->take(5)
            ->get();

        foreach ($dropping as $r) {
            $content = Content::find($r->content_id);
            $recs['dropping'][] = [
                'content_id' => $r->content_id,
                'query' => $r->query,
                'title' => $content?->title ?? $r->query,
                'previous' => round($r->prev_pos, 1),
                'current' => round($r->cur_pos, 1),
                'drop' => $r->drop_val,
                'action' => 'Refresh konten & update internal links untuk memulihkan posisi',
                'type' => 'dropping',
            ];
        }

        // 3. Quick Wins: position 4-10 with upward trend or stable
        $quickWins = GscSearchAnalytics::select(
                'content_id', 'query',
                DB::raw('AVG(position) as avg_pos'),
                DB::raw('SUM(impressions) as total_impressions')
            )
            ->byDateRange($start28, $now)
            ->groupBy('content_id', 'query')
            ->having('avg_pos', '>=', 4)
            ->having('avg_pos', '<=', 10)
            ->orderBy('avg_pos')
            ->take(5)
            ->get();

        foreach ($quickWins as $r) {
            $content = Content::find($r->content_id);
            $recs['quick_wins'][] = [
                'content_id' => $r->content_id,
                'query' => $r->query,
                'title' => $content?->title ?? $r->query,
                'position' => round($r->avg_pos, 1),
                'impressions' => $r->total_impressions,
                'action' => 'Tingkatkan internal linking & tambah semantic entities untuk tembus Top 3',
                'type' => 'quick_win',
            ];
        }

        // 4. Content Gap: published content with zero GSC data
        $gapContents = Content::where('status', 'published')
            ->whereNotExists(function ($q) {
                $q->select(DB::raw(1))
                    ->from('gsc_search_analytics')
                    ->whereColumn('gsc_search_analytics.content_id', 'contents.id');
            })
            ->take(5)
            ->get(['id', 'target_keyword', 'slug', 'published_at']);

        foreach ($gapContents as $c) {
            $recs['content_gap'][] = [
                'content_id' => $c->id,
                'query' => $c->target_keyword ?: $c->slug,
                'title' => $c->title,
                'action' => 'Kirim ke Indexing API & optimasi internal links agar terindeks',
                'type' => 'gap',
            ];
        }

        // 5. High Click Potential: high position (>15) but high impression (>500)
        $highPotential = GscSearchAnalytics::select(
                'content_id', 'query',
                DB::raw('AVG(position) as avg_pos'),
                DB::raw('SUM(impressions) as total_impressions')
            )
            ->byDateRange($start28, $now)
            ->groupBy('content_id', 'query')
            ->having('avg_pos', '>', 15)
            ->having('total_impressions', '>', 500)
            ->orderByDesc('total_impressions')
            ->take(5)
            ->get();

        foreach ($highPotential as $r) {
            $content = Content::find($r->content_id);
            $recs['high_potential'][] = [
                'content_id' => $r->content_id,
                'query' => $r->query,
                'title' => $content?->title ?? $r->query,
                'position' => round($r->avg_pos, 1),
                'impressions' => $r->total_impressions,
                'action' => 'Buat konten lebih komprehensif + tambah backlinks untuk naik peringkat',
                'type' => 'potential',
            ];
        }

        return $recs;
    }

    public function generateSample(): \Illuminate\Http\RedirectResponse
    {
        Artisan::call('seofast:sync-gsc', ['--sample' => true, '--days' => 30]);
        $output = Artisan::output();
        return redirect()->route('admin.serp-rank.index')
            ->with('success', 'Sample GSC data generated. ' . nl2br(e($output)));
    }
}
