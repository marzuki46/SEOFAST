<?php

declare(strict_types=1);

namespace App\Services;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMXPath;

final class HtmlSanitizer
{
    private const ALLOWED_TAGS = [
        'a', 'article', 'aside', 'blockquote', 'br', 'button', 'code', 'div', 'em',
        'figcaption', 'figure', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'hr', 'img',
        'li', 'ol', 'p', 'pre', 'section', 'small', 'span', 'strong', 'svg', 'path',
        'table', 'tbody', 'td', 'th', 'thead', 'tr', 'u', 'ul',
    ];

    private const ALLOWED_ATTRIBUTES = [
        'alt', 'aria-hidden', 'aria-label', 'class', 'colspan', 'd', 'decoding',
        'fill', 'height', 'href', 'id', 'loading', 'rel', 'role', 'rowspan', 'src',
        'stroke', 'stroke-linecap', 'stroke-linejoin', 'stroke-width', 'target',
        'title', 'viewbox', 'width',
    ];

    public function sanitize(string $html): string
    {
        if ($html === '' || ! class_exists(DOMDocument::class)) {
            return strip_tags($html, '<a><article><aside><blockquote><br><button><code><div><em><figcaption><figure><h1><h2><h3><h4><h5><h6><hr><img><li><ol><p><pre><section><small><span><strong><svg><path><table><tbody><td><th><thead><tr><u><ul>');
        }

        $document = new DOMDocument('1.0', 'UTF-8');
        $previous = libxml_use_internal_errors(true);
        $document->loadHTML(
            '<?xml encoding="UTF-8"><div id="seofast-sanitize-root">'.$html.'</div>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $xpath = new DOMXPath($document);
        foreach (iterator_to_array($xpath->query('//*') ?: []) as $element) {
            if (! $element instanceof DOMElement || $element->getAttribute('id') === 'seofast-sanitize-root') {
                continue;
            }

            $tag = strtolower($element->tagName);
            if (! in_array($tag, self::ALLOWED_TAGS, true)) {
                $this->removeElement($element, in_array($tag, ['script', 'style', 'iframe', 'object', 'embed'], true));

                continue;
            }

            foreach (iterator_to_array($element->attributes) as $attribute) {
                $name = strtolower($attribute->name);
                if (! in_array($name, self::ALLOWED_ATTRIBUTES, true)
                    || str_starts_with($name, 'on')
                    || (in_array($name, ['href', 'src'], true) && ! $this->isSafeUrl($attribute->value))) {
                    $element->removeAttribute($attribute->name);
                }
            }

            if ($tag === 'a' && $element->getAttribute('target') === '_blank') {
                $element->setAttribute('rel', 'noopener noreferrer');
            }
        }

        $root = $document->getElementById('seofast-sanitize-root');
        if (! $root) {
            return '';
        }

        $result = '';
        foreach ($root->childNodes as $node) {
            $result .= $document->saveHTML($node);
        }

        return $result;
    }

    private function isSafeUrl(string $url): bool
    {
        $value = html_entity_decode(trim($url), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        if ($value === '' || str_starts_with($value, '/') || str_starts_with($value, '#')) {
            return true;
        }

        $scheme = strtolower((string) parse_url($value, PHP_URL_SCHEME));

        if (! in_array($scheme, ['http', 'https', 'mailto', 'tel'], true)) {
            return false;
        }

        // AI/imported content must not silently redirect visitors to an
        // unrelated domain. Relative URLs and this application's own host are
        // safe; external hosts must be explicitly allowlisted in config.
        if (in_array($scheme, ['http', 'https'], true)) {
            $host = strtolower((string) parse_url($value, PHP_URL_HOST));
            $appUrl = (string) config('app.url');
            $appHost = strtolower((string) parse_url($appUrl !== '' ? $appUrl : url('/'), PHP_URL_HOST));
            $allowedHosts = array_map('strtolower', (array) config('content.allowed_external_hosts', []));

            if ($host !== $appHost && ! in_array($host, $allowedHosts, true)) {
                return false;
            }
        }

        return true;
    }

    private function removeElement(DOMElement $element, bool $removeChildren): void
    {
        $parent = $element->parentNode;
        if (! $parent instanceof DOMNode) {
            return;
        }

        if (! $removeChildren) {
            while ($element->firstChild) {
                $parent->insertBefore($element->firstChild, $element);
            }
        }

        $parent->removeChild($element);
    }
}
