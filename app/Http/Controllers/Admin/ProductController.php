<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::withoutGlobalScopes()->latest()->get();

        return view('admin.products.index', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'features' => 'nullable|string', // Comma separated
            'image_url' => 'nullable|url',
            'download_url' => 'nullable|url',
            'download_file' => 'nullable|file|max:10240', // max 10MB
        ]);

        $slug = Str::slug($request->name);

        // Ensure unique slug
        $count = Product::withoutGlobalScopes()->where('slug', 'like', "{$slug}%")->count();
        if ($count > 0) {
            $slug = "{$slug}-{$count}";
        }

        // Parse features into JSON array
        $featuresArray = array_filter(array_map('trim', explode(',', $request->features)));

        $downloadFile = null;
        if ($request->hasFile('download_file')) {
            $downloadFile = $request->file('download_file')->store('products/downloads', 'public');
        }

        Product::create([
            'tenant_id' => \App\Models\Tenant::first()?->id ?? 1,
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'price' => $request->price,
            'features' => $featuresArray,
            'image_url' => $request->image_url,
            'download_url' => $request->download_url,
            'download_file' => $downloadFile,
            'shortcode' => '[midtrans_checkout product="' . $slug . '"]',
            'is_active' => true,
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {

        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'features' => 'nullable|string',
            'is_active' => 'boolean',
            'image_url' => 'nullable|url',
            'download_url' => 'nullable|url',
            'download_file' => 'nullable|file|max:10240',
        ]);

        $featuresArray = array_filter(array_map('trim', explode(',', $request->features)));

        $updateData = [
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'features' => $featuresArray,
            'is_active' => $request->has('is_active'),
            'image_url' => $request->image_url,
            'download_url' => $request->download_url,
        ];

        if ($request->hasFile('download_file')) {
            $updateData['download_file'] = $request->file('download_file')->store('products/downloads', 'public');
        }

        $product->update($updateData);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {

        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }
}
