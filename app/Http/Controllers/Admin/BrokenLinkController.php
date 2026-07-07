<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BrokenLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class BrokenLinkController extends Controller
{
    public function index(Request $request)
    {
        $query = BrokenLink::with('content:id,target_keyword,slug,status');

        // Filter: all / broken / valid
        $filter = $request->input('filter', 'broken');
        if ($filter === 'broken') {
            $query->where('is_broken', true);
        } elseif ($filter === 'valid') {
            $query->where('is_broken', false)->whereNotNull('status_code');
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('link_type', $request->type);
        }

        $links = $query->orderBy('is_broken', 'desc')->orderByDesc('checked_at')->paginate(25);

        $stats = [
            'total' => BrokenLink::count(),
            'broken' => BrokenLink::where('is_broken', true)->count(),
            'internal' => BrokenLink::where('link_type', 'internal')->where('is_broken', true)->count(),
            'external' => BrokenLink::where('link_type', 'external')->where('is_broken', true)->count(),
        ];

        return view('admin.broken-links.index', compact('links', 'stats', 'filter'));
    }

    public function scan()
    {
        Artisan::call('seofast:scan-links', ['--limit' => 200]);
        $output = trim(Artisan::output());

        return redirect()->route('admin.broken-links.index')
            ->with('success', $output ?: 'Scan selesai.');
    }

    public function destroy(BrokenLink $brokenLink)
    {
        $brokenLink->delete();

        return redirect()->route('admin.broken-links.index')
            ->with('success', 'Link entry cleared.');
    }

    public function clearAll()
    {
        BrokenLink::truncate();

        return redirect()->route('admin.broken-links.index')
            ->with('success', 'All link scan data cleared.');
    }
}
