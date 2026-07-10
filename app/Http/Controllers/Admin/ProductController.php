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
        $products = Product::withoutGlobalScopes()->with('categories')->latest()->get();

        return view('admin.products.index', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'features' => 'nullable|string',
            'image_url' => 'nullable|url',
            'image' => 'nullable|image|max:5120',
            'download_url' => 'nullable|url',
            'download_file' => 'nullable|file|max:10240',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:product_categories,id',
            'display_sections' => 'nullable|array',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'nullable|url',
            'gallery' => 'nullable|array',
            'gallery.*' => 'nullable|image|max:5120',
            'specifications' => 'nullable|array',
            'faq' => 'nullable|array',
            'enable_buy_button' => 'boolean',
            'enable_inquiry_button' => 'boolean',
            'inquiry_label' => 'nullable|string|max:255',
            'inquiry_url' => 'nullable|url',
        ]);

        $slug = Str::slug($request->name);

        $count = Product::withoutGlobalScopes()->where('slug', 'like', "{$slug}%")->count();
        if ($count > 0) {
            $slug = "{$slug}-{$count}";
        }

        $featuresArray = array_filter(array_map('trim', explode(',', $request->features ?? '')));

        $downloadFile = null;
        if ($request->hasFile('download_file')) {
            $downloadFile = $request->file('download_file')->store('products/downloads', 'public');
        }

        $product = Product::create([
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
            'display_sections' => $request->display_sections,
            'gallery_images' => $request->gallery_images,
            'specifications' => $this->parseKeyValueArray($request->specifications),
            'faq' => $this->parseFaq($request->faq),
            'enable_buy_button' => $request->boolean('enable_buy_button', true),
            'enable_inquiry_button' => $request->boolean('enable_inquiry_button', false),
            'inquiry_label' => $request->inquiry_label,
            'inquiry_url' => $request->inquiry_url,
        ]);

        if ($request->hasFile('image')) {
            $product->update(['image_url' => $request->file('image')->store('products/images', 'public')]);
        }

        if ($request->hasFile('gallery')) {
            $stored = $product->gallery_images ?? [];
            foreach ($request->file('gallery') as $file) {
                $stored[] = $file->store('products/gallery', 'public');
            }
            $product->update(['gallery_images' => $stored]);
        }

        if ($request->has('categories')) {
            $product->categories()->sync($request->categories);
        }

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $product->load('categories');
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
            'image' => 'nullable|image|max:5120',
            'download_url' => 'nullable|url',
            'download_file' => 'nullable|file|max:10240',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:product_categories,id',
            'display_sections' => 'nullable|array',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'nullable|url',
            'specifications' => 'nullable|array',
            'faq' => 'nullable|array',
            'enable_buy_button' => 'boolean',
            'enable_inquiry_button' => 'boolean',
            'inquiry_label' => 'nullable|string|max:255',
            'inquiry_url' => 'nullable|url',
            'gallery' => 'nullable|array',
            'gallery.*' => 'nullable|image|max:5120',
        ]);

        $featuresArray = array_filter(array_map('trim', explode(',', $request->features ?? '')));

        $updateData = [
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'features' => $featuresArray,
            'is_active' => $request->has('is_active'),
            'image_url' => $request->image_url,
            'download_url' => $request->download_url,
            'display_sections' => $request->display_sections,
            'gallery_images' => $request->gallery_images,
            'specifications' => $this->parseKeyValueArray($request->specifications),
            'faq' => $this->parseFaq($request->faq),
            'enable_buy_button' => $request->boolean('enable_buy_button', true),
            'enable_inquiry_button' => $request->boolean('enable_inquiry_button', false),
            'inquiry_label' => $request->inquiry_label,
            'inquiry_url' => $request->inquiry_url,
        ];

        if ($request->hasFile('download_file')) {
            $updateData['download_file'] = $request->file('download_file')->store('products/downloads', 'public');
        }

        if ($request->hasFile('image')) {
            $updateData['image_url'] = $request->file('image')->store('products/images', 'public');
        }

        if ($request->hasFile('gallery')) {
            $stored = $updateData['gallery_images'] ?? [];
            foreach ($request->file('gallery') as $file) {
                $stored[] = $file->store('products/gallery', 'public');
            }
            $updateData['gallery_images'] = $stored;
        }

        $product->update($updateData);

        if ($request->has('categories')) {
            $product->categories()->sync($request->categories);
        } else {
            $product->categories()->detach();
        }

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }

    private function parseKeyValueArray(?array $items): ?array
    {
        if (empty($items)) return null;
        $result = [];
        foreach ($items as $item) {
            if (!empty($item['key']) || !empty($item['value'])) {
                $result[] = [
                    'key' => $item['key'] ?? '',
                    'value' => $item['value'] ?? '',
                ];
            }
        }
        return empty($result) ? null : $result;
    }

    private function parseFaq(?array $items): ?array
    {
        if (empty($items)) return null;
        $result = [];
        foreach ($items as $item) {
            if (!empty($item['question']) || !empty($item['answer'])) {
                $result[] = [
                    'question' => $item['question'] ?? '',
                    'answer' => $item['answer'] ?? '',
                ];
            }
        }
        return empty($result) ? null : $result;
    }
}
