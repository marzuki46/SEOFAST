<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PreOrder;
use App\Models\Product;
use App\Notifications\PreOrderLaunched;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class PreOrderController extends Controller
{
    public function global()
    {
        $products = Product::withCount('preOrders')->having('pre_orders_count', '>', 0)->latest()->get();
        return view('admin.pre-orders.global', compact('products'));
    }

    public function index(Product $product)
    {
        $preOrders = $product->preOrders()->latest()->get();
        return view('admin.pre-orders.index', compact('product', 'preOrders'));
    }

    public function launch(Product $product)
    {
        if ($product->launched_at) {
            return back()->with('error', 'Product already launched.');
        }

        $product->update(['launched_at' => now()]);

        $preOrders = $product->preOrders()->notNotified()->get();

        foreach ($preOrders as $preOrder) {
            Notification::route('mail', $preOrder->email)
                ->notify(new PreOrderLaunched($product, $preOrder));

            $preOrder->update(['notified_at' => now()]);
        }

        return redirect()->route('admin.pre-orders.index', $product)
            ->with('success', 'Product launched! ' . $preOrders->count() . ' pre-order customers have been notified.');
    }
}
