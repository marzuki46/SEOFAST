<?php

namespace App\Http\Controllers;

use App\Models\CompetitorAnalysis;
use App\Services\CompetitorAnalysisService;
use Illuminate\Http\Request;

class CompetitorAnalysisController extends Controller
{
    public function index()
    {
        $analyses = CompetitorAnalysis::latest()->paginate(10);
        return view('competitor-analysis.index', compact('analyses'));
    }

    public function store(Request $request, CompetitorAnalysisService $service)
    {
        $request->validate([
            'keyword' => 'required|string|max:255',
        ]);

        $analysis = CompetitorAnalysis::create([
            'keyword' => $request->keyword,
            'status' => 'pending',
        ]);

        $service->analyze($analysis);

        return redirect()->route('admin.competitor-analysis.show', $analysis)
            ->with('success', 'Analisis selesai!');
    }

    public function show(CompetitorAnalysis $analysis)
    {
        return view('competitor-analysis.show', compact('analysis'));
    }

    public function destroy(CompetitorAnalysis $analysis)
    {
        $analysis->delete();
        return redirect()->route('admin.competitor-analysis.index')
            ->with('success', 'Analisis berhasil dihapus.');
    }
}
