<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductCategoryController extends Controller
{
    public function index()
    {
        $categories = ProductCategory::orderBy('order')->orderBy('name')->get();
        return view('admin.product-categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'order' => 'nullable|integer|min:0',
        ]);

        $slug = Str::slug($request->name);
        $baseSlug = $slug;
        $counter = 1;
        while (ProductCategory::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter++;
        }

        ProductCategory::create([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'order' => $request->order ?? 0,
        ]);

        return redirect()->route('admin.product-categories.index')->with('success', 'Kategori berhasil dibuat.');
    }

    public function update(Request $request, ProductCategory $productCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $slug = Str::slug($request->name);
        $baseSlug = $slug;
        $counter = 1;
        while (ProductCategory::where('slug', $slug)->where('id', '!=', $productCategory->id)->exists()) {
            $slug = $baseSlug . '-' . $counter++;
        }

        $productCategory->update([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'order' => $request->order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.product-categories.index')->with('success', 'Kategori berhasil diupdate.');
    }

    public function destroy(ProductCategory $productCategory)
    {
        $productCategory->delete();
        return redirect()->route('admin.product-categories.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
