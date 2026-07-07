<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class UrlAuditController extends Controller
{
    public function index(Request $request)
    {
        $results = cache('url_audit_results', []);
        $summary = cache('url_audit_summary', null);

        $filter = $request->get('filter');
        $search = $request->get('search');

        if ($filter === 'good') {
            $results = array_filter($results, fn($r) => $r['score'] >= 80);
        } elseif ($filter === 'needs_work') {
            $results = array_filter($results, fn($r) => $r['score'] >= 50 && $r['score'] < 80);
        } elseif ($filter === 'poor') {
            $results = array_filter($results, fn($r) => $r['score'] < 50);
        }

        if ($search) {
            $results = array_filter($results, fn($r) =>
                str_contains(strtolower($r['title']), strtolower($search))
                || str_contains(strtolower($r['slug']), strtolower($search))
            );
        }

        $perPage = 30;
        $page = (int) $request->get('page', 1);
        $total = count($results);
        $offset = ($page - 1) * $perPage;
        $paginated = array_slice(array_values($results), $offset, $perPage);

        return view('admin.url-audit.index', compact(
            'paginated', 'summary', 'filter', 'search', 'total', 'page', 'perPage'
        ));
    }

    public function run()
    {
        Artisan::call('seofast:audit-urls');
        return redirect()->route('admin.url-audit.index')
            ->with('success', 'URL audit completed.');
    }
}
