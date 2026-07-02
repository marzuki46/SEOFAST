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
            'message' => "RecoveryManager: Mendeteksi error [{$classification}] di {$currentPhase}. Memulai Recovery Strategy..."
        ];

        try {
            switch ($classification) {
                case 'json_invalid':
                case 'html_invalid':
                case 'output_incomplete':
                case 'halogenation':
                    return $this->attemptRepair($job, $content, $currentPhase, $classification, $logs, $errorMsg);
                
                case 'rate_limit':
                    return $this->applyCircuitBreaker($job, 'rate_limit', 90, $logs);

                case 'timeout':
                case 'server_error':
                    return $this->applyExponentialBackoff($job, $logs);

                default:
                    // Default to backoff before failing completely
                    return $this->applyExponentialBackoff($job, $logs);
            }
        } catch (\Exception $recoveryException) {
            $logs[] = ['level' => 'error', 'message' => 'Sistem Recovery ikut gagal: ' . $recoveryException->getMessage()];
            return $this->applyExponentialBackoff($job, $logs); // Fallback to backoff
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

        // Parsing errors
        if (str_contains($error, 'json') || str_contains($error, 'decode') || str_contains($error, 'syntax error')) {
            return 'json_invalid';
        }

        if (str_contains($error, 'html') || str_contains($error, 'tag') || str_contains($error, 'unclosed') || str_contains($error, 'buntung')) {
            return 'html_invalid';
        }

        if (str_contains($error, 'empty content') || str_contains($error, 'too short') || str_contains($error, 'null')) {
            return 'output_incomplete';
        }
        
        if (str_contains($error, 'basa-basi') || str_contains($error, 'halogenation') || str_contains($error, 'format tidak sesuai')) {
            return 'halogenation';
        }

        return 'unknown';
    }

    /**
     * Attempts to repair the content based on the current phase.
     */
    private function attemptRepair(AiGenerationJob $job, Content $content, string $phase, string $issue, array &$logs, string $originalError)
    {
        $logs[] = ['level' => 'info', 'message' => "Menjalankan Repair Mode untuk [{$issue}] di {$phase}..."];
        
        $aiService = new AIService($this->tenant, 'default');
        
        // ── Phase 1 & 7: Membutuhkan format JSON ──
        if ($phase === 'phase_1' || $phase === 'phase_7') {
            $logs[] = ['level' => 'info', 'message' => 'Mencoba memperbaiki format JSON yang rusak...'];
            $brokenData = $phase === 'phase_1' ? $job->phase_1_lsi : ($job->phase_7_meta ?? $originalError);
            
            $prompt = "Berikut adalah respon JSON yang rusak atau tidak valid. Perbaiki sintaksnya agar menjadi JSON murni yang valid tanpa tambahan teks apa pun.\n\nRAW DATA:\n" . substr((string)$brokenData, 0, 1500);
            $repaired = $aiService->generate("You are a JSON syntax fixer.", $prompt);
            
            if ($repaired) {
                // Return 'continue' so the main pipeline will try to parse it again in the next cycle
                $logs[] = ['level' => 'success', 'message' => 'Repair JSON selesai. Mengulangi proses parsing...'];
                return ['success' => true, 'status' => 'continue'];
            }
        }
        
        // ── Phase 2, 4, 5: Masalah output terpotong atau terlalu pendek (Incomplete Output) ──
        if (in_array($phase, ['phase_2', 'phase_4', 'phase_5']) && $issue === 'output_incomplete') {
            $logs[] = ['level' => 'info', 'message' => 'Teks terpotong/terlalu pendek. Mengirim instruksi Auto-Completion...'];
            
            $brokenText = '';
            if ($phase === 'phase_2') $brokenText = $job->phase_1_draft;
            if ($phase === 'phase_4') $brokenText = $job->phase_4_answers;
            if ($phase === 'phase_5') $brokenText = $job->phase_5_combined;
            
            if (strlen((string)$brokenText) > 100) {
                $lastWords = substr($brokenText, -500);
                $prompt = "Lanjutkan teks berikut yang terpotong. Lanjutkan tepat dari kalimat terakhir ini tanpa mengulangnya:\n\n[...]" . $lastWords;
                
                // Gunakan model yang lebih cepat/murah (misal claude atau gpt-4o-mini) jika tersedia untuk repair
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

        // ── Phase 6: HTML Rusak / Buntung ──
        if ($phase === 'phase_6') {
            $brokenHtml = $job->phase_6_html;
            if (!$brokenHtml) {
                return $this->applyExponentialBackoff($job, $logs);
            }

            $prompt = "Perbaiki HTML ini. Jika ada tag yang belum tertutup (seperti <div> atau <p> atau <article_body>), tutuplah dengan benar di bagian akhir. JANGAN MERUBAH ISI TEKS di awalnya. Hanya kembalikan HTML akhirnya saja.\n\nHTML:\n" . substr($brokenHtml, -3000);
            
            // Set temperature to 0 for strict structural repair
            $repaired = $aiService->generate("You are an expert HTML code fixer.", $prompt, ['temperature' => 0.0]);
            
            if ($repaired) {
                $job->update(['phase_6_html' => $brokenHtml . "\n" . $repaired]);
                $logs[] = ['level' => 'success', 'message' => 'Tag HTML berhasil ditutup dan diperbaiki secara otomatis.'];
                return ['success' => true, 'status' => 'continue'];
            }
        }

        // ── Phase 3: Basa-basi (Halogenation) di list pertanyaan ──
        if ($phase === 'phase_3' && $issue === 'halogenation') {
            $logs[] = ['level' => 'info', 'message' => 'Membersihkan respon AI dari basa-basi...'];
            $brokenData = $job->phase_3_questions ?? $originalError;
            
            $prompt = "Ekstrak hanya daftar pertanyaannya saja ke dalam format JSON array berisi string. Jangan ada teks lain.\n\nInput:\n" . substr((string)$brokenData, 0, 1000);
            $repaired = $aiService->generateJson("You extract questions to JSON.", $prompt);
            
            if ($repaired) {
                $job->update(['phase_3_questions' => json_encode($repaired)]);
                $logs[] = ['level' => 'success', 'message' => 'Pertanyaan berhasil diekstrak dan dibersihkan.'];
                return ['success' => true, 'status' => 'continue'];
            }
        }

        // Jika repair gagal, masuk ke Exponential Backoff
        $logs[] = ['level' => 'warn', 'message' => 'Repair Mode gagal memperbaiki output. Menggunakan Exponential Backoff...'];
        return $this->applyExponentialBackoff($job, $logs);
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
        if ($job->retry_count >= 5) { // Ditingkatkan ke 5 agar lebih tangguh
            $logs[] = ['level' => 'error', 'message' => '❌ Batas maksimal retry tercapai (5x). Artikel ini ditandai Gagal.'];
            $job->update(['status' => 'failed', 'error_log' => ['reason' => 'Max retries exceeded during recovery.']]);
            $job->content?->update(['status' => 'failed_cqi']);
            return ['success' => false, 'error' => 'Max retries exceeded'];
        }

        $job->increment('retry_count');
        
        // Pola Backoff: 5s, 10s, 20s, 40s, 80s
        $backoffSeconds = pow(2, $job->retry_count - 1) * 5; 
        
        $logs[] = ['level' => 'warn', 'message' => "🔄 Exponential Backoff: Mencoba ulang kembali (Percobaan ke-{$job->retry_count}/5). Menunda proses selama {$backoffSeconds} detik..."];
        
        sleep($backoffSeconds); 
        
        return ['success' => true, 'status' => 'continue'];
    }

    public function isCircuitOpen(): bool
    {
        return Cache::has('ai_circuit_breaker');
    }
}
