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

        return view('admin.seo.settings', compact('settings'));
    }
}
