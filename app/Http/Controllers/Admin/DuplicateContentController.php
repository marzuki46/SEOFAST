<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CanonicalMapping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class DuplicateContentController extends Controller
{
    public function index(Request $request)
    {
        $query = CanonicalMapping::with([
            'content:id,target_keyword,slug,status,title',
            'canonicalTarget:id,target_keyword,slug,status,title',
        ])->orderBy('similarity_score', 'desc');

        // Filter
        $filter = $request->input('filter', 'unresolved');
        if ($filter === 'resolved') {
            $query->where('is_resolved', true);
        } elseif ($filter === 'unresolved') {
            $query->where('is_resolved', false);
        }

        if ($request->filled('min_score')) {
            $query->where('similarity_score', '>=', (float) $request->min_score);
        }

        $mappings = $query->with([
            'content:id,target_keyword,slug,status',
            'canonicalTarget:id,target_keyword,slug,status',
        ])->paginate(25);

        $stats = [
            'total' => CanonicalMapping::count(),
            'unresolved' => CanonicalMapping::where('is_resolved', false)->count(),
            'high' => CanonicalMapping::where('similarity_score', '>=', 0.8)->count(),
            'moderate' => CanonicalMapping::whereBetween('similarity_score', [0.6, 0.8])->count(),
        ];

        return view('admin.duplicates.index', compact('mappings', 'stats', 'filter'));
    }

    public function detect()
    {
        Artisan::call('seofast:detect-duplicates', ['--limit' => 200]);
        $output = trim(Artisan::output());

        return redirect()->route('admin.duplicates.index')
            ->with('success', $output ?: 'Deteksi selesai.');
    }

    public function resolve(CanonicalMapping $mapping)
    {
        $mapping->update([
            'is_resolved' => true,
            'resolved_at' => now(),
        ]);

        return redirect()->route('admin.duplicates.index')
            ->with('success', 'Marked as resolved.');
    }

    public function unresolve(CanonicalMapping $mapping)
    {
        $mapping->update(['is_resolved' => false, 'resolved_at' => null]);

        return redirect()->route('admin.duplicates.index')
            ->with('success', 'Reopened.');
    }
}
