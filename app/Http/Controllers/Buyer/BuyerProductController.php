<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\BuyerProductAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BuyerProductController extends Controller
{
    public function index()
    {
        $buyer = Auth::guard('buyer')->user();
        $accesses = BuyerProductAccess::where('buyer_id', $buyer->id)
            ->where('is_active', true)
            ->with('product', 'order')
            ->latest()
            ->get();

        return view('buyer.products.index', compact('accesses'));
    }

    public function access(BuyerProductAccess $access)
    {
        $buyer = Auth::guard('buyer')->user();
        if ($access->buyer_id !== $buyer->id) abort(403);
        if (!$access->is_active) abort(403, 'Akses produk ini tidak aktif.');
        if ($access->isExpired()) abort(403, 'Akses produk ini telah kadaluarsa.');

        $access->trackAccess();
        $product = $access->product;

        return view('buyer.products.access', compact('access', 'product'));
    }
}
