<?php

namespace App\Services;

use App\Models\CompetitorAnalysis;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CompetitorAnalysisService
{
    protected string $baseUrl;
    protected ?string $apiKey;
    protected string $llmModel;

    public function __construct()
    {
        $this->baseUrl = rtrim(SystemSetting::get('9router_api_base', 'https://api.9router.com'), '/');
        $this->apiKey = SystemSetting::get('9router_api_key');
        $this->llmModel = SystemSetting::get('competitor_llm_model', 'kr/deepseek-3.2');
    }

    public function analyze(CompetitorAnalysis $analysis): void
    {
        set_time_limit(120);
        $analysis->update(['status' => 'processing']);

        try {
            $result = $this->analyzeKeyword($analysis->keyword);

            $analysis->update([
                'status' => 'completed',
                'results' => $result,
            ]);
        } catch (\Exception $e) {
            Log::error('CompetitorAnalysis failed: ' . $e->getMessage());
            $analysis->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);
        }
    }

    protected function analyzeKeyword(string $keyword): array
    {
        $prompt = <<<PROMPT
Kamu adalah SEO content analyst berpengalaman. Analisis lanskap konten competitor untuk keyword "$keyword" berdasarkan pengetahuan yang kamu miliki.

Berikan analisis dalam format JSON berikut (hanya JSON, tanpa teks lain):

{
  "common_topics": [
    {"topic": "nama topik yang umum dibahas competitor", "mentioned_by": 8, "description": "deskripsi bagaimana topik ini dibahas"}
  ],
  "gap_topics": [
    {"topic": "topik yang jarang dibahas competitor", "mentioned_by": 2, "description": "deskripsi topik ini dan mengapa ini peluang"}
  ],
  "key_findings": [
    "temuan penting 1 tentang strategi konten competitor",
    "temuan penting 2"
  ],
  "content_recommendations": [
    {"title": "judul konten yang disarankan", "rationale": "alasan mengapa konten ini akan efektif"}
  ],
  "competitor_insights": [
    {"rank": 1, "title": "nama competitor/situs", "main_points": ["poin utama 1", "poin utama 2"]},
    {"rank": 2, "title": "nama competitor/situs", "main_points": ["poin utama 1"]}
  ]
}

Panduan:
- common_topics: identifikasi topik-topik yang hampir semua competitor bahas. Beri perkiraan jumlah competitor yang membahas topik tersebut.
- gap_topics: cari topik yang hanya dibahas oleh sedikit competitor (1-3). Ini adalah opportunity untuk membuat konten yang unik.
- key_findings: beri 3-5 temuan penting tentang strategi konten competitor secara umum.
- content_recommendations: beri 3-5 rekomendasi judul konten yang bisa dikembangkan, lengkap dengan alasan.
- competitor_insights: analisis 5-8 competitor/situs utama yang muncul untuk keyword ini. Untuk setiap competitor, sebutkan poin-poin utama yang mereka bahas di halaman mereka.
PROMPT;

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->timeout(120)->connectTimeout(30)->post($this->baseUrl . '/v1/chat/completions', [
            'model' => $this->llmModel,
            'messages' => [['role' => 'user', 'content' => $prompt]],
            'max_tokens' => 8192,
            'temperature' => 0.3,
        ]);

        if (!$response->successful()) {
            throw new \Exception('LLM API error: ' . $response->body());
        }

        $text = $response->json()['choices'][0]['message']['content'] ?? '';

        $json = $this->extractJson($text);
        if (!$json) {
            Log::debug('CompetitorAnalysis LLM raw response (no JSON): ' . mb_substr($text, 0, 2000));
            return [
                'common_topics' => [],
                'gap_topics' => [],
                'key_findings' => [$text],
                'content_recommendations' => [],
                'competitor_insights' => [],
            ];
        }

        return json_decode($json, true) ?? [];
    }

    protected function extractJson(string $text): ?string
    {
        if (preg_match('/```(?:json)?\s*(\{.*?\})\s*```/s', $text, $m)) {
            return $m[1];
        }
        $start = strpos($text, '{');
        if ($start === false) return null;
        $depth = 0;
        for ($i = $start; $i < strlen($text); $i++) {
            if ($text[$i] === '{') $depth++;
            elseif ($text[$i] === '}') $depth--;
            if ($depth === 0) {
                $candidate = substr($text, $start, $i - $start + 1);
                json_decode($candidate);
                if (json_last_error() === JSON_ERROR_NONE) {
                    return $candidate;
                }
                return null;
            }
        }
        return null;
    }
}
