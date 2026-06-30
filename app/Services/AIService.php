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

    /**
     * Stores diagnostic information from the last generate() call.
     * Each entry: ['provider', 'model', 'status', 'http_status', 'elapsed_ms',
     *              'response_format', 'content_length', 'raw_snippet', 'error']
     */
    private array $lastDiagnostics = [];

    public function __construct(?Tenant $tenant, string $role = 'default')
    {
        $this->tenant = $tenant;
        $this->role = $role;
        $this->config = $this->loadConfig();
    }

    /** Return diagnostics from the last generate() call. */
    public function getLastDiagnostics(): array
    {
        return $this->lastDiagnostics;
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
        $this->lastDiagnostics = []; // reset for this call

        // Log debug info
        $debugData = [
            'time' => date('Y-m-d H:i:s'),
            'role' => $this->role,
            'config' => $this->config,
            'systemPrompt' => substr($systemPrompt, 0, 100),
            'userPrompt' => substr($userPrompt, 0, 100),
            'backtrace' => collect(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 5))->map(function($t) {
                return ($t['class'] ?? '') . '::' . ($t['function'] ?? '') . ' (line ' . ($t['line'] ?? '') . ')';
            })->toArray()
        ];
        @file_put_contents(
            storage_path('logs/ai_debug.log'),
            json_encode($debugData, JSON_PRETTY_PRINT) . "\n\n",
            FILE_APPEND
        );

        $primaryProvider = $this->config['provider'];
        $providersToTry = [];
        
        // Cek jika custom model dipisah koma (fallback models in custom)
        if ($primaryProvider === 'custom') {
            $customModels = array_filter(array_map('trim', explode(',', $this->config['model'] ?? '')));
            if (count($customModels) > 1) {
                foreach ($customModels as $m) {
                    $providersToTry[] = "custom:{$m}";
                }
            } else {
                $providersToTry[] = 'custom';
            }
        } else {
            $providersToTry[] = $primaryProvider;
        }
        
        // Setup smart fallbacks in order of preference
        $fallbacks = ['gemini', 'openai', 'claude', 'deepseek', '9router', 'custom'];
        foreach ($fallbacks as $fb) {
            if ($fb !== $primaryProvider && !in_array($fb, $providersToTry)) {
                $providersToTry[] = $fb;
            }
        }

        $lastError = null;

        foreach ($providersToTry as $currentProviderRaw) {
            $parts = explode(':', $currentProviderRaw, 2);
            $currentProvider = $parts[0];
            $specificModel = $parts[1] ?? null;

            $attemptStart = microtime(true);
            $diag = [
                'provider'        => $currentProvider,
                'model'           => $specificModel ?: $this->config['model'],
                'status'          => 'pending',
                'http_status'     => null,
                'elapsed_ms'      => null,
                'response_format' => 'unknown',
                'content_length'  => null,
                'raw_snippet'     => null,
                'error'           => null,
            ];

            try {
                // If falling back or using specific model, reconfigure the provider and API key
                if ($currentProvider !== $primaryProvider || $specificModel) {
                    $this->config['provider'] = $currentProvider;
                    $this->config['model'] = $specificModel ?: match ($currentProvider) {
                        'openai'   => $this->getSetting('openai_model', 'gpt-4o'),
                        'gemini'   => $this->getSetting('gemini_model', 'gemini-1.5-pro'),
                        'claude'   => $this->getSetting('claude_model', 'claude-3-5-sonnet'),
                        '9router'  => $this->getSetting('9router_model', 'meta-llama/llama-3-8b-instruct'),
                        'deepseek' => $this->getSetting('deepseek_model', 'deepseek-chat'),
                        'custom'   => $this->getSetting('custom_model', 'custom-model'),
                        default    => 'gpt-4o',
                    };
                    // Handle specific model fallback inside custom provider itself (if any)
                    if ($currentProvider === 'custom' && strpos($this->config['model'], ',') !== false) {
                        $this->config['model'] = trim(explode(',', $this->config['model'])[0]);
                    }

                    $this->config['apiKey'] = match ($currentProvider) {
                        'openai'   => $this->getSetting('openai_api_key'),
                        'gemini'   => $this->getSetting('gemini_api_key'),
                        'claude'   => $this->getSetting('claude_api_key'),
                        '9router'  => $this->getSetting('9router_api_key'),
                        'deepseek' => $this->getSetting('deepseek_api_key'),
                        'custom'   => $this->getSetting('custom_api_key'),
                        default    => null,
                    } ?: config('ai.openai_api_key');
                    
                    $diag['model'] = $this->config['model'];

                    // Skip provider if API key is clearly missing
                    if (empty($this->config['apiKey']) && !in_array($currentProvider, ['custom', '9router'])) {
                        $diag['status'] = 'skipped';
                        $diag['error']  = 'API key missing';
                        $this->lastDiagnostics[] = $diag;
                        continue;
                    }
                }

                $response = match ($this->config['provider']) {
                    'openai'   => $this->callOpenAI($systemPrompt, $userPrompt, $options),
                    'gemini'   => $this->callGemini($systemPrompt, $userPrompt, $options),
                    'claude'   => $this->callClaude($systemPrompt, $userPrompt, $options),
                    'deepseek' => $this->callDeepSeek($systemPrompt, $userPrompt, $options),
                    '9router'  => $this->call9Router($systemPrompt, $userPrompt, $options),
                    'custom'   => $this->callCustom($systemPrompt, $userPrompt, $options),
                    default    => throw new \InvalidArgumentException("Unknown provider: {$this->config['provider']}"),
                };

                $elapsed = round((microtime(true) - $attemptStart) * 1000);
                $content = $response['content'] ?? '';

                // Strip <think> blocks globally for all providers
                $content = preg_replace('/<think>[\s\S]*?(?:<\/think>|$)\s*/i', '', $content);

                $diag['status']         = 'success';
                $diag['elapsed_ms']     = $elapsed;
                $diag['content_length'] = mb_strlen($content);
                $diag['http_status']    = $response['_http_status'] ?? 200;
                $diag['response_format']= $response['_format'] ?? 'json';
                $this->lastDiagnostics[] = $diag;

                $this->logUsage($systemPrompt, $userPrompt, $response, $startTime);

                return $content ?: null;

            } catch (\Exception $e) {
                $elapsed  = round((microtime(true) - $attemptStart) * 1000);
                $lastError = $e->getMessage();

                $diag['status']     = 'failed';
                $diag['elapsed_ms'] = $elapsed;
                $diag['error']      = $lastError;
                // Capture raw snippet if stored in exception data
                if ($e instanceof \RuntimeException && str_contains($lastError, 'RAW:')) {
                    [$msg, $raw] = explode('RAW:', $lastError, 2);
                    $diag['error']       = trim($msg);
                    $diag['raw_snippet'] = trim(substr($raw, 0, 300));
                }
                $this->lastDiagnostics[] = $diag;

                Log::warning("AI generation failed with {$currentProvider}, trying next fallback...", [
                    'tenant_id' => $this->tenant?->id ?? (\App\Models\Tenant::first()?->id ?? 1),
                    'provider'  => $currentProvider,
                    'error'     => $lastError,
                ]);
            }
        }

        // All providers failed
        Log::error("ALL AI providers failed. Last error: {$lastError}");
        if (function_exists('session') && request()->hasSession()) {
            session()->flash('ai_error', "All AI providers failed. Last error: {$lastError}");
        }

        $this->logUsage($systemPrompt, $userPrompt, ['error' => $lastError], $startTime, 'failed');

        return null;
    }

    /**
     * Test connectivity to the configured AI provider.
     * Sends a minimal prompt to verify the API key and endpoint are working.
     *
     * @return array{ok: bool, provider: string, model: string, error: string|null}
     */
    public function testConnection(): array
    {
        $provider = $this->config['provider'];
        $model    = $this->config['model'];

        if (empty($this->config['apiKey']) && !in_array($provider, ['custom', '9router'])) {
            return [
                'ok'       => false,
                'provider' => $provider,
                'model'    => $model,
                'error'    => 'API Key tidak ditemukan untuk provider: ' . $provider,
            ];
        }

        try {
            $result = $this->generate(
                'You are a connectivity test assistant. Reply concisely.',
                'Say exactly: OK',
                ['max_tokens' => 500, 'temperature' => 0]
            );

            // Always collect diagnostics regardless of success/fail
            $diagnostics = $this->getLastDiagnostics();

            if ($result !== null && trim($result) !== '') {
                return [
                    'ok'          => true,
                    'provider'    => $this->config['provider'],
                    'model'       => $this->config['model'],
                    'error'       => null,
                    'diagnostics' => $diagnostics,
                ];
            }

            // Build a helpful error from diagnostics
            $diagErrors = collect($diagnostics)
                ->where('status', 'failed')
                ->map(fn($d) => "[{$d['provider']}] {$d['error']}")
                ->implode(' | ');

            return [
                'ok'          => false,
                'provider'    => $provider,
                'model'       => $model,
                'error'       => $diagErrors ?: 'AI merespons null — tidak ada konten dikembalikan.',
                'diagnostics' => $diagnostics,
            ];
        } catch (\Exception $e) {
            return [
                'ok'          => false,
                'provider'    => $provider,
                'model'       => $model,
                'error'       => $e->getMessage(),
                'diagnostics' => $this->getLastDiagnostics(),
            ];
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
        } else {
            // Fallback: Try to find the first '[' and last ']' if it looks like a JSON array
            if (preg_match('/\[\s*([\s\S]*)\s*\]/', $result, $matches)) {
                $result = $matches[0];
            }
        }
        $decoded = json_decode($result, true);

        // Debug log the raw response and parsed result
        @file_put_contents(storage_path('logs/ai_debug.log'), json_encode([
            'time' => date('Y-m-d H:i:s'),
            'type' => 'response',
            'raw_result' => $result,
            'decoded' => $decoded
        ], JSON_PRETTY_PRINT) . "\n\n", FILE_APPEND);

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
     * Generate embeddings for a given text.
     */
    public function generateEmbeddings(string $text, string $model = 'text-embedding-3-small'): ?array
    {
        $apiBase = $this->config['apiBase'];
        if (!$apiBase) {
            $apiBase = match ($this->config['provider']) {
                'openai' => 'https://api.openai.com/v1',
                default  => 'https://api.9router.com/v1',
            };
        }
        
        $url = rtrim($apiBase, '/') . '/embeddings';

        try {
            $response = $this->getHttpClient()->withToken($this->config['apiKey'])
                ->timeout(60)
                ->post($url, [
                    'model' => $model,
                    'input' => $text,
                ]);

            if (!$response->successful()) {
                Log::error("Embeddings generation failed", ['error' => $response->body()]);
                return null;
            }

            $body = $response->json();
            return $body['data'][0]['embedding'] ?? null;
        } catch (\Exception $e) {
            Log::error("Embeddings exception: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get pre-configured HTTP client with keep-alive progress callback.
     */
    private function getHttpClient()
    {
        return Http::withOptions([
            'progress' => function () {
                static $lastPing = 0;
                $now = microtime(true);
                // Ping every 5 seconds if running inside a stream (ob_get_level > 0)
                if ($now - $lastPing > 5 && ob_get_level() > 0) {
                    echo " ";
                    @ob_flush();
                    @flush();
                    $lastPing = $now;
                }
            }
        ]);
    }

    /**
     * Call OpenAI API.
     */
    private function callOpenAI(string $systemPrompt, string $userPrompt, array $options): array
    {
        $response = $this->getHttpClient()->withToken($this->config['apiKey'])
            ->timeout(300)
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

        $response = $this->getHttpClient()->withHeaders([
            'Content-Type' => 'application/json',
        ])
        ->timeout(300)
        ->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}", [
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
        $response = $this->getHttpClient()->withHeaders([
            'x-api-key' => $this->config['apiKey'],
            'anthropic-version' => '2023-06-01',
            'Content-Type' => 'application/json',
        ])
        ->timeout(300)
        ->post('https://api.anthropic.com/v1/messages', [
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
        $response = $this->getHttpClient()->withToken($this->config['apiKey'])
            ->timeout(300)
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
        $apiBase = $this->config['apiBase'] ?: 'https://api.9router.com/v1';
        $url = rtrim($apiBase, '/') . '/chat/completions';

        $response = $this->getHttpClient()->withToken($this->config['apiKey'])
            ->timeout(300)
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
            throw new \RuntimeException(
                ($body['error']['message'] ?? '9Router API error') .
                ' RAW:' . substr($response->body(), 0, 200)
            );
        }

        return [
            'content'      => $body['choices'][0]['message']['content'],
            'usage'        => $body['usage'] ?? [],
            'model'        => $body['model'] ?? ($options['model'] ?? $this->config['model']),
            '_format'      => 'json',
            '_http_status' => $response->status(),
        ];
    }

    /**
     * Call Custom API (OpenAI Compatible).
     */
    private function callCustom(string $systemPrompt, string $userPrompt, array $options): array
    {
        $apiBase = $this->config['apiBase'] ?: 'http://localhost:20128/v1';
        $url = rtrim($apiBase, '/') . '/chat/completions';

        $request = $this->getHttpClient()->asJson()
            ->timeout(300)
            ->withHeaders([
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
            'max_tokens'  => $options['max_tokens'] ?? $this->config['max_tokens'],
            'stream'      => true, // Enable stream to prevent 524 Cloudflare timeouts
        ]);

        $rawBody = $response->body();

        // ── Handle SSE / streaming response format ────────────────────────────
        // Some free/proxy APIs return SSE chunks even when stream=false.
        // Format: "data: {json}\n\ndata: {json}\n\ndata: [DONE]"
        // We detect this by checking if the body starts with 'data:'
        if (str_starts_with(ltrim($rawBody), 'data:')) {
            $fullContent = '';
            $lastUsage   = [];
            $lastModel   = $options['model'] ?? $this->config['model'];
            $hasReasoningStarted = false;

            foreach (explode("\n", $rawBody) as $line) {
                $line = trim($line);
                if (!str_starts_with($line, 'data:')) continue;
                $json = trim(substr($line, 5));
                if ($json === '[DONE]' || $json === '') continue;

                $chunk = json_decode($json, true);
                if (!is_array($chunk)) continue;

                // Accumulate delta content (streaming format)
                $delta = $chunk['choices'][0]['delta']['content'] ?? null;
                $reasoning = $chunk['choices'][0]['delta']['reasoning_content'] ?? null;
                
                if ($reasoning !== null && $reasoning !== '') {
                    if (!$hasReasoningStarted) {
                        $fullContent .= "<think>\n";
                        $hasReasoningStarted = true;
                    }
                    $fullContent .= $reasoning;
                }

                if ($delta !== null && $delta !== '') {
                    if ($hasReasoningStarted) {
                        $fullContent .= "\n</think>\n\n";
                        $hasReasoningStarted = false;
                    }
                    $fullContent .= $delta;
                }

                // Also handle non-delta (some APIs mix formats)
                $msg = $chunk['choices'][0]['message']['content'] ?? null;
                if ($msg !== null && empty($fullContent)) {
                    $fullContent = $msg;
                }

                if (!empty($chunk['usage'])) $lastUsage = $chunk['usage'];
                if (!empty($chunk['model'])) $lastModel = $chunk['model'];
            }
            
            if ($hasReasoningStarted) {
                $fullContent .= "\n</think>\n\n";
            }

            if (!empty($fullContent)) {
                return [
                    'content'      => $fullContent,
                    'usage'        => $lastUsage,
                    'model'        => $lastModel,
                    '_format'      => 'sse',
                    '_http_status' => $response->status(),
                ];
            }

            // SSE detected but no content parsed — throw with raw snippet
            throw new \RuntimeException(
                'Custom API returned SSE but no content could be parsed. RAW:' . substr($rawBody, 0, 300)
            );
        }

        // ── Standard JSON response ────────────────────────────────────────────
        $rawBody = preg_replace('/data:\s*\[DONE\]\s*$/i', '', trim($rawBody));
        $body = json_decode($rawBody, true);

        if (!$response->successful()) {
            throw new \RuntimeException(
                ((is_array($body) ? ($body['error']['message'] ?? '') : '') ?: 'Custom API error (HTTP ' . $response->status() . ')') .
                ' RAW:' . substr($rawBody, 0, 200)
            );
        }

        if (!is_array($body)) {
            throw new \RuntimeException(
                'Custom API returned non-JSON response. RAW:' . substr($rawBody, 0, 300)
            );
        }

        $content = $body['choices'][0]['message']['content'] ?? '';

        if (trim($content) === '') {
            throw new \RuntimeException(
                'Custom API returned empty content (JSON). RAW:' . substr($rawBody, 0, 500)
            );
        }

        return [
            'content'      => $content,
            'usage'        => $body['usage'] ?? [],
            'model'        => $body['model'] ?? ($options['model'] ?? $this->config['model']),
            '_format'      => 'json',
            '_http_status' => $response->status(),
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