<?php

namespace App\Http\Controllers;

use App\Models\BuyerOrder;
use App\Models\BuyerProductAccess;
use App\Services\Payment\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function __construct(
        protected MidtransService $midtrans
    ) {}

    /**
     * Midtrans webhook notification handler (server-to-server)
     */
    public function notification(Request $request)
    {
        $payload = $request->all();

        Log::info('Midtrans notification received', ['payload' => $payload]);

        if (!$this->midtrans->verifyCallbackSignature($payload)) {
            Log::warning('Midtrans notification signature mismatch', ['payload' => $payload]);
            return response()->json(['error' => 'Invalid signature'], 403);
        }

        $orderId = $payload['order_id'] ?? '';
        $transactionStatus = $payload['transaction_status'] ?? '';
        $fraudStatus = $payload['fraud_status'] ?? '';

        $order = BuyerOrder::where('order_number', $orderId)->first();

        if (!$order) {
            Log::warning('Midtrans notification: order not found', ['order_id' => $orderId]);
            return response()->json(['error' => 'Order not found'], 404);
        }

        $order->update([
            'transaction_id' => $payload['transaction_id'] ?? $order->transaction_id,
            'payment_type' => $payload['payment_type'] ?? $order->payment_type,
            'midtrans_response' => $payload,
        ]);

        $this->processOrderStatus($order, $transactionStatus, $fraudStatus);

        return response()->json(['ok' => true]);
    }

    /**
     * Payment finish callback (from Snap redirect)
     */
    public function finish(Request $request)
    {
        $orderId = $request->query('order_id');

        if (!$orderId) {
            $orderId = session('pending_order');
        }

        if (!$orderId) {
            return redirect()->route('home')->with('error', 'Order reference not found.');
        }

        $order = BuyerOrder::where('order_number', $orderId)->first();

        if (!$order) {
            return redirect()->route('home')->with('error', 'Order not found.');
        }

        // Check fresh status from Midtrans
        $status = $this->midtrans->checkTransactionStatus($orderId);
        if ($status) {
            $order->update([
                'transaction_id' => $status['transaction_id'] ?? $order->transaction_id,
                'payment_type' => $status['payment_type'] ?? $order->payment_type,
                'midtrans_response' => $status,
            ]);
            $this->processOrderStatus($order, $status['transaction_status'] ?? '', $status['fraud_status'] ?? '');
        }

        $order->load('product', 'access');

        return view('payment.finish', compact('order'));
    }

    /**
     * Payment pending callback
     */
    public function pending(Request $request)
    {
        $orderId = $request->query('order_id') ?? session('pending_order');
        return view('payment.pending', compact('orderId'));
    }

    /**
     * Payment error callback
     */
    public function error(Request $request)
    {
        $orderId = $request->query('order_id') ?? session('pending_order');
        return view('payment.error', compact('orderId'));
    }

    /**
     * Process/update order status based on Midtrans transaction status
     */
    protected function processOrderStatus(BuyerOrder $order, string $transactionStatus, string $fraudStatus): void
    {
        match ($transactionStatus) {
            'capture' => match ($fraudStatus) {
                'accept' => $this->verifyOrder($order),
                'challenge' => $order->update(['status' => 'pending', 'admin_note' => 'Payment challenged by Midtrans']),
                'deny' => $order->update(['status' => 'rejected', 'admin_note' => 'Payment denied by Midtrans']),
                default => null,
            },
            'settlement' => $this->verifyOrder($order),
            'pending' => $order->update(['status' => 'pending']),
            'deny' => $order->update(['status' => 'rejected', 'admin_note' => 'Payment denied by Midtrans']),
            'cancel' => $order->update(['status' => 'rejected', 'admin_note' => 'Payment cancelled by buyer']),
            'expire' => $order->update(['status' => 'rejected', 'admin_note' => 'Payment expired']),
            'refund' => $order->update(['status' => 'refunded']),
            'partial_refund' => $order->update(['status' => 'refunded', 'admin_note' => 'Partial refund processed']),
            default => null,
        };
    }

    /**
     * Auto-verify order and grant product access
     */
    protected function verifyOrder(BuyerOrder $order): void
    {
        $order->update([
            'status' => 'verified',
            'paid_at' => now(),
            'verified_at' => now(),
        ]);

        BuyerProductAccess::updateOrCreate(
            ['buyer_id' => $order->buyer_id, 'product_id' => $order->product_id],
            [
                'order_id' => $order->id,
                'granted_at' => now(),
                'is_active' => true,
            ]
        );
    }
}
