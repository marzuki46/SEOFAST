<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductSalesController extends Controller
{
    public function index()
    {
        $products = Product::where('is_active', true)->paginate(12);
        return view('products.catalog', compact('products'));
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        return view('products.show', compact('product'));
    }

    public function order(Request $request, Product $product)
    {
        // Handle logic for checking out or redirect to order
        return redirect()->back()->with('success', 'Order has been placed for ' . $product->name);
    }
}
