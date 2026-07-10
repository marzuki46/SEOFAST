<?php

namespace App\Services\Payment;

use App\Models\BuyerOrder;
use App\Models\Invoice;
use App\Models\SystemSetting;
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
        $dbServerKey = SystemSetting::get('midtrans_server_key');
        $dbClientKey = SystemSetting::get('midtrans_client_key');
        $dbIsProduction = SystemSetting::get('midtrans_is_production');

        $this->serverKey = $dbServerKey ?: (config('services.midtrans.server_key') ?? '');
        $this->clientKey = $dbClientKey ?: (config('services.midtrans.client_key') ?? '');
        $this->isProduction = $dbIsProduction !== null
            ? filter_var($dbIsProduction, FILTER_VALIDATE_BOOLEAN)
            : (bool) config('services.midtrans.is_production', false);

        $this->snapUrl = $this->isProduction
            ? 'https://app.midtrans.com/snap/v1/transactions'
            : 'https://app.sandbox.midtrans.com/snap/v1/transactions';
    }

    public function getClientKey(): string
    {
        return $this->clientKey;
    }

    public function isProduction(): bool
    {
        return $this->isProduction;
    }

    /**
     * Create Midtrans Snap Token for a BuyerOrder
     */
    public function createBuyerSnapToken(BuyerOrder $order): ?string
    {
        try {
            $auth = base64_encode($this->serverKey . ':');
            $buyer = $order->buyer;

            $params = [
                'transaction_details' => [
                    'order_id' => $order->order_number,
                    'gross_amount' => (int) ($order->amount + $order->unique_amount),
                ],
                'customer_details' => [
                    'first_name' => $buyer->name ?? 'Buyer',
                    'email' => $buyer->email ?? 'buyer@seofast.test',
                ],
                'credit_card' => [
                    'secure' => true,
                ],
                'callbacks' => [
                    'finish' => route('payment.finish'),
                    'error' => route('payment.error'),
                    'pending' => route('payment.pending'),
                ],
            ];

            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . $auth,
            ])->post($this->snapUrl, $params);

            if ($response->failed()) {
                Log::error('Midtrans Snap Request failed', [
                    'order' => $order->order_number,
                    'response' => $response->body(),
                ]);
                return null;
            }

            $token = $response->json('token');
            $order->update(['snap_token' => $token]);
            return $token;
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
        $orderId = $payload['order_id'] ?? '';
        $statusCode = $payload['status_code'] ?? '';
        $grossAmount = $payload['gross_amount'] ?? '';
        $signatureKey = $payload['signature_key'] ?? '';

        $stringToHash = $orderId . $statusCode . $grossAmount . $this->serverKey;
        $hash = hash('sha512', $stringToHash);

        return hash_equals($hash, $signatureKey);
    }

    /**
     * Check transaction status from Midtrans API
     */
    public function checkTransactionStatus(string $orderId): ?array
    {
        try {
            $auth = base64_encode($this->serverKey . ':');
            $url = $this->isProduction
                ? "https://api.midtrans.com/v2/{$orderId}/status"
                : "https://api.sandbox.midtrans.com/v2/{$orderId}/status";

            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Basic ' . $auth,
            ])->get($url);

            if ($response->failed()) {
                Log::error('Midtrans status check failed', ['order' => $orderId, 'response' => $response->body()]);
                return null;
            }

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Error checking Midtrans status: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Create Midtrans Snap Token for an invoice (legacy)
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
}
