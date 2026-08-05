<?php

namespace App\Services;

use App\Models\SystemSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteService
{
    protected string $apiUrl = 'https://api.fonnte.com/send';

    protected ?string $token;

    public function __construct(?string $token = null)
    {
        $this->token = $token ?: (string) SystemSetting::get('whatsapp_fonnte_token', '');
    }

    public function isConfigured(): bool
    {
        return $this->token !== '';
    }

    /**
     * Send a WhatsApp text message via Fonnte.
     *
     * @return array{success: bool, message: string, detail?: mixed}
     */
    public function send(string $target, string $message): array
    {
        if (!$this->isConfigured()) {
            return [
                'success' => false,
                'message' => 'Token Fonnte belum dikonfigurasi. Isi di Settings → WhatsApp & Notifikasi.',
            ];
        }

        $target = $this->normalizeNumber($target);

        if ($target === '') {
            return [
                'success' => false,
                'message' => 'Nomor WhatsApp tujuan tidak valid.',
            ];
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $this->token,
            ])->asForm()->timeout(20)->post($this->apiUrl, [
                'target' => $target,
                'message' => $message,
            ]);

            $body = $response->json();

            $ok = $response->ok() && (($body['status'] ?? false) === true);

            if (!$ok) {
                $detail = $body['detail'] ?? $body['reason'] ?? $response->body();
                Log::warning('Fonnte send failed', ['target' => $target, 'detail' => $detail]);

                return [
                    'success' => false,
                    'message' => 'Fonnte menolak pengiriman: ' . (is_string($detail) ? $detail : json_encode($detail)),
                    'detail' => $detail,
                ];
            }

            return [
                'success' => true,
                'message' => 'Pesan berhasil dikirim ke ' . $target . '.',
                'detail' => $body['detail'] ?? null,
            ];
        } catch (\Throwable $e) {
            Log::error('Fonnte exception', ['error' => $e->getMessage()]);

            return [
                'success' => false,
                'message' => 'Gagal menghubungi Fonnte: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Normalize a phone number to international format (62xxxx).
     * Accepts: 08xx, +62xx, 62xx, with spaces/dashes removed.
     */
    public function normalizeNumber(string $number): string
    {
        $num = preg_replace('/[^0-9]/', '', $number);

        if ($num === '') {
            return '';
        }

        if (str_starts_with($num, '0')) {
            $num = '62' . substr($num, 1);
        } elseif (str_starts_with($num, '62')) {
            // already correct
        } else {
            $num = '62' . $num;
        }

        return $num;
    }
}
