<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::orderBy('created_at', 'desc')->get();
        return view('admin.pages.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.pages.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages',
        ]);

        $page = Page::create($request->only('title', 'slug', 'meta_title', 'meta_description'));
        
        return redirect()->route('admin.pages.builder', $page->id)->with('success', 'Page created! Now build it.');
    }

    public function builder(Page $page)
    {
        return view('admin.pages.builder', compact('page'));
    }

    public function saveBuilder(Request $request, Page $page)
    {
        $page->update([
            'html_content' => $request->input('html'),
            'css_content' => $request->input('css'),
            'builder_data' => $request->input('components'), // JSON from grapesjs
        ]);

        return response()->json(['success' => true]);
    }
    
    public function setHomepage(Page $page)
    {
        Page::where('is_homepage', true)->update(['is_homepage' => false]);
        $page->update(['is_homepage' => true]);
        return redirect()->back()->with('success', 'Homepage updated successfully.');
    }
}
