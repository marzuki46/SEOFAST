<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index(Request $request)
    {
        $folder = $request->query('folder');
        
        $query = Page::orderBy('slug', 'asc');
        
        if ($folder) {
            // Get pages that are exactly children of this folder (one level deep or all nested, let's do all nested for simplicity but prioritize immediate)
            $query->where('slug', 'like', $folder . '/%');
        } else {
            // Only get root level pages (no slashes) OR pages that act as roots
            $query->where('slug', 'not like', '%/%');
        }

        $pages = $query->get();
        
        return view('admin.pages.index', compact('pages', 'folder'));
    }

    public function create()
    {
        $pages = Page::orderBy('slug', 'asc')->get();
        return view('admin.pages.create', compact('pages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'featured_image_upload' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $page = Page::create($request->only('title', 'slug', 'meta_title', 'meta_description'));
        
        $seoMeta = ['title' => $request->meta_title, 'description' => $request->meta_description];
        if ($request->hasFile('featured_image_upload')) {
            $path = $request->file('featured_image_upload')->store('content-images', 'public');
            $seoMeta['og_image'] = '/storage/' . $path;
        }
        $page->updateSeoMeta($seoMeta);
        
        return redirect()->route('admin.pages.builder', $page->id)->with('success', 'Page created! Now build it.');
    }

    public function edit(Page $page)
    {
        $pages = Page::where('id', '!=', $page->id)->orderBy('slug', 'asc')->get();
        return view('admin.pages.edit', compact('page', 'pages'));
    }

    public function update(Request $request, Page $page)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages,slug,' . $page->id,
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'is_published' => 'nullable|boolean',
            'featured_image_upload' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->only('title', 'slug', 'meta_title', 'meta_description');
        $data['is_published'] = $request->has('is_published');
        
        $page->update($data);

        $seoMeta = ['title' => $request->meta_title, 'description' => $request->meta_description];

        if ($request->hasFile('featured_image_upload')) {
            $path = $request->file('featured_image_upload')->store('content-images', 'public');
            $seoMeta['og_image'] = '/storage/' . $path;
        }

        $page->updateSeoMeta($seoMeta);

        return redirect()->route('admin.pages.index')->with('success', 'Page settings and meta updated successfully.');
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
