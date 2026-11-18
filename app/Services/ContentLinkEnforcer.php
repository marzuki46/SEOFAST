<?php

namespace App\Services;

use App\Models\Content;
use App\Models\SystemSetting;

class ContentLinkEnforcer
{
    /** Ensure publication output contains usable navigation and conversion links. */
    public function enforce(Content $content, string $html, ?string $ctaUrl = null, ?string $ctaLabel = null): string
    {
        $brandName = trim((string) SystemSetting::get('site_name', config('app.name', 'Juki')));
        $homeUrl = url('/');

        if (! $this->hasHref($html, $homeUrl)) {
            $brandLink = '<a href="'.e($homeUrl).'">'.e($brandName).'</a>';
            $html = $this->addToFirstParagraph($html, 'Temukan layanan dan panduan bisnis digital lainnya dari '.$brandLink.'.');
        }

        $campaign = $content->campaign;
        $landingUrl = $content->campaignTarget?->landing_url ?: $campaign?->destination_url;
        if ($landingUrl && $this->isHttpUrl($landingUrl) && ! $this->hasHref($html, $landingUrl)) {
            $label = $content->campaignTarget?->keyword ?: 'Lihat solusi yang relevan';
            $html .= '<p>Ingin menerapkan solusi ini? <a href="'.e($landingUrl).'">'.e($label).'</a> untuk bisnis Anda.</p>';
        }

        $relatedLinks = $this->relatedLinks($content);
        if ($relatedLinks !== '') {
            $html .= '<p><strong>Baca juga:</strong> '.$relatedLinks.'</p>';
        }

        if ($ctaUrl && $this->isHttpUrl($ctaUrl) && ! $this->hasHref($html, $ctaUrl)) {
            $html .= '<p><a href="'.e($ctaUrl).'">'.e(trim($ctaLabel ?: 'Konsultasikan kebutuhan Anda')).'</a></p>';
        }

        return $html;
    }

    private function relatedLinks(Content $content): string
    {
        if (! $content->campaign_id) {
            return '';
        }

        $stage = strtolower((string) ($content->contentTopic?->funnel_stage ?? 'tofu'));
        $allowedStages = match ($stage) {
            'mofu' => ['mofu', 'bofu'],
            'bofu' => ['bofu'],
            default => ['tofu', 'mofu'],
        };

        return Content::withoutGlobalScopes()
            ->where('campaign_id', $content->campaign_id)
            ->where('id', '<>', $content->id)
            ->where('status', 'published')
            ->whereHas('contentTopic', fn ($query) => $query->whereIn('funnel_stage', $allowedStages))
            ->latest('published_at')
            ->limit(3)
            ->get(['slug', 'meta_title', 'target_keyword'])
            ->map(fn (Content $item) => '<a href="'.e(url('/blog/'.$item->slug)).'">'.e($item->title ?: $item->target_keyword).'</a>')
            ->implode(' · ');
    }

    private function addToFirstParagraph(string $html, string $text): string
    {
        $updated = preg_replace('/(<p\b[^>]*>)/i', '$1'.$text.' ', $html, 1, $count);

        return $count > 0 ? (string) $updated : '<p>'.$text.'</p>'.$html;
    }

    private function hasHref(string $html, string $url): bool
    {
        return str_contains(html_entity_decode($html), $url);
    }

    private function isHttpUrl(string $url): bool
    {
        return (bool) filter_var($url, FILTER_VALIDATE_URL)
            && in_array(strtolower((string) parse_url($url, PHP_URL_SCHEME)), ['http', 'https'], true);
    }
}
