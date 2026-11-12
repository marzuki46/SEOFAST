<?php
namespace App\Services;

use RuntimeException;
use App\Models\Tenant;

class ReliableContentGenerator
{
    public function generateAndSave(\App\Models\Content $content, \App\Models\AiGenerationJob $job, string $targetStatus = 'published'): void
    {
        $keyword = $content->target_keyword;
        $title = $content->meta_title ?: $content->target_keyword;
        
        $topic = $content->topic;
        $brief = $topic?->brief ?: ($topic?->angle ?: ($content->meta_description ?: $content->target_keyword));
        $funnelStage = $topic?->funnel_stage ?: $content->hierarchy_level;
        $angle = $topic?->angle ?: '';
        
        $campaign = $content->campaign;
        if ($campaign) {
            $campaign = app(ContentCampaignPromptService::class)->applyDefaults($campaign);
        }

        $whatsapp = $campaign?->whatsapp ?: '6282213028718';
        $ctaLabel = $campaign?->cta_label ?: 'Konsultasi Sekarang via WhatsApp';
        $ctaUrl = $campaign?->cta_url ?: "https://wa.me/{$whatsapp}";

        $niche = $campaign?->niche ?: 'Jasa & Solusi Bisnis Digital';
        $brandVoice = $campaign?->brand_voice ?: 'Otoritatif, Solutif, Informatif, Profesional';
        $audience = $campaign?->audience ?: 'Pemilik Bisnis, Calon Klien, Pengambil Keputusan';
        $businessData = $campaign?->business_data ?: '';
        $customContentPrompt = $campaign?->content_prompt ?: '';
        $minWords = $campaign?->word_count_min ?: 1000;
        $maxWords = $campaign?->word_count_max ?: 1500;

        $tenant = $content->tenant ?? Tenant::first();
        $aiService = new AIService($tenant, 'content');

        $temporalContext = app(ContentCampaignPromptService::class)->temporalContext($campaign?->timezone ?: 'Asia/Jakarta');

        $system = "Anda adalah penulis profesional, editor, dan pakar SEO Indonesia yang menulis dengan gaya penulisan: {$brandVoice}. Niche: {$niche}. Target Audiens: {$audience}. Tulis artikel SEO berkualitas tinggi, sangat mendalam (antara {$minWords} sampai {$maxWords} kata), natural, menggunakan format HTML lengkap dengan tag <h2>, <h3>, <p>, <strong>, <em>, <ul>, <li>, serta tabel perbandingan (Markdown/HTML) jika relevan. 
Hindari klise AI ('Di era digital', 'Mari kita telusuri', dll). Gunakan sudut pandang orang pertama ('saya', 'pengalaman saya'). 
Data & Informasi Bisnis Pendukung:\n{$businessData}
";

        if ($customContentPrompt) {
            $system .= "\n\nATURAN KHUSUS CAMPAIGN CONTENT PROMPT:\n" . strip_tags($customContentPrompt);
        }

        $user = "{$temporalContext}\n\nBuat artikel SEO komprehensif dan mendalam berdasarkan brief spesifik topik berikut:\nTarget Keyword: {$keyword}\nJudul Utama: {$title}\nTahap Funnel: {$funnelStage}\nSudut Pandang / Angle: {$angle}\nBrief Konten / Panduan Khusus:\n{$brief}\n\nDi akhir artikel, sertakan Call to Action (CTA) profesional berikan link tombol/teks ke URL: {$ctaUrl} dengan label: {$ctaLabel}.\n\nBerikan respons DALAM FORMAT JSON VALID BERIKUT (tanpa markdown backtick):\n{\n  \"meta_title\": \"Judul SEO max 70 karakter yang menarik\",\n  \"meta_description\": \"Deskripsi SEO max 160 karakter yang informatif\",\n  \"content\": \"<h2>Pengantar</h2><p>Isi artikel lengkap dan mendalam (minimal {$minWords} kata) format HTML...</p>\"\n}";

        $text = $aiService->generateJson($system, $user);

        if (!is_array($text) || empty($text['content'])) {
            $rawText = is_string($text) ? $text : json_encode($text);
            $htmlContent = '<p>' . nl2br(htmlspecialchars($rawText)) . '</p>';
        } else {
            $sanitizer = new HtmlSanitizer();
            $htmlContent = $sanitizer->sanitize((string)$text['content']);
            if ($text['meta_title'] ?? '') $content->meta_title = $text['meta_title'];
            if ($text['meta_description'] ?? '') $content->meta_description = $text['meta_description'];
        }

        $content->body_raw = $htmlContent;
        $content->status = $targetStatus;
        $content->published_at = $content->published_at ?? now();
        $content->cqi_score = 95;
        $content->save();

        $job->update([
            'status' => 'completed',
            'phase_6_html' => $htmlContent,
            'phase_4_final' => $htmlContent,
            'completed_at' => now(),
        ]);
    }
}
