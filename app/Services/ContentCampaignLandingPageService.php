<?php

namespace App\Services;

use App\Models\ContentCampaignTarget;
use App\Models\Page;
use Illuminate\Support\Str;

class ContentCampaignLandingPageService
{
    public function create(ContentCampaignTarget $target): ContentCampaignTarget
    {
        $campaign = $target->campaign()->with(['product', 'tenant'])->firstOrFail();
        $campaign = app(ContentCampaignPromptService::class)->applyDefaults($campaign);
        $product = $campaign->product;
        $ai = new AIService($campaign->tenant, 'default');
        $fallbackTitle = Str::title($target->keyword);
        $landingPrompt = $this->clean($campaign->landing_prompt, 6000);
        $temporalContext = app(ContentCampaignPromptService::class)->temporalContext($campaign->timezone ?: config('app.timezone', 'UTC'));
        $result = $ai->generateJson(
            'Balas HANYA JSON valid. Jangan menghasilkan HTML, JavaScript, URL eksternal, klaim harga, atau fitur yang tidak ada di data bisnis.',
            Str::limit("{$temporalContext}\nBuat struktur landing page SEO untuk keyword target: {$target->keyword}\nProduk: {$product?->name}\nNiche: {$campaign->niche}\nData bisnis: {$campaign->business_data}\nArahan landing page tambahan:\n{$landingPrompt}\nFormat: {\"title\":\"...\",\"headline\":\"...\",\"subheadline\":\"...\",\"cta_label\":\"...\",\"benefits\":[\"...\"]}", 18000),
            ['temperature' => 0.5]
        ) ?? [];

        $title = $this->clean($result['title'] ?? $fallbackTitle, 255) ?: $fallbackTitle;
        $headline = $this->clean($result['headline'] ?? $title, 255) ?: $title;
        $subheadline = $this->clean($result['subheadline'] ?? $campaign->audience ?? '', 500);
        $ctaLabel = $this->clean($result['cta_label'] ?? $campaign->cta_label ?? 'Pelajari Selengkapnya', 120);
        $benefits = $this->cleanList($result['benefits'] ?? []);
        $slugBase = Str::slug($target->keyword) ?: 'landing-page';
        $slug = $slugBase;
        $suffix = 2;
        while (Page::where('slug', $slug)->exists()) {
            $slug = $slugBase.'-'.$suffix++;
        }

        $ctaUrl = $campaign->cta_url ?: ($product ? route('products.show', $product->slug) : '/contact');
        $safeBenefits = collect($benefits)->map(fn (string $benefit) => '<li>'.e($benefit).'</li>')->implode('');
        $html = '<section class="campaign-landing-page"><h1>'.e($headline).'</h1>'
            .'<p>'.e($subheadline).'</p>'
            .($safeBenefits !== '' ? '<ul>'.$safeBenefits.'</ul>' : '')
            .'<p><a href="'.e($ctaUrl).'">'.e($ctaLabel).'</a></p></section>';

        $page = Page::create([
            'title' => $title,
            'slug' => $slug,
            'template' => 'default',
            'html_content' => $html,
            'meta_title' => Str::limit($title, 255, ''),
            'meta_description' => Str::limit($subheadline ?: $title, 500, ''),
            'is_published' => true,
        ]);

        $target->update([
            'page_id' => $page->id,
            'status' => 'ready',
            'landing_url' => url('/'.$page->slug),
            'landing_data' => compact('title', 'headline', 'subheadline', 'ctaLabel', 'benefits'),
            'anchor_variations' => $this->anchors($target->keyword),
            'error_message' => null,
        ]);

        return $target->fresh(['page']);
    }

    private function anchors(string $keyword): array
    {
        return array_values(array_unique([
            $keyword,
            'informasi tentang '.$keyword,
            'solusi '.$keyword,
            'pelajari '.$keyword,
        ]));
    }

    private function clean(mixed $value, int $limit): string
    {
        return Str::limit(trim(strip_tags((string) $value)), $limit, '');
    }

    private function cleanList(mixed $values): array
    {
        if (! is_array($values)) {
            $values = preg_split('/[,\n]+/', (string) $values) ?: [];
        }

        return collect($values)->map(fn ($value) => $this->clean($value, 180))->filter()->unique()->take(8)->values()->all();
    }
}
