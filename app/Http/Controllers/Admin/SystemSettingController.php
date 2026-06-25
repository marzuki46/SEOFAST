<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class SystemSettingController extends Controller
{
    public function index()
    {
        // Settings are grouped by tabs in the UI
        $settings = [
            'general' => SystemSetting::group('general'),
            'auth'    => SystemSetting::group('auth'),
            'ai'      => SystemSetting::group('ai'),
            'payment' => SystemSetting::group('payment'),
            'seo'     => SystemSetting::group('seo'),
            'email'   => SystemSetting::group('email'),
            'storage' => SystemSetting::group('storage'),
            'queue'   => SystemSetting::group('queue'),
            'api'     => SystemSetting::group('api'),
        ];

        return view('admin.settings.system', compact('settings'));
    }

    public function update(Request $request)
    {
        $group = $request->input('group', 'general');
        $inputs = $request->except(['_token', 'group', '_method', 'expected_checkboxes']);

        foreach ($inputs as $key => $value) {
            // Handle checkboxes (boolean)
            if ($value === 'on' || $value === 'true') {
                $value = true;
            } elseif ($value === 'off' || $value === 'false') {
                $value = false;
            }

            SystemSetting::set($key, $value, $group);
        }

        // Handle checkboxes that were unchecked (and thus not in the request)
        if ($request->has('expected_checkboxes')) {
            $expected = json_decode($request->input('expected_checkboxes'), true) ?? [];
            foreach ($expected as $cb) {
                if (!array_key_exists($cb, $inputs)) {
                    SystemSetting::set($cb, false, $group);
                }
            }
        }

        return back()->with('success', 'Pengaturan sistem ('.ucfirst($group).') berhasil disimpan.')
            ->with('active_tab', $group);
    }

    public function clearCache()
    {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        SystemSetting::flushCache();

        return back()->with('success', 'Semua cache sistem berhasil dibersihkan.');
    }
}
