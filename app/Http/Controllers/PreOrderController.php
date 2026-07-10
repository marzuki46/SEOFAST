<?php

namespace App\Http\Controllers;

use App\Models\PreOrder;
use App\Models\Product;
use Illuminate\Http\Request;

class PreOrderController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:1000',
        ]);

        $existing = PreOrder::where('product_id', $product->id)
            ->where('email', $request->email)
            ->first();

        if ($existing) {
            return back()->with('error', 'You have already pre-ordered this product.');
        }

        PreOrder::create([
            'product_id' => $product->id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'notes' => $request->notes,
        ]);

        return back()->with('success', 'Pre-order berhasil! Kami akan memberi tahu Anda saat produk ini diluncurkan.');
    }
}
