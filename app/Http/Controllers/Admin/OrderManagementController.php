<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\OrderVerified;
use App\Models\BuyerOrder;
use App\Models\BuyerProductAccess;
use App\Models\Buyer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class OrderManagementController extends Controller
{
    /**
     * List all buyer orders for admin review.
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');

        $orders = BuyerOrder::with(['buyer', 'product'])
            ->when($status !== 'all', fn($q) => $q->where('status', $status))
            ->latest()
            ->paginate(15);

        $stats = [
            'pending'  => BuyerOrder::where('status', 'pending')->count(),
            'paid'     => BuyerOrder::where('status', 'paid')->count(),
            'verified' => BuyerOrder::where('status', 'verified')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'stats', 'status'));
    }

    /**
     * Verify payment and grant product access.
     */
    public function verify(Request $request, BuyerOrder $order)
    {
        if (!in_array($order->status, ['pending', 'paid'])) {
            return back()->with('error', 'Order ini sudah diproses sebelumnya.');
        }

        $order->update([
            'status'      => 'verified',
            'verified_at' => now(),
            'verified_by' => Auth::id(),
            'admin_note'  => $request->input('note'),
        ]);

        // Grant product access
        BuyerProductAccess::updateOrCreate(
            ['buyer_id' => $order->buyer_id, 'product_id' => $order->product_id],
            [
                'order_id'   => $order->id,
                'granted_at' => now(),
                'is_active'  => true,
            ]
        );

        try {
            Mail::to($order->buyer->email)->send(new OrderVerified($order));
        } catch (\Exception $e) {
            // Silent fail — email is a bonus, not blocking
        }

        return back()->with('success', "Order #{$order->order_number} berhasil diverifikasi. Akses produk telah diberikan.");
    }

    /**
     * Reject a payment.
     */
    public function reject(Request $request, BuyerOrder $order)
    {
        $request->validate(['note' => 'required|string|max:500']);

        $order->update([
            'status'     => 'rejected',
            'admin_note' => $request->note,
        ]);

        return back()->with('success', "Order #{$order->order_number} ditolak.");
    }

    /**
     * Show order detail.
     */
    public function show(BuyerOrder $order)
    {
        $order->load(['buyer', 'product', 'verifiedBy', 'access']);
        return view('admin.orders.show', compact('order'));
    }
}
