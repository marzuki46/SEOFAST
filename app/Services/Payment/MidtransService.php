<?php

namespace App\Services\Payment;

use App\Models\Invoice;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MidtransService
{
    protected string $serverKey;
    protected string $clientKey;
    protected bool $isProduction;
    protected string $snapUrl;

    public function __construct()
    {
        $this->serverKey = config('services.midtrans.server_key') ?? '';
        $this->clientKey = config('services.midtrans.client_key') ?? '';
        $this->isProduction = (bool) config('services.midtrans.is_production', false);
        
        $this->snapUrl = $this->isProduction
            ? 'https://app.midtrans.com/snap/v1/transactions'
            : 'https://app.sandbox.midtrans.com/snap/v1/transactions';
    }

    /**
     * Create Midtrans Snap Token for an invoice
     */
    public function createSnapToken(Invoice $invoice): ?string
    {
        try {
            $auth = base64_encode($this->serverKey . ':');
            $tenant = $invoice->tenant;

            $params = [
                'transaction_details' => [
                    'order_id' => $invoice->invoice_number,
                    'gross_amount' => (int) $invoice->total,
                ],
                'customer_details' => [
                    'first_name' => $tenant->name ?? 'User',
                    'email' => $tenant->users()->first()?->email ?? 'tenant@seofast.test',
                ],
                'credit_card' => [
                    'secure' => true,
                ],
            ];

            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . $auth,
            ])->post($this->snapUrl, $params);

            if ($response->failed()) {
                Log::error('Midtrans API Request failed: ' . $response->body());
                return null;
            }

            return $response->json('token');
        } catch (\Exception $e) {
            Log::error('Error creating Midtrans snap token: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Verify Callback Signature from Midtrans webhook
     */
    public function verifyCallbackSignature(array $payload): bool
    {
        // Signature = SHA512(order_id + status_code + gross_amount + ServerKey)
        $orderId = $payload['order_id'] ?? '';
        $statusCode = $payload['status_code'] ?? '';
        $grossAmount = $payload['gross_amount'] ?? '';
        $signatureKey = $payload['signature_key'] ?? '';

        $stringToHash = $orderId . $statusCode . $grossAmount . $this->serverKey;
        $hash = hash('sha512', $stringToHash);

        return hash_equals($hash, $signatureKey);
    }
}
