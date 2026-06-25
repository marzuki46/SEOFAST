<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Product;
use App\Services\Payment\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BillingController extends Controller
{
    /**
     * Show all invoices/orders.
     */
    public function index(Request $request)
    {
        $invoices = Invoice::with(['user', 'product'])->latest()->get();
        $products = Product::where('is_active', true)->get();

        return view('admin.billing.index', compact('invoices', 'products'));
    }

    /**
     * Create a new manual order.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $product    = Product::findOrFail($request->product_id);
        $uniqueCode = rand(100, 999);
        $total      = $product->price + $uniqueCode;

        Invoice::create([
            'user_id'        => auth()->id(),
            'product_id'     => $product->id,
            'invoice_number' => 'INV-' . now()->format('Ymd') . '-' . rand(1000, 9999),
            'status'         => 'pending',
            'invoice_date'   => now(),
            'due_date'       => now()->addDays(3),
            'payment_method' => 'Manual Bank Transfer',
            'subtotal'       => $product->price,
            'tax'            => 0,
            'total'          => $total,
            'notes'          => 'Order: ' . $product->name . ' (Kode Unik: ' . $uniqueCode . ')',
        ]);

        return redirect()->back()->with('success', 'Order dibuat. Transfer tepat Rp ' . number_format($total, 0, ',', '.'));
    }

    /**
     * Verify / mark payment as paid.
     */
    public function verify(Invoice $invoice)
    {
        $invoice->update([
            'status'  => 'paid',
            'paid_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Pembayaran berhasil diverifikasi.');
    }
}
