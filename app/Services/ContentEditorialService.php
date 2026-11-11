<?php

namespace App\Services;

use App\Models\Content;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\Log;

class ContentEditorialService
{
    /**
     * Memproses review dan penyempurnaan konten oleh Editorial AI Team.
     */
    public function polish(Content $content): bool
    {
        $enabled = (bool) SystemSetting::get('apc_editorial_enabled', false);
        if (! $enabled) {
            return false;
        }

        $keyword = $content->target_keyword;
        $title = $content->meta_title ?: $content->title;
        $body = $content->body_raw;

        if (blank($body) || blank($keyword)) {
            return false;
        }

        $ai = new AIService($content->tenant, 'editor');
        $editorialPrompt = SystemSetting::get('apc_editorial_prompt') ?: $this->defaultEditorialPrompt();

        $systemPrompt = <<<PROMPT
Anda adalah Kepala Tim Editorial SEO dan Editor Ahli Manusia.
Tugas Anda adalah membaca seluruh draft artikel, memahami target keyword, lalu menyunting dan menyempurnakan bagian-bagian yang kaku, repetitif, atau terlalu beraroma robot AI.

Prinsip Editorial:
1. PERTAHANKAN seluruh link markdown [teks](url) atau tag HTML <a> persis apa adanya. Jangan hapus link internal atau CTA landing page!
2. PERTAHANKAN target keyword utama '{$keyword}' di tempat-tempat krusial secara natural.
3. Haluskan pembuka kalimat agar tidak monoton. Ganti kalimat pasif atau klise dengan analogi segar, contoh praktis, atau sudut pandang solutif.
4. Pastikan alur paragraf mengalir logis, transisi antar sub-heading rapi, dan mudah dibaca pembaca manusia (skimmable).
5. Outputkan HANYA konten artikel yang sudah disempurnakan (Markdown atau HTML sesuai format awal). Jangan berikan pengantar atau catatan kaki penjelasan.
PROMPT;

        $userPrompt = "TARGET KEYWORD: {$keyword}\nJUDUL: {$title}\n\nARAHAN EDITORIAL KHUSUS:\n{$editorialPrompt}\n\nKONTEN ASLI UNTUK DIEDIT:\n{$body}";

        try {
            $improved = $ai->generate($systemPrompt, $userPrompt, [
                'temperature' => 0.4,
                'max_tokens' => 8192,
            ]);

            if (blank($improved) || mb_strlen($improved) < 300) {
                Log::warning("Editorial review produced empty or too short content for content #{$content->id}");

                return false;
            }

            // Update konten: timpa body_raw dengan versi hasil edit
            $origTime = $content->published_at ?: now();
            // Jitter revisi alami 15-45 menit setelah published
            $jitterSec = random_int(15 * 60, 45 * 60);
            $revisedTimestamp = $origTime->copy()->addSeconds($jitterSec);

            $content->update([
                'body_raw' => $improved,
                'revised_at' => $revisedTimestamp,
                'last_partial_update_at' => $revisedTimestamp,
                'editorial_review_count' => ($content->editorial_review_count ?? 0) + 1,
                'last_editorial_notes' => 'Disempurnakan oleh Tim Editorial AI.',
            ]);

            Log::info("Editorial AI polished content #{$content->id} successfully | revised_at={$revisedTimestamp}");

            return true;
        } catch (\Throwable $e) {
            Log::error("Editorial polish error for content #{$content->id}: {$e->getMessage()}");

            return false;
        }
    }

    public function defaultEditorialPrompt(): string
    {
        return <<<'PROMPT'
- Baca seluruh isi artikel dan perbaiki kalimat berulang atau klise robotik.
- Pastikan target keyword tertanam alami tanpa keyword stuffing.
- Buat transisi antar paragraf mengalir halus seperti ditulis jurnalis profesional.
- Jaga semua tag link internal, link landing page, dan CTA penawaran tetap utuh.
PROMPT;
    }
}
