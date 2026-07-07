<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class ReadabilityController extends Controller
{
    public function index(Request $request)
    {
        $query = Content::whereIn('status', ['published', 'draft'])
            ->whereNotNull('readability_score')
            ->orderBy('readability_score');

        if ($request->filled('level')) {
            $level = $request->level;
            if ($level === 'low') $query->where('readability_score', '<', 40);
            elseif ($level === 'medium') $query->whereBetween('readability_score', [40, 70]);
            elseif ($level === 'high') $query->where('readability_score', '>', 70);
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($qry) use ($q) {
                $qry->where('title', 'like', "%{$q}%")
                    ->orWhere('target_keyword', 'like', "%{$q}%");
            });
        }

        $contents = $query->paginate(25);

        $stats = [
            'total' => Content::whereIn('status', ['published', 'draft'])->whereNotNull('readability_score')->count(),
            'low' => Content::whereIn('status', ['published', 'draft'])->whereNotNull('readability_score')->where('readability_score', '<', 40)->count(),
            'medium' => Content::whereIn('status', ['published', 'draft'])->whereNotNull('readability_score')->whereBetween('readability_score', [40, 70])->count(),
            'high' => Content::whereIn('status', ['published', 'draft'])->whereNotNull('readability_score')->where('readability_score', '>', 70)->count(),
            'avg' => round(Content::whereIn('status', ['published', 'draft'])->whereNotNull('readability_score')->avg('readability_score') ?: 0, 1),
            'unscored' => Content::whereIn('status', ['published', 'draft'])->whereNull('readability_score')->count(),
        ];

        return view('admin.readability.index', compact('contents', 'stats'));
    }

    public function compute()
    {
        Artisan::call('seofast:compute-readability', ['--limit' => 500]);

        return redirect()->route('admin.readability.index')
            ->with('success', nl2br(e(Artisan::output())));
    }
}
