<?php

namespace App\Services;

use App\Models\Content;
use App\Models\SiloBlueprint;
use DOMDocument;

class WordPressExportService
{
    protected const NS_WP = 'http://wordpress.org/export/1.2/';
    protected const NS_CONTENT = 'http://purl.org/rss/1.0/modules/content/';
    protected const NS_DC = 'http://purl.org/dc/elements/1.1/';
    protected const NS_EXCERPT = 'http://wordpress.org/export/1.2/excerpt/';
    protected const NS_WFW = 'http://wellformedweb.org/CommentAPI/';

    public function export(array $options = []): string
    {
        $siteName = config('app.name');
        $siteUrl = url('/');

        $contents = Content::where('status', 'published')
            ->when($options['silo_id'] ?? null, fn($q, $id) => $q->where('silo_blueprint_id', $id))
            ->orderBy('published_at')
            ->get();

        $blueprints = SiloBlueprint::withCount('contents')->get();

        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $rss = $dom->createElement('rss');
        $rss->setAttribute('version', '2.0');
        $rss->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:excerpt', self::NS_EXCERPT);
        $rss->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:content', self::NS_CONTENT);
        $rss->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:wfw', self::NS_WFW);
        $rss->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:dc', self::NS_DC);
        $rss->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:wp', self::NS_WP);
        $dom->appendChild($rss);

        $channel = $dom->createElement('channel');
        $rss->appendChild($channel);

        $channel->appendChild($dom->createElement('title', $siteName));
        $channel->appendChild($dom->createElement('link', $siteUrl));
        $channel->appendChild($dom->createElement('description', $siteName . ' Blog Export'));
        $channel->appendChild($dom->createElement('language', 'id-ID'));

        $channel->appendChild($this->elementNS($dom, 'wp:wxr_version', '1.2', self::NS_WP));
        $channel->appendChild($this->elementNS($dom, 'wp:base_site_url', $siteUrl, self::NS_WP));
        $channel->appendChild($this->elementNS($dom, 'wp:base_blog_url', $siteUrl, self::NS_WP));

        foreach ($blueprints as $bp) {
            $cat = $this->elementNS($dom, 'wp:category', null, self::NS_WP);
            $cat->appendChild($this->elementNS($dom, 'wp:term_id', $bp->id, self::NS_WP));
            $cat->appendChild($this->elementNS($dom, 'wp:category_nicename', \Illuminate\Support\Str::slug($bp->silo_name), self::NS_WP));
            $cat->appendChild($this->elementNS($dom, 'wp:category_parent', '', self::NS_WP));
            $cat->appendChild($this->cdataElementNS($dom, 'wp:cat_name', $bp->silo_name, self::NS_WP));
            $channel->appendChild($cat);
        }

        foreach ($contents as $content) {
            $item = $dom->createElement('item');
            $channel->appendChild($item);

            $item->appendChild($this->cdataElement($dom, 'title', $content->title));
            $item->appendChild($dom->createElement('link', $siteUrl . '/blog/' . $content->slug));
            $item->appendChild($dom->createElement('pubDate', $content->published_at?->format('r') ?? date('r')));

            $creator = $this->elementNS($dom, 'dc:creator', 'admin', self::NS_DC);
            $item->appendChild($creator);

            $guid = $dom->createElement('guid', $siteUrl . '/?p=' . $content->id);
            $guid->setAttribute('isPermaLink', 'false');
            $item->appendChild($guid);

            $item->appendChild($dom->createElement('description', ''));
            $item->appendChild($this->cdataElementNS($dom, 'content:encoded', $content->body_raw ?? '', self::NS_CONTENT));
            $item->appendChild($this->cdataElementNS($dom, 'excerpt:encoded', $content->meta_description ?? '', self::NS_EXCERPT));

            $item->appendChild($this->elementNS($dom, 'wp:post_id', $content->id, self::NS_WP));
            $item->appendChild($this->elementNS($dom, 'wp:post_date', $content->published_at?->format('Y-m-d H:i:s') ?? now()->format('Y-m-d H:i:s'), self::NS_WP));
            $item->appendChild($this->elementNS($dom, 'wp:post_date_gmt', $content->published_at?->format('Y-m-d H:i:s') ?? now()->format('Y-m-d H:i:s'), self::NS_WP));
            $item->appendChild($this->elementNS($dom, 'wp:post_name', $content->slug, self::NS_WP));
            $item->appendChild($this->elementNS($dom, 'wp:status', 'publish', self::NS_WP));
            $item->appendChild($this->elementNS($dom, 'wp:post_type', 'post', self::NS_WP));
            $item->appendChild($this->elementNS($dom, 'wp:post_password', '', self::NS_WP));
            $item->appendChild($this->elementNS($dom, 'wp:is_sticky', '0', self::NS_WP));

            if ($content->silo_blueprint_id && ($bp = $blueprints->firstWhere('id', $content->silo_blueprint_id))) {
                $catChild = $dom->createElement('category', $bp->silo_name);
                $catChild->setAttribute('domain', 'category');
                $catChild->setAttribute('nicename', \Illuminate\Support\Str::slug($bp->silo_name));
                $item->appendChild($catChild);
            }

            if ($content->featured_image_url) {
                $meta = $this->elementNS($dom, 'wp:postmeta', null, self::NS_WP);
                $meta->appendChild($this->elementNS($dom, 'wp:meta_key', '_thumbnail_id', self::NS_WP));
                $meta->appendChild($this->elementNS($dom, 'wp:meta_value', 'img_' . $content->id, self::NS_WP));
                $item->appendChild($meta);
            }

            if ($content->meta_title) {
                $meta = $this->elementNS($dom, 'wp:postmeta', null, self::NS_WP);
                $meta->appendChild($this->elementNS($dom, 'wp:meta_key', '_yoast_wpseo_title', self::NS_WP));
                $meta->appendChild($this->cdataElementNS($dom, 'wp:meta_value', $content->meta_title, self::NS_WP));
                $item->appendChild($meta);
            }

            if ($content->meta_description) {
                $meta = $this->elementNS($dom, 'wp:postmeta', null, self::NS_WP);
                $meta->appendChild($this->elementNS($dom, 'wp:meta_key', '_yoast_wpseo_metadesc', self::NS_WP));
                $meta->appendChild($this->cdataElementNS($dom, 'wp:meta_value', $content->meta_description, self::NS_WP));
                $item->appendChild($meta);
            }
        }

        return $dom->saveXML();
    }

    protected function elementNS(DOMDocument $dom, string $qualifiedName, ?string $value, string $namespace): \DOMElement
    {
        $parts = explode(':', $qualifiedName);
        $localName = $parts[1] ?? $parts[0];

        if ($value === null) {
            return $dom->createElementNS($namespace, $localName);
        }
        return $dom->createElementNS($namespace, $localName, htmlspecialchars($value, ENT_QUOTES | ENT_XML1, 'UTF-8'));
    }

    protected function cdataElement(DOMDocument $dom, string $name, string $value): \DOMElement
    {
        $el = $dom->createElement($name);
        $el->appendChild($dom->createCDATASection($value));
        return $el;
    }

    protected function cdataElementNS(DOMDocument $dom, string $qualifiedName, string $value, string $namespace): \DOMElement
    {
        $parts = explode(':', $qualifiedName);
        $localName = $parts[1] ?? $parts[0];
        $el = $dom->createElementNS($namespace, $localName);
        $el->appendChild($dom->createCDATASection($value));
        return $el;
    }
}
