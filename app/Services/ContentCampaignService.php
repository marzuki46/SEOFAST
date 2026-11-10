<?php

namespace App\Services;

use App\Models\Content;
use App\Models\ContentCampaign;
use App\Models\ContentCampaignTarget;
use App\Models\ContentTopic;
use Illuminate\Support\Str;

class ContentCampaignService
{
    public function generateTopics(ContentCampaign $campaign, int $count, ?string $funnelStage = null, ?ContentCampaignTarget $target = null): array
    {
        // Resolve credentials for the campaign tenant, rather than relying on a
        // container-wide AI client that could use the wrong tenant settings.
        $ai = new AIService($campaign->tenant, 'keyword');
        $count = max(1, min(20, $count));
        $stage = in_array($funnelStage, ['tofu', 'mofu', 'bofu'], true) ? $funnelStage : null;
        $existing = $this->existingContext($campaign);
        $productName = $campaign->product?->name ?? 'produk digital';
        $targets = $target ? collect([$target]) : $campaign->targets()->whereIn('status', ['pending', 'generating', 'ready'])->get();
        $targetText = $targets->map(fn (ContentCampaignTarget $item) => '- '.$item->keyword)->implode("\n") ?: '- belum ditentukan';
        $siloName = $campaign->siloBlueprint?->silo_name ?? $productName;
        $stageInstruction = $stage
            ? 'Semua ide wajib berada pada funnel '.$stage.'.'
            : 'Distribusikan ide secara seimbang antara TOFU, MOFU, dan BOFU.';

        $system = 'Anda adalah SEO content strategist. Balas HANYA JSON array valid. Jangan mengarang data harga, fitur, klaim, atau fakta yang tidak ada di data bisnis.';
        $prompt = "Buat {$count} ide konten BARU untuk campaign produk {$productName}.\n"
            ."Silo: {$siloName}\nBahasa: {$campaign->language}\nAudiens: {$campaign->audience}\n"
            ."Entity seed: {$campaign->entity_seed}\nTarget landing page:\n{$targetText}\nData bisnis: {$campaign->business_data}\n"
            ."{$stageInstruction}\n\nArtikel/keyword yang sudah ada (jangan ulangi atau mirip):\n{$existing}\n\n"
            ."TOFU = edukasi masalah dan definisi. MOFU = perbandingan, evaluasi, studi kasus. BOFU = keputusan, implementasi, harga/CTA bila datanya tersedia.\n"
            .'Format setiap item: {"target_keyword":"pilih salah satu target di atas","title":"...","primary_keyword":"...","intent":"informational|commercial|transactional","funnel_stage":"tofu|mofu|bofu","angle":"...","entity_keywords":["..."]}';

        $items = $ai->generateJson($system, Str::limit($prompt, 18000), ['temperature' => 0.7]) ?? [];
        $created = [];
        foreach (is_array($items) ? $items : [] as $item) {
            if (! is_array($item)) {
                continue;
            }
            $title = $this->clean($item['title'] ?? '', 255);
            $keyword = $this->clean($item['primary_keyword'] ?? $title, 191);
            if ($title === '' || $keyword === '') {
                continue;
            }
            $normalized = $this->normalize($keyword);
            if ($this->isDuplicate($campaign, $normalized, $title)) {
                continue;
            }

            $itemStage = strtolower($this->clean($item['funnel_stage'] ?? $stage ?? 'tofu', 10));
            if (! in_array($itemStage, ['tofu', 'mofu', 'bofu'], true)) {
                $itemStage = $stage ?? 'tofu';
            }
            $itemTarget = $target ?: $this->matchTarget($targets, $item['target_keyword'] ?? null);
            $created[] = ContentTopic::create([
                'tenant_id' => $campaign->tenant_id,
                'campaign_id' => $campaign->id,
                'campaign_target_id' => $itemTarget?->id,
                'product_id' => $campaign->product_id,
                'silo_blueprint_id' => $campaign->silo_blueprint_id,
                'funnel_stage' => $itemStage,
                'title' => $title,
                'primary_keyword' => $keyword,
                'normalized_keyword' => $normalized,
                'intent' => $this->clean($item['intent'] ?? 'informational', 50),
                'angle' => $this->clean($item['angle'] ?? '', 2000),
                'entity_keywords' => $this->cleanKeywords($item['entity_keywords'] ?? []),
                'status' => $itemTarget ? 'idea' : 'rejected',
                'source' => 'ai_research',
                'rejection_reason' => $itemTarget ? null : 'AI tidak mengembalikan target landing page yang valid.',
            ]);
        }

        return $created;
    }

    private function matchTarget($targets, mixed $keyword): ?ContentCampaignTarget
    {
        $normalized = $this->normalize((string) $keyword);

        return $targets->first(fn (ContentCampaignTarget $target) => $target->normalized_keyword === $normalized)
            ?: $targets->first();
    }

    public function normalize(string $value): string
    {
        $value = Str::lower(Str::ascii($value));
        $value = preg_replace('/[^a-z0-9]+/', ' ', $value) ?? '';

        return trim(preg_replace('/\s+/', ' ', $value) ?? '');
    }

    public function isDuplicate(ContentCampaign $campaign, string $normalizedKeyword, string $title): bool
    {
        if ($normalizedKeyword === '') {
            return true;
        }
        $query = ContentTopic::withoutGlobalScopes()->where('tenant_id', $campaign->tenant_id)
            ->where('normalized_keyword', $normalizedKeyword)
            ->whereNotIn('status', ['rejected']);
        if ($query->exists()) {
            return true;
        }

        $contentKeywords = Content::withoutGlobalScopes()->where('tenant_id', $campaign->tenant_id)
            ->whereNotNull('target_keyword')->pluck('target_keyword');
        $titleNormalized = $this->normalize($title);
        foreach ($contentKeywords as $existing) {
            if ($this->similarity($normalizedKeyword, $this->normalize((string) $existing)) >= 0.72
                || $this->similarity($titleNormalized, $this->normalize((string) $existing)) >= 0.72) {
                return true;
            }
        }

        return false;
    }

    public function similarity(string $a, string $b): float
    {
        $left = array_values(array_unique(array_filter(explode(' ', $a))));
        $right = array_values(array_unique(array_filter(explode(' ', $b))));
        if (! $left || ! $right) {
            return 0.0;
        }
        $intersection = count(array_intersect($left, $right));

        return $intersection / count(array_unique(array_merge($left, $right)));
    }

    private function existingContext(ContentCampaign $campaign): string
    {
        $topics = ContentTopic::withoutGlobalScopes()->where('tenant_id', $campaign->tenant_id)
            ->whereIn('status', ['idea', 'processing', 'used'])->latest()->limit(60)->pluck('primary_keyword')->all();
        $contents = Content::withoutGlobalScopes()->where('tenant_id', $campaign->tenant_id)
            ->whereNotNull('target_keyword')->latest()->limit(60)->pluck('target_keyword')->all();

        return collect(array_merge($topics, $contents))->unique()->map(fn ($v) => '- '.$v)->implode("\n") ?: '- belum ada';
    }

    private function clean(mixed $value, int $limit): string
    {
        return Str::limit(trim(strip_tags((string) $value)), $limit, '');
    }

    private function cleanKeywords(mixed $keywords): array
    {
        if (! is_array($keywords)) {
            $keywords = preg_split('/[,\n]+/', (string) $keywords) ?: [];
        }

        return collect($keywords)->map(fn ($v) => $this->clean($v, 120))->filter()->unique()->take(12)->values()->all();
    }
}
