<?php
namespace App\Services;

use RuntimeException;

class SimpleNineRouterService
{
    public function generateArticle(string $keyword, string $title, string $brief, string $whatsappNumber): array
    {
        $settings = new NineRouterSettings();
        $url = rtrim((string) ($settings->get('url', '') ?: env('ninerouter.url') ?: 'https://api.9router.com'), '/');
        $apiKeyStored = (string) $settings->get('apiKey', '');
        $apiKey = trim((new SecretCipher())->decrypt($apiKeyStored, (string) (env('ninerouter.api_key') ?: '')));
        $model = trim((string) ($settings->get('model', '') ?: env('ninerouter.model') ?: 'content'));

        if (!$url || !$apiKey || !$model) {
            throw new RuntimeException('9Router not configured properly.');
        }

        $system = "Anda adalah Master SEO Content Strategist dan Developer Senior Indonesia. Tulis artikel SEO berkualitas tinggi dalam Bahasa Indonesia format HTML (gunakan tag <h2>, <h3>, <p>, <strong>, <em>, <ul>, <li>). Panjang artikel minimal 1000 kata. Di akhir artikel, berikan Call to Action (CTA) ke WhatsApp nomor {$whatsappNumber} secara profesional.";
        
        $user = "Buat artikel SEO yang komprehensif, mendalam, dan natural berdasarkan:\nTarget Keyword: {$keyword}\nJudul: {$title}\nBrief/Panduan: {$brief}\n\nBerikan respons DALAM FORMAT JSON BERIKUT (tanpa markdown backtick tambahan):\n{\n  \"meta_title\": \"Judul SEO max 70 karakter\",\n  \"meta_description\": \"Deskripsi SEO max 160 karakter\",\n  \"content\": \"<p>Isi artikel lengkap format HTML...</p>\"\n}";

        $endpoint = str_ends_with($url, '/v1/chat/completions') ? $url : $url . '/v1/chat/completions';
        $ch = curl_init($endpoint);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode([
                'model' => $model,
                'temperature' => 0.5,
                'response_format' => ['type' => 'json_object'],
                'messages' => [
                    ['role' => 'system', 'content' => $system],
                    ['role' => 'user', 'content' => $user],
                ],
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR),
            CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $apiKey, 'Content-Type: application/json', 'Accept: application/json'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 15,
            CURLOPT_TIMEOUT => 120,
            CURLOPT_SSL_VERIFYPEER => true,
        ]);

        $raw = curl_exec($ch);
        $error = curl_error($ch);
        $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($raw === false || $error !== '' || $status < 200 || $status >= 300) {
            throw new RuntimeException("9Router API request failed (HTTP {$status}): {$error}");
        }

        $response = json_decode((string) $raw, true);
        $text = trim((string) ($response['choices'][0]['message']['content'] ?? ''));
        if ($text === '') {
            throw new RuntimeException('9Router returned empty response.');
        }

        $cleanText = preg_replace('/^```(?:json)?\s*|\s*```$/i', '', $text);
        $data = json_decode(trim($cleanText), true);

        if (!is_array($data) || empty($data['content'])) {
            // Fallback jika tidak JSON valid
            return [
                'meta_title' => $title,
                'meta_description' => $brief,
                'content' => '<p>' . nl2br(htmlspecialchars($text)) . '</p>'
            ];
        }

        return $data;
    }
}
