<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageError;
use App\Models\Redirect;
use Illuminate\Http\Request;

class PageErrorController extends Controller
{
    public function index(Request $request)
    {
        $query = PageError::orderByDesc('count');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where('url', 'like', "%{$q}%");
        }

        $pageErrors = $query->paginate(25);

        return view('admin.errors.index', compact('pageErrors'));
    }

    public function createRedirect(Request $request, PageError $pageError)
    {
        $request->validate([
            'new_url' => 'required|string|max:1024',
            'status_code' => 'required|in:301,302',
        ]);

        $path = parse_url($pageError->url, PHP_URL_PATH);

        Redirect::create([
            'old_url' => ltrim($path, '/'),
            'new_url' => $request->new_url,
            'status_code' => $request->status_code,
            'active' => true,
        ]);

        // Clear the 404 log entry since we've handled it
        $pageError->delete();

        return redirect()->route('admin.errors.index')
            ->with('success', 'Redirect created from 404 error.');
    }

    public function destroy(PageError $pageError)
    {
        $pageError->delete();

        return redirect()->route('admin.errors.index')
            ->with('success', '404 entry cleared.');
    }

    public function clearAll()
    {
        PageError::truncate();

        return redirect()->route('admin.errors.index')
            ->with('success', 'All 404 entries cleared.');
    }
}
