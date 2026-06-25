<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\BuyerOrder;
use App\Models\BuyerProductAccess;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BuyerOrderController extends Controller
{
    public function index()
    {
        $buyer = Auth::guard('buyer')->user();
        $orders = BuyerOrder::where('buyer_id', $buyer->id)
            ->with('product')
            ->latest()
            ->paginate(10);

        return view('buyer.orders.index', compact('orders'));
    }

    public function show(BuyerOrder $order)
    {
        $buyer = Auth::guard('buyer')->user();
        if ($order->buyer_id !== $buyer->id) abort(403);

        $order->load('product', 'verifiedBy');
        return view('buyer.orders.show', compact('order'));
    }

    public function uploadProof(Request $request, BuyerOrder $order)
    {
        $buyer = Auth::guard('buyer')->user();
        if ($order->buyer_id !== $buyer->id) abort(403);
        if (!in_array($order->status, ['pending'])) {
            return back()->with('error', 'Status order tidak memungkinkan upload bukti.');
        }

        $request->validate(['proof' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048']);

        $path = $request->file('proof')->store('payment-proofs', 'public');

        $order->update([
            'payment_proof' => $path,
            'status'        => 'paid',
            'paid_at'       => now(),
        ]);

        return back()->with('success', 'Bukti pembayaran berhasil dikirim. Menunggu verifikasi admin.');
    }
}
