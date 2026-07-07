<?php

namespace App\Services;

use App\Models\AiGenerationJob;
use App\Models\Content;
use App\Models\Tenant;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class AiRecoveryManager
{
    private Tenant|null $tenant;
    private const MAX_RETRIES = 10;

    public function __construct($tenant = null)
    {
        $this->tenant = $tenant ?? \App\Models\Tenant::first();
    }

    /**
     * Entry point for handling AI pipeline failures.
     */
    public function handleFailure(AiGenerationJob $job, Content $content, \Exception $e, string $currentPhase, array &$logs)
    {
        $errorMsg = $e->getMessage();
        $classification = $this->classifyError($errorMsg);

        $logs[] = [
            'level' => 'warn',
            'message' => "RecoveryManager: Mendeteksi error [{$classification}] di {$currentPhase}. Penyebab: {$errorMsg}",
        ];

        try {
            switch ($classification) {
                case 'missing_phase_data':
                    return $this->handleMissingData($job, $content, $currentPhase, $logs, $errorMsg);

                case 'json_invalid':
                case 'html_invalid':
                case 'output_incomplete':
                case 'halogenation':
                    $result = $this->attemptRepair($job, $content, $currentPhase, $classification, $logs, $errorMsg);
                    if ($result['success'] ?? false) {
                        return $result;
                    }
                    // Repair gagal — fallback ke generasi template
                    return $this->generateFallback($job, $content, $currentPhase, $logs, $errorMsg);

                case 'rate_limit':
                    return $this->applyCircuitBreaker($job, 'rate_limit', 90, $logs);

                case 'timeout':
                case 'server_error':
                    return $this->applyExponentialBackoff($job, $logs);

                default:
                    // Coba repair dulu, kalau gagal fallback
                    $result = $this->attemptRepair($job, $content, $currentPhase, $classification, $logs, $errorMsg);
                    if ($result['success'] ?? false) {
                        return $result;
                    }
                    return $this->generateFallback($job, $content, $currentPhase, $logs, $errorMsg);
            }
        } catch (\Exception $recoveryException) {
            $logs[] = ['level' => 'error', 'message' => 'Sistem Recovery ikut gagal: ' . $recoveryException->getMessage()];
            return $this->generateFallback($job, $content, $currentPhase, $logs, $errorMsg ?? $recoveryException->getMessage());
        }
    }

    /**
     * Classifies the error string into specific categories.
     */
    private function classifyError(string $error): string
    {
        $error = strtolower($error);

        if (str_contains($error, '429') || str_contains($error, 'too many requests') || str_contains($error, 'rate limit')) {
            return 'rate_limit';
        }

        if (str_contains($error, 'timeout') || str_contains($error, 'curl error 28') || str_contains($error, '524') || str_contains($error, '520')) {
            return 'timeout';
        }

        if (str_contains($error, '500') || str_contains($error, '502') || str_contains($error, '503') || str_contains($error, 'bad gateway')) {
            return 'server_error';
        }

        // Missing data from previous phase
        if (str_contains($error, 'empty in db') || (str_contains($error, 'failed') && str_contains($error, 'empty'))) {
            return 'missing_phase_data';
        }

        if (str_contains($error, 'json') || str_contains($error, 'decode') || str_contains($error, 'syntax error')) {
            return 'json_invalid';
        }

        if (str_contains($error, 'html') || str_contains($error, 'tag') || str_contains($error, 'unclosed') || str_contains($error, 'buntung')) {
            return 'html_invalid';
        }

        if (str_contains($error, 'empty content') || str_contains($error, 'too short') || str_contains($error, 'null') || str_contains($error, 'insufficient')) {
            return 'output_incomplete';
        }

        if (str_contains($error, 'basa-basi') || str_contains($error, 'halogenation') || str_contains($error, 'format tidak sesuai')) {
            return 'halogenation';
        }

        return 'unknown';
    }

    // ──────────────────────────────────────────────────────
    //  MISSING DATA — revert to previous phase to regenerate
    // ──────────────────────────────────────────────────────

    private function handleMissingData(AiGenerationJob $job, Content $content, string $currentPhase, array &$logs, string $errorMsg)
    {
        $previousPhase = match ($currentPhase) {
            'phase_2' => 'phase_1',
            'phase_3' => 'phase_2',
            'phase_4' => 'phase_3',
            'phase_5' => 'phase_4',
            'phase_6' => 'phase_5',
            default   => 'phase_1',
        };

        $logs[] = ['level' => 'warn', 'message' => "Data fase sebelumnya ({$previousPhase}) tidak ditemukan. Mengulang dari {$previousPhase}..."];

        if ($job->retry_count >= self::MAX_RETRIES) {
            $logs[] = ['level' => 'error', 'message' => 'Batas retry tercapai. Menggunakan fallback template.'];
            return $this->generateFallback($job, $content, $currentPhase, $logs, $errorMsg);
        }

        $job->update([
            'status'      => $previousPhase,
            'retry_count' => $job->retry_count + 1,
            'error_log'   => ['reason' => $errorMsg, 'retry' => $job->retry_count + 1],
        ]);

        $logs[] = ['level' => 'info', 'message' => "⬅️ Kembali ke {$previousPhase} untuk regenerasi data (percobaan ke-" . ($job->retry_count) . "/" . self::MAX_RETRIES . ")."];
        return ['success' => true, 'status' => 'continue'];
    }

    // ──────────────────────────────────────────────────────
    //  FALLBACK — generate template content as last resort
    // ──────────────────────────────────────────────────────

    private function generateFallback(AiGenerationJob $job, Content $content, string $phase, array &$logs, string $originalError)
    {
        $logs[] = ['level' => 'warn', 'message' => 'Semua percobaan gagal. Membuat konten template sebagai fallback...'];

        try {
            $keyword = $content->target_keyword;

            switch ($phase) {
                case 'phase_1':
                    $fallbackLsi = "{$keyword}, {$keyword} tips, {$keyword} guide, {$keyword} tutorial, {$keyword} best practices, {$keyword} strategy, learn {$keyword}, {$keyword} for beginners, {$keyword} examples, {$keyword} tools";
                    $job->update([
                        'status'      => 'phase_2',
                        'phase_1_lsi' => $fallbackLsi,
                    ]);
                    $logs[] = ['level' => 'success', 'message' => "✅ Fallback LSI keywords generated for: {$keyword}"];
                    return ['success' => true, 'status' => 'continue'];

                case 'phase_2':
                    $existingLsi = $job->phase_1_lsi ?? "{$keyword}, {$keyword} guide";
                    $template = $this->buildTemplateDraft($keyword, $existingLsi);
                    $job->update([
                        'status'       => 'phase_3',
                        'phase_1_draft' => $template,
                    ]);
                    $logs[] = ['level' => 'success', 'message' => "✅ Fallback draft template created for: {$keyword}"];
                    return ['success' => true, 'status' => 'continue'];

                case 'phase_3':
                    $defaultQuestions = ["What is {$keyword}?", "How to implement {$keyword}?", "Best practices for {$keyword}?"];
                    $job->update([
                        'status'           => 'phase_4',
                        'phase_2_critique' => $defaultQuestions,
                    ]);
                    $logs[] = ['level' => 'success', 'message' => "✅ Fallback questions generated for: {$keyword}"];
                    return ['success' => true, 'status' => 'continue'];

                case 'phase_4':
                    $draft = $job->phase_1_draft ?? '';
                    $critique = $job->phase_2_critique ?? [];
                    $questions = is_array($critique) ? (isset($critique[0]) ? $critique : ($critique['questions'] ?? [])) : [];
                    $answers = '';
                    foreach ($questions as $i => $q) {
                        $answers .= "\n\n## " . ($i + 1) . ". {$q}\n\n{$keyword} is an important topic. To implement it successfully, follow best practices and proven strategies. Consult with experts and use reliable tools for best results.";
                    }
                    $job->update([
                        'status'          => 'phase_5',
                        'phase_4_answers' => $answers,
                    ]);
                    $logs[] = ['level' => 'success', 'message' => "✅ Fallback answers generated for: {$keyword}"];
                    return ['success' => true, 'status' => 'continue'];

                case 'phase_5':
                case 'phase_6':
                    // Jangan override status kalau job sudah completed
                    if ($job->status === 'completed') {
                        $logs[] = ['level' => 'warn', 'message' => 'Job sudah completed. Tidak perlu fallback.'];
                        return ['success' => true, 'status' => 'continue'];
                    }

                    $draft = $job->phase_1_draft ?? '';
                    $answers = $job->phase_4_answers ?? '';
                    $combined = $draft . "\n\n" . $answers;
                    $html = "<article>\n" . \Illuminate\Support\Str::markdown($combined) . "\n</article>";

                    $job->update([
                        'status'          => 'phase_6',
                        'phase_5_combined'=> $combined,
                        'phase_6_html'    => $html,
                    ]);

                    if ($phase === 'phase_6') {
                        $logs[] = ['level' => 'success', 'message' => "✅ Fallback HTML created. Proceeding to save."];
                    } else {
                        $logs[] = ['level' => 'success', 'message' => "✅ Fallback combine+HTML created."];
                        // One more dispatch to get to phase_6
                        return ['success' => true, 'status' => 'continue'];
                    }
                    return ['success' => true, 'status' => 'continue'];

                default:
                    $logs[] = ['level' => 'error', 'message' => 'Fase tidak dikenal. Mengirim ke backoff.'];
                    return $this->applyExponentialBackoff($job, $logs);
            }
        } catch (\Exception $e) {
            $logs[] = ['level' => 'error', 'message' => 'Fallback template juga gagal: ' . $e->getMessage()];
            return $this->applyExponentialBackoff($job, $logs);
        }
    }

    /**
     * Build a template draft article from keyword + LSI.
     */
    private function buildTemplateDraft(string $keyword, string $lsi): string
    {
        $lsiList = explode(',', $lsi);
        $lsiItems = '';
        foreach ($lsiList as $item) {
            $item = trim($item);
            if ($item) {
                $lsiItems .= "- **{$item}**\n";
            }
        }

        return <<<MARKDOWN
# Comprehensive Guide to {$keyword}

## Introduction

Understanding **{$keyword}** is essential for anyone looking to improve their digital strategy. This guide covers all the fundamental aspects and advanced techniques you need to know.

## Key Concepts

The following topics are critical to mastering **{$keyword}**:

{$lsiItems}

## Step-by-Step Implementation

1. **Research** — Start by understanding the core principles of {$keyword}.
2. **Plan** — Create a structured approach based on industry best practices.
3. **Execute** — Implement your strategy with attention to detail.
4. **Monitor** — Track results and optimize continuously.
5. **Iterate** — Refine your approach based on data and feedback.

## Best Practices

- Always start with a clear goal in mind
- Use reliable tools and resources
- Test different approaches to find what works best
- Stay updated with the latest developments in {$keyword}
- Document your process for future reference

## Common Mistakes to Avoid

- Skipping the research phase
- Not measuring results properly
- Overcomplicating the process
- Ignoring user intent
- Failing to iterate based on feedback

## Conclusion

Mastering **{$keyword}** takes time and practice, but with the right approach, you can achieve excellent results. Focus on continuous learning and improvement.

---

*This article was automatically generated by SEOFAST AI Pipeline.*
MARKDOWN;
    }

    // ──────────────────────────────────────────────────────
    //  REPAIR MODE
    // ──────────────────────────────────────────────────────

    private function attemptRepair(AiGenerationJob $job, Content $content, string $phase, string $issue, array &$logs, string $originalError)
    {
        $logs[] = ['level' => 'info', 'message' => "Menjalankan Repair Mode untuk [{$issue}] di {$phase}..."];

        $aiService = new AIService($this->tenant, 'default');

        // ── Phase 1 (LSI): JSON rusak —─
        if ($phase === 'phase_1') {
            $logs[] = ['level' => 'info', 'message' => 'Mencoba memperbaiki format...'];
            $brokenData = $job->phase_1_lsi ?? $originalError;

            $prompt = "Ekstrak dan perbaiki daftar keyword dari teks berikut. Kembalikan hanya sebagai comma-separated string, tanpa teks lain.\n\nInput:\n" . substr((string)$brokenData, 0, 1500);
            $repaired = $aiService->generate("You extract and fix keyword lists.", $prompt);

            if ($repaired && mb_strlen(trim($repaired)) > 10) {
                $job->update(['phase_1_lsi' => trim($repaired)]);
                $logs[] = ['level' => 'success', 'message' => 'Repair LSI keywords berhasil.'];
                return ['success' => true, 'status' => 'continue'];
            }
        }

        // ── Phase 2 (Draft), Phase 4 (Answers), Phase 5 (Combine): output terpotong ──
        if (in_array($phase, ['phase_2', 'phase_4', 'phase_5']) && $issue === 'output_incomplete') {
            $logs[] = ['level' => 'info', 'message' => 'Teks terpotong/terlalu pendek. Mengirim instruksi Auto-Completion...'];

            $brokenText = '';
            if ($phase === 'phase_2') $brokenText = $job->phase_1_draft;
            if ($phase === 'phase_4') $brokenText = $job->phase_4_answers;
            if ($phase === 'phase_5') $brokenText = $job->phase_5_combined;

            if (strlen((string)$brokenText) > 100) {
                $lastWords = substr($brokenText, -500);
                $prompt = "Lanjutkan teks berikut yang terpotong. Lanjutkan tepat dari kalimat terakhir ini tanpa mengulangnya:\n\n[...]" . $lastWords;
                $repaired = $aiService->generate("You are an auto-completion AI.", $prompt, ['temperature' => 0.2]);

                if ($repaired) {
                    $newText = $brokenText . " " . $repaired;
                    if ($phase === 'phase_2') $job->update(['phase_1_draft' => $newText]);
                    if ($phase === 'phase_4') $job->update(['phase_4_answers' => $newText]);
                    if ($phase === 'phase_5') $job->update(['phase_5_combined' => $newText]);

                    $logs[] = ['level' => 'success', 'message' => 'Auto-Completion berhasil menyambung teks.'];
                    return ['success' => true, 'status' => 'continue'];
                }
            }
        }

        // ── Phase 5 (HTML): HTML Rusak / Buntung ──
        if ($phase === 'phase_5') {
            $brokenHtml = $job->phase_6_html;
            if (!$brokenHtml) {
                return ['success' => false];
            }

            $prompt = "Perbaiki HTML ini. Jika ada tag yang belum tertutup (seperti <div> atau <p> atau <article_body>), tutuplah dengan benar di bagian akhir. JANGAN MERUBAH ISI TEKS di awalnya. Hanya kembalikan HTML akhirnya saja.\n\nHTML:\n" . substr($brokenHtml, -3000);
            $repaired = $aiService->generate("You are an expert HTML code fixer.", $prompt, ['temperature' => 0.0]);

            if ($repaired) {
                $job->update(['phase_6_html' => $brokenHtml . "\n" . $repaired]);
                $logs[] = ['level' => 'success', 'message' => 'Tag HTML berhasil ditutup dan diperbaiki secara otomatis.'];
                return ['success' => true, 'status' => 'continue'];
            }
        }

        // ── Phase 3 (Questions): Basa-basi (Halogenation) ──
        if ($phase === 'phase_3' && $issue === 'halogenation') {
            $logs[] = ['level' => 'info', 'message' => 'Membersihkan respon AI dari basa-basi...'];
            $critique = $job->phase_2_critique;

            $prompt = "Ekstrak hanya JSON array of strings dari input berikut. Jangan ada teks lain.\n\nInput:\n" . substr(json_encode($critique ?? $originalError), 0, 2000);
            $repaired = $aiService->generateJson("You extract JSON array of strings.", $prompt);

            if ($repaired && is_array($repaired)) {
                $job->update(['phase_2_critique' => $repaired]);
                $logs[] = ['level' => 'success', 'message' => 'Questions array berhasil diekstrak.'];
                return ['success' => true, 'status' => 'continue'];
            }
        }

        $logs[] = ['level' => 'warn', 'message' => 'Repair Mode gagal memperbaiki output. Mencoba fallback template...'];
        return ['success' => false];
    }

    /**
     * Applies a circuit breaker pause.
     */
    private function applyCircuitBreaker(AiGenerationJob $job, string $reason, int $seconds, array &$logs)
    {
        $logs[] = ['level' => 'warn', 'message' => "Circuit Breaker AKTIF ({$reason}). Menjeda semua job selama {$seconds} detik untuk mencegah blokir (banned) dari API Provider."];
        Cache::put('ai_circuit_breaker', true, $seconds);

        return [
            'success' => true,
            'status' => 'wait',
            'wait_time' => $seconds,
        ];
    }

    /**
     * Applies exponential backoff for a specific job.
     */
    private function applyExponentialBackoff(AiGenerationJob $job, array &$logs)
    {
        if ($job->retry_count >= self::MAX_RETRIES) {
            $logs[] = ['level' => 'error', 'message' => '❌ Batas maksimal retry tercapai (' . self::MAX_RETRIES . 'x). Artikel ini ditandai Gagal.'];
            $job->update(['status' => 'failed', 'error_log' => ['reason' => 'Max retries exceeded during recovery.']]);
            $job->content?->update(['status' => 'failed_cqi']);
            return ['success' => false, 'error' => 'Max retries exceeded'];
        }

        $job->increment('retry_count');

        // Pola Backoff: 5s, 10s, 20s, 40s, 80s
        $backoffSeconds = pow(2, $job->retry_count - 1) * 5;

        $logs[] = ['level' => 'warn', 'message' => "🔄 Exponential Backoff: Mencoba ulang kembali (Percobaan ke-{$job->retry_count}/" . self::MAX_RETRIES . "). Menunda proses selama {$backoffSeconds} detik..."];

        sleep($backoffSeconds);

        return ['success' => true, 'status' => 'continue'];
    }

    public function isCircuitOpen(): bool
    {
        return Cache::has('ai_circuit_breaker');
    }
}
