<?php

namespace App\Http\Controllers;

use App\Models\BuyerOrder;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Services\Payment\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductSalesController extends Controller
{
    public function __construct(
        protected MidtransService $midtrans
    ) {}

    public function index(Request $request)
    {
        $query = Product::where('is_active', true)->with('categories');

        // Search
        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $categorySlug = $request->input('category');
            $query->whereHas('categories', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        // Sort
        $sort = $request->input('sort', 'latest');
        match ($sort) {
            'price_asc' => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'name_asc' => $query->orderBy('name', 'asc'),
            'name_desc' => $query->orderBy('name', 'desc'),
            default => $query->latest(),
        };

        $products = $query->paginate(12)->withQueryString();
        $categories = ProductCategory::active()->orderBy('order')->orderBy('name')->withCount('products')->get();

        return view('products.catalog', compact('products', 'categories'));
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)->with('categories')->firstOrFail();
        return view('products.show', compact('product'));
    }

    public function order(Request $request, Product $product)
    {
        if (!Auth::guard('buyer')->check()) {
            return redirect()->route('buyer.login')->with('error', 'Silakan login sebagai pembeli terlebih dahulu.');
        }

        $buyer = Auth::guard('buyer')->user();

        $order = BuyerOrder::create([
            'buyer_id' => $buyer->id,
            'product_id' => $product->id,
            'order_number' => BuyerOrder::generateOrderNumber(),
            'unique_code' => BuyerOrder::generateUniqueCode(),
            'amount' => $product->price,
            'unique_amount' => 0,
            'status' => 'pending',
            'payment_method' => 'midtrans',
        ]);

        $snapToken = $this->midtrans->createBuyerSnapToken($order);

        if (!$snapToken) {
            return redirect()->back()->with('error', 'Gagal terhubung ke gateway pembayaran. Silakan coba lagi.');
        }

        session(['pending_order' => $order->order_number]);

        return view('products.checkout', compact('order', 'snapToken'));
    }
}
