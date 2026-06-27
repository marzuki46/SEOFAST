<?php

namespace App\Services;

use App\Models\AiLog;
use App\Models\Tenant;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIService
{
    private ?Tenant $tenant;
    private array $config;
    private string $role;

    public function __construct(?Tenant $tenant, string $role = 'default')
    {
        $this->tenant = $tenant;
        $this->role = $role;
        $this->config = $this->loadConfig();
    }

    /**
     * Get a setting from Tenant (if exists) or fall back to SystemSetting.
     * This allows the CMS to operate in single-ownership mode without a Tenant record.
     */
    private function getSetting(string $key, mixed $default = null): mixed
    {
        // Fallback to global SystemSetting first (prioritize global admin settings)
        $value = \App\Models\SystemSetting::get($key);
        if ($value !== null) {
            return $value;
        }

        // If a tenant exists and has the setting, use it
        if ($this->tenant) {
            $value = $this->tenant->getSetting($key);
            if ($value !== null) {
                return $value;
            }
        }

        return $default;
    }

    /**
     * Load AI configuration from tenant settings or global system settings.
     */
    private function loadConfig(): array
    {
        $settingKey = $this->role === 'default' ? 'ai_provider' : 'ai_provider_' . $this->role;
        $provider = $this->getSetting($settingKey, $this->getSetting('ai_provider', 'openai'));

        // Resolve settings per provider
        $apiKey = match ($provider) {
            'openai'   => $this->getSetting('openai_api_key'),
            'gemini'   => $this->getSetting('gemini_api_key'),
            'claude'   => $this->getSetting('claude_api_key'),
            '9router'  => $this->getSetting('9router_api_key'),
            'deepseek' => $this->getSetting('deepseek_api_key'),
            'custom'   => $this->getSetting('custom_api_key'),
            default    => $this->getSetting('ai_api_key'),
        } ?: config('ai.openai_api_key');

        $model = match ($provider) {
            'openai'   => $this->getSetting('openai_model'),
            'gemini'   => $this->getSetting('gemini_model'),
            'claude'   => $this->getSetting('claude_model'),
            '9router'  => $this->getSetting('9router_model'),
            'deepseek' => $this->getSetting('deepseek_model'),
            'custom'   => $this->getSetting('custom_model'),
            default    => $this->getSetting('ai_model'),
        } ?: match ($provider) {
            'openai'   => 'gpt-4o',
            'gemini'   => 'gemini-1.5-pro',
            'claude'   => 'claude-3-5-sonnet',
            '9router'  => 'meta-llama/llama-3-8b-instruct',
            'deepseek' => 'deepseek-chat',
            'custom'   => 'custom-model',
            default    => config('ai.default_model', 'gpt-4o'),
        };

        $apiBase = match ($provider) {
            '9router' => $this->getSetting('9router_api_base', 'https://api.9router.com/v1'),
            'custom'  => $this->getSetting('custom_api_base', 'http://localhost:20128/v1'),
            default   => null,
        };

        return [
            'provider'    => $provider,
            'model'       => $model,
            'apiKey'      => $apiKey,
            'apiBase'     => $apiBase,
            'temperature' => 0.7,
            'max_tokens'  => 4096,
        ];
    }

    /**
     * Send a prompt to the configured AI provider.
     */
    public function generate(string $systemPrompt, string $userPrompt, array $options = []): ?string
    {
        $startTime = microtime(true);

        try {
            $response = match ($this->config['provider']) {
                'openai' => $this->callOpenAI($systemPrompt, $userPrompt, $options),
                'gemini' => $this->callGemini($systemPrompt, $userPrompt, $options),
                'claude' => $this->callClaude($systemPrompt, $userPrompt, $options),
                'deepseek' => $this->callDeepSeek($systemPrompt, $userPrompt, $options),
                '9router' => $this->call9Router($systemPrompt, $userPrompt, $options),
                'custom' => $this->callCustom($systemPrompt, $userPrompt, $options),
                default => throw new \InvalidArgumentException("Unknown provider: {$this->config['provider']}"),
            };

            $this->logUsage($systemPrompt, $userPrompt, $response, $startTime);

            return $response['content'] ?? null;

        } catch (\Exception $e) {
            Log::error("AI generation failed: {$e->getMessage()}", [
                'tenant_id' => $this->tenant?->id ?? (\App\Models\Tenant::first()?->id ?? 1),
                'provider' => $this->config['provider'],
                'model' => $this->config['model'],
            ]);

            if (function_exists('session') && request()->hasSession()) {
                session()->flash('ai_error', $e->getMessage());
            }

            $this->logUsage($systemPrompt, $userPrompt, ['error' => $e->getMessage()], $startTime, 'failed');

            return null;
        }
    }

    /**
     * Generate structured JSON output from AI.
     */
    public function generateJson(string $systemPrompt, string $userPrompt, array $options = []): ?array
    {
        $responseFormat = $options['response_format'] ?? 'json_object';
        $options['response_format'] = $responseFormat;

        $result = $this->generate($systemPrompt, $userPrompt, $options);

        if (!$result) {
            return null;
        }

        // Try to extract JSON from markdown code blocks if present
        if (preg_match('/```(?:json)?\s*([\s\S]*?)```/', $result, $matches)) {
            $result = $matches[1];
        }

        $decoded = json_decode($result, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::warning("AI JSON parsing failed: " . json_last_error_msg(), [
                'raw_response' => substr($result, 0, 500),
            ]);
            if (function_exists('session') && request()->hasSession()) {
                session()->flash('ai_error', 'JSON parsing failed: ' . json_last_error_msg() . ' | Raw response: ' . substr($result, 0, 200));
            }
            return null;
        }

        return $decoded;
    }

    /**
     * Call OpenAI API.
     */
    private function callOpenAI(string $systemPrompt, string $userPrompt, array $options): array
    {
        $response = Http::withToken($this->config['apiKey'])
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => $options['model'] ?? $this->config['model'],
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $userPrompt],
                ],
                'temperature' => $options['temperature'] ?? $this->config['temperature'],
                'max_tokens' => $options['max_tokens'] ?? $this->config['max_tokens'],
                'response_format' => $options['response_format'] ?? null,
            ]);

        $body = $response->json();

        if (!$response->successful()) {
            throw new \RuntimeException($body['error']['message'] ?? 'OpenAI API error');
        }

        return [
            'content' => $body['choices'][0]['message']['content'],
            'usage' => $body['usage'] ?? [],
            'model' => $body['model'],
        ];
    }

    /**
     * Call Google Gemini API.
     */
    private function callGemini(string $systemPrompt, string $userPrompt, array $options): array
    {
        $apiKey = $this->config['apiKey'];
        $model = $options['model'] ?? $this->config['model'];

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}", [
            'contents' => [
                ['parts' => [['text' => $systemPrompt . "\n\n" . $userPrompt]]],
            ],
            'generationConfig' => [
                'temperature' => $options['temperature'] ?? $this->config['temperature'],
                'maxOutputTokens' => $options['max_tokens'] ?? $this->config['max_tokens'],
            ],
        ]);

        $body = $response->json();

        if (!$response->successful()) {
            throw new \RuntimeException($body['error']['message'] ?? 'Gemini API error');
        }

        return [
            'content' => $body['candidates'][0]['content']['parts'][0]['text'] ?? '',
            'usage' => [],
            'model' => $model,
        ];
    }

    /**
     * Call Anthropic Claude API.
     */
    private function callClaude(string $systemPrompt, string $userPrompt, array $options): array
    {
        $response = Http::withHeaders([
            'x-api-key' => $this->config['apiKey'],
            'anthropic-version' => '2023-06-01',
            'Content-Type' => 'application/json',
        ])->post('https://api.anthropic.com/v1/messages', [
            'model' => $options['model'] ?? $this->config['model'],
            'max_tokens' => $options['max_tokens'] ?? $this->config['max_tokens'],
            'system' => $systemPrompt,
            'messages' => [
                ['role' => 'user', 'content' => $userPrompt],
            ],
        ]);

        $body = $response->json();

        if (!$response->successful()) {
            throw new \RuntimeException($body['error']['message'] ?? 'Claude API error');
        }

        return [
            'content' => $body['content'][0]['text'] ?? '',
            'usage' => $body['usage'] ?? [],
            'model' => $body['model'],
        ];
    }

    /**
     * Call DeepSeek API.
     */
    private function callDeepSeek(string $systemPrompt, string $userPrompt, array $options): array
    {
        $response = Http::withToken($this->config['apiKey'])
            ->post('https://api.deepseek.com/v1/chat/completions', [
                'model' => $options['model'] ?? $this->config['model'],
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $userPrompt],
                ],
                'temperature' => $options['temperature'] ?? $this->config['temperature'],
                'max_tokens' => $options['max_tokens'] ?? $this->config['max_tokens'],
            ]);

        $body = $response->json();

        if (!$response->successful()) {
            throw new \RuntimeException($body['error']['message'] ?? 'DeepSeek API error');
        }

        return [
            'content' => $body['choices'][0]['message']['content'],
            'usage' => $body['usage'] ?? [],
            'model' => $body['model'],
        ];
    }

    /**
     * Call 9Router API (OpenAI Compatible).
     */
    private function call9Router(string $systemPrompt, string $userPrompt, array $options): array
    {
        $apiBase = $this->config['apiBase'] ?: 'https://api.9router.com/v1'; // fallback 9router endpoint if needed
        $url = rtrim($apiBase, '/') . '/chat/completions';

        $response = Http::withToken($this->config['apiKey'])
            ->withHeaders([
                'HTTP-Referer' => config('app.url', 'https://seofast.test'),
                'X-Title' => 'SEOFAST Optimizer',
            ])
            ->post($url, [
                'model' => $options['model'] ?? $this->config['model'],
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $userPrompt],
                ],
                'temperature' => $options['temperature'] ?? $this->config['temperature'],
                'max_tokens' => $options['max_tokens'] ?? $this->config['max_tokens'],
            ]);

        $body = $response->json();

        if (!$response->successful()) {
            throw new \RuntimeException($body['error']['message'] ?? '9Router API error');
        }

        return [
            'content' => $body['choices'][0]['message']['content'],
            'usage' => $body['usage'] ?? [],
            'model' => $body['model'] ?? ($options['model'] ?? $this->config['model']),
        ];
    }

    /**
     * Call Custom API (OpenAI Compatible).
     */
    private function callCustom(string $systemPrompt, string $userPrompt, array $options): array
    {
        $apiBase = $this->config['apiBase'] ?: 'http://localhost:20128/v1';
        $url = rtrim($apiBase, '/') . '/chat/completions';

        $request = Http::asJson()->withHeaders([
            'Bypass-Tunnel-Reminder' => 'true',
        ]);
        if (!empty($this->config['apiKey'])) {
            $request = $request->withToken($this->config['apiKey']);
        }

        $response = $request->post($url, [
            'model' => $options['model'] ?? $this->config['model'],
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $userPrompt],
            ],
            'temperature' => $options['temperature'] ?? $this->config['temperature'],
            'max_tokens' => $options['max_tokens'] ?? $this->config['max_tokens'],
        ]);

        $body = $response->json();

        if (!$response->successful()) {
            throw new \RuntimeException($body['error']['message'] ?? 'Custom API error');
        }

        return [
            'content' => $body['choices'][0]['message']['content'] ?? '',
            'usage' => $body['usage'] ?? [],
            'model' => $body['model'] ?? ($options['model'] ?? $this->config['model']),
        ];
    }

    /**
     * Log AI usage for billing/monitoring.
     */
    private function logUsage(
        string $systemPrompt,
        string $userPrompt,
        array $response,
        float $startTime,
        string $status = 'success'
    ): void {
        $duration = microtime(true) - $startTime;

        AiLog::create([
            'tenant_id'          => $this->tenant?->id ?? (\App\Models\Tenant::first()?->id ?? 1),
            'provider'           => $this->config['provider'],
            'model'              => $response['model'] ?? $this->config['model'],
            'prompt_tokens'      => $response['usage']['prompt_tokens'] ?? 0,
            'completion_tokens'  => $response['usage']['completion_tokens'] ?? 0,
            'total_tokens'       => ($response['usage']['prompt_tokens'] ?? 0) + ($response['usage']['completion_tokens'] ?? 0),
            'cost_micros'        => $this->calculateCost(
                $response['usage']['prompt_tokens'] ?? 0,
                $response['usage']['completion_tokens'] ?? 0,
                $this->config['model']
            ),
            'status'             => $status,
            'error_message'      => $response['error'] ?? null,
            'started_at'         => now()->subSeconds($duration),
            'completed_at'       => now(),
        ]);
    }

    /**
     * Calculate approximate cost in micro dollars.
     */
    private function calculateCost(int $promptTokens, int $completionTokens, string $model): int
    {
        $rates = [
            'gpt-4o' => [2.50, 10.00],        // $ per 1M tokens: prompt, completion
            'gpt-4o-mini' => [0.15, 0.60],
            'claude-3-5-sonnet' => [3.00, 15.00],
            'gemini-1.5-pro' => [1.25, 5.00],
            'deepseek-chat' => [0.27, 1.10],
        ];

        $rate = $rates[$model] ?? [2.50, 10.00];

        return (int)(
            ($promptTokens / 1_000_000 * $rate[0] * 1_000_000) +
            ($completionTokens / 1_000_000 * $rate[1] * 1_000_000)
        );
    }
}