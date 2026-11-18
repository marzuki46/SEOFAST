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

        $contextualLinks = [];
        $campaign = $content->campaign;
        $landingUrl = $content->campaignTarget?->landing_url ?: $campaign?->destination_url;
        if ($landingUrl && $this->isHttpUrl($landingUrl) && ! $this->hasHref($html, $landingUrl)) {
            $label = $content->campaignTarget?->keyword ?: 'Lihat solusi yang relevan';
            $contextualLinks[] = 'Jika Anda sedang menilai kebutuhan serupa, lihat <a href="'.e($landingUrl).'">'.e($label).'</a> untuk bisnis Anda.';
        }

        if ($contextualLinks !== []) {
            $html = $this->addAfterFirstParagraph($html, implode(' ', $contextualLinks));
        }

        if ($ctaUrl && $this->isHttpUrl($ctaUrl) && ! $this->hasHref($html, $ctaUrl)) {
            $cta = '<a href="'.e($ctaUrl).'">'.e(trim($ctaLabel ?: 'Konsultasikan kebutuhan Anda')).'</a>';
            $updated = preg_replace_callback(
                '/(hubungi kami via whatsapp|konsultasi(?:kan)? kebutuhan(?: anda)?(?: via whatsapp)?|konsultasi sekarang via whatsapp)/iu',
                static fn () => $cta,
                $html,
                1,
                $count
            );
            $html = $count > 0 ? (string) $updated : $html.'<p>'.$cta.'</p>';
        }

        return $html;
    }

    private function addToFirstParagraph(string $html, string $text): string
    {
        $updated = preg_replace('/(<p\b[^>]*>)/i', '$1'.$text.' ', $html, 1, $count);

        return $count > 0 ? (string) $updated : '<p>'.$text.'</p>'.$html;
    }

    private function addAfterFirstParagraph(string $html, string $text): string
    {
        $paragraph = '<p>'.$text.'</p>';
        $updated = preg_replace('/(<\/p>)/i', '$1'.$paragraph, $html, 1, $count);

        return $count > 0 ? (string) $updated : $html.$paragraph;
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
