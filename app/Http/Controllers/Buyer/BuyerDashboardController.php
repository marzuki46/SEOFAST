<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\BuyerOrder;
use App\Models\BuyerProductAccess;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BuyerDashboardController extends Controller
{
    public function index()
    {
        $buyer = Auth::guard('buyer')->user();

        $recentOrders = BuyerOrder::where('buyer_id', $buyer->id)
            ->with('product')
            ->latest()
            ->take(5)
            ->get();

        $ownedProducts = BuyerProductAccess::where('buyer_id', $buyer->id)
            ->where('is_active', true)
            ->with('product')
            ->get();

        $pendingOrders = BuyerOrder::where('buyer_id', $buyer->id)
            ->whereIn('status', ['pending', 'paid'])
            ->count();

        $allProducts = Product::where('is_active', true)
            ->whereNotIn('id', $ownedProducts->pluck('product_id')->toArray())
            ->take(4)
            ->get();

        return view('buyer.dashboard', compact(
            'buyer', 'recentOrders', 'ownedProducts', 'pendingOrders', 'allProducts'
        ));
    }
}
