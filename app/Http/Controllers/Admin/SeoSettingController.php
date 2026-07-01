<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;

class SeoSettingController extends Controller
{
    /**
     * Display the SEO settings form.
     */
    public function index()
    {
        // Settings are grouped by tabs in the UI
        $settings = [
            'seo_global' => SystemSetting::group('seo_global'),
            'seo_multilingual' => SystemSetting::group('seo_multilingual'),
            'seo_ai_pipeline' => SystemSetting::group('seo_ai_pipeline'),
            'seo_ai_prompt' => SystemSetting::group('seo_ai_prompt'),
            'seo_schema' => SystemSetting::group('seo_schema'),
            'seo_indexing' => SystemSetting::group('seo_indexing'),
            'seo_redirect' => SystemSetting::group('seo_redirect'),
            'seo_advanced' => SystemSetting::group('seo_advanced'),
        ];

        // Calculate Database Garbage (Temporary AI Generation Artifacts)
        $completedJobs = \App\Models\AiGenerationJob::withoutGlobalScopes()
            ->where('status', 'completed')
            ->where(function($q) {
                $q->whereNotNull('phase_1_draft')
                  ->orWhereNotNull('phase_5_combined')
                  ->orWhereNotNull('phase_6_html');
            })->get();
            
        $garbageBytes = 0;
        foreach ($completedJobs as $job) {
            $garbageBytes += mb_strlen($job->phase_1_lsi ?? '');
            $garbageBytes += mb_strlen($job->phase_1_draft ?? '');
            $garbageBytes += mb_strlen($job->phase_3_questions ?? '');
            $garbageBytes += mb_strlen($job->phase_4_answers ?? '');
            $garbageBytes += mb_strlen($job->phase_5_combined ?? '');
            $garbageBytes += mb_strlen($job->phase_6_html ?? '');
        }
        
        $garbageSizeMB = round($garbageBytes / 1024 / 1024, 2);
        $garbageCount = $completedJobs->count();

        return view('admin.seo.settings', compact('settings', 'garbageSizeMB', 'garbageCount'));
    }
    
    /**
     * Clean up completed AI Generation Jobs to free up database storage.
     */
    public function cleanGarbage()
    {
        $completedJobs = \App\Models\AiGenerationJob::withoutGlobalScopes()
            ->where('status', 'completed')
            ->get();
            
        $count = 0;
        foreach ($completedJobs as $job) {
            if ($job->phase_1_draft || $job->phase_5_combined) {
                $job->update([
                    'phase_1_lsi' => null,
                    'phase_1_draft' => null,
                    'phase_3_questions' => null,
                    'phase_4_answers' => null,
                    'phase_5_combined' => null,
                    'phase_6_html' => null,
                ]);
                $count++;
            }
        }
        
        return back()->with('success', "Berhasil membersihkan {$count} data sementara (sampah) dari database.");
    }
}
