<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\WordPressImportService;
use App\Services\WordPressExportService;
use App\Models\SiloBlueprint;
use Illuminate\Http\Request;

class WordPressController extends Controller
{
    public function index()
    {
        $blueprints = SiloBlueprint::orderBy('silo_name')->get();
        return view('admin.content.wordpress', compact('blueprints'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'wxr_file' => 'required|file|mimes:xml',
            'post_types' => 'nullable|array',
            'post_types.*' => 'in:post,page',
            'skip_existing' => 'boolean',
        ]);

        $xml = file_get_contents($request->file('wxr_file')->getRealPath());

        $service = app(WordPressImportService::class);

        try {
            $stats = $service->import($xml, [
                'post_types' => $request->input('post_types', ['post', 'page']),
                'skip_existing' => $request->boolean('skip_existing', true),
                'import_media_library' => $request->boolean('import_media_library', false),
            ]);

            return redirect()->route('admin.wordpress.index')
                ->with('success', "Import selesai! {$stats['imported']} konten diimport, {$stats['skipped']} dilewati, {$stats['images']} gambar diproses, {$stats['categories']} kategori.");
        } catch (\Exception $e) {
            return redirect()->route('admin.wordpress.index')
                ->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }

    public function export(Request $request)
    {
        $service = app(WordPressExportService::class);

        $xml = $service->export([
            'silo_id' => $request->input('silo_id'),
        ]);

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="export-' . date('Y-m-d') . '.xml"',
        ]);
    }
}
