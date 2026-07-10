<?php

namespace App\Services;

use App\Models\Content;
use App\Models\Media;
use App\Models\SiloBlueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class WordPressImportService
{
    protected array $stats = [
        'imported' => 0,
        'skipped' => 0,
        'images' => 0,
        'categories' => 0,
    ];

    protected array $categoryMap = [];
    protected array $mediaMap = [];
    protected ImageService $imageService;

    public function __construct()
    {
        $this->imageService = app(ImageService::class);
    }

    public function import(string $xmlContent, array $options = []): array
    {
        $xml = simplexml_load_string($xmlContent);
        if (!$xml) {
            throw new \RuntimeException('Invalid XML file.');
        }

        $namespaces = $xml->getNamespaces(true);
        $wp = $xml->channel->children($namespaces['wp'] ?? 'http://wordpress.org/export/1.2/');
        $content = $xml->channel->children($namespaces['content'] ?? 'http://purl.org/rss/1.0/modules/content/');

        $attachmentMap = $this->buildAttachmentUrlMap($xml->channel, $namespaces);

        DB::beginTransaction();
        try {
            $this->importCategories($xml->channel, $wp);

            if ($options['import_media_library'] ?? false) {
                $this->importAllAttachments($xml->channel, $namespaces);
            }

            $this->importPosts($xml->channel, $namespaces, $attachmentMap, $options);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $this->stats;
    }

    protected function importCategories(\SimpleXMLElement $channel, $wp): void
    {
        foreach ($wp->category as $cat) {
            $name = (string) $cat->cat_name;
            $slug = (string) $cat->category_nicename;

            if (empty($name)) continue;

            $blueprint = SiloBlueprint::firstOrCreate(
                ['silo_name' => $name],
                [
                    'slug' => $slug ?: Str::slug($name),
                    'content_framework' => 'default',
                    'content_tone' => 'formal',
                    'seed_keyword' => $name,
                ]
            );

            $this->categoryMap[(int) $cat->term_id] = $blueprint->id;
            $this->stats['categories']++;
        }
    }

    protected function importPosts(\SimpleXMLElement $channel, array $namespaces, array $attachmentMap, array $options): void
    {
        $wp = $channel->children($namespaces['wp'] ?? 'http://wordpress.org/export/1.2/');
        $contentNs = $namespaces['content'] ?? 'http://purl.org/rss/1.0/modules/content/';
        $excerpt = $namespaces['excerpt'] ?? 'http://wordpress.org/export/1.2/excerpt/';

        foreach ($channel->item as $item) {
            $itemWp = $item->children($namespaces['wp'] ?? 'http://wordpress.org/export/1.2/');
            $itemContent = $item->children($contentNs);
            $itemExcerpt = $item->children($excerpt);

            $postType = (string) $itemWp->post_type;
            $postStatus = (string) $itemWp->status;

            if (!in_array($postType, $options['post_types'] ?? ['post', 'page'])) continue;
            if ($postStatus === 'trash') continue;

            $slug = (string) $itemWp->post_name;
            if (empty($slug)) continue;

            $existing = Content::where('slug->id', $slug)->first();
            if ($existing && ($options['skip_existing'] ?? true)) {
                $this->stats['skipped']++;
                continue;
            }

            $title = strip_tags((string) $item->title);
            $body = (string) $itemContent->encoded;
            $excerptText = (string) $itemExcerpt->encoded;
            $postDate = (string) $itemWp->post_date_gmt ?: (string) $itemWp->post_date;
            $parentId = (int) $itemWp->post_parent;

            $meta = [];
            foreach ($itemWp->postmeta as $pm) {
                $meta[(string) $pm->meta_key] = (string) $pm->meta_value;
            }

            $body = $this->processMediaInContent($body, $meta);

            $status = match ($postStatus) {
                'publish' => 'published',
                'draft' => 'draft',
                'pending' => 'pending',
                'future' => 'draft',
                default => 'draft',
            };

            $contentData = [
                'silo_blueprint_id' => null,
                'target_keyword' => $title,
                'slug' => $slug,
                'meta_title' => $meta['_yoast_wpseo_title'] ?? $title,
                'meta_description' => $meta['_yoast_wpseo_metadesc'] ?? $excerptText,
                'body_raw' => $body,
                'status' => $status,
                'hierarchy_level' => 'cluster',
            ];

            if ($postDate && $postDate !== '0000-00-00 00:00:00') {
                $contentData['published_at'] = $postDate;
            }

            if (!empty($this->categoryMap)) {
                $cats = [];
                foreach ($item->category as $cat) {
                    $attrs = $cat->attributes('domain');
                    $domain = (string) $attrs;
                    if ($domain === 'category') {
                        $name = (string) $cat;
                        $bp = SiloBlueprint::where('silo_name', $name)->first();
                        if ($bp) {
                            $cats[] = $bp->id;
                        }
                    }
                }
                if (!empty($cats)) {
                    $contentData['silo_blueprint_id'] = $cats[0];
                }
            }

            if ($existing) {
                $existing->update($contentData);
            } else {
                $content = Content::create($contentData);
            }

            $this->importFeaturedImage($meta, $content ?? $existing, $attachmentMap);

            $this->stats['imported']++;
        }
    }

    protected function processMediaInContent(string $body, array $meta): string
    {
        $processed = [];
        $replacements = [];

        preg_match_all('/<img[^>]+src=["\']([^"\']+)["\'][^>]*>/i', $body, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $url = $match[1];

            if (isset($this->mediaMap[$url])) {
                $replacements[$url] = $this->mediaMap[$url];
                continue;
            }

            $result = $this->imageService->downloadAndProcess($url, 'wordpress-import');
            if ($result) {
                $localUrl = $result['url'];
                $this->mediaMap[$url] = $localUrl;
                $replacements[$url] = $localUrl;
                $this->stats['images']++;

                Media::create([
                    'filename' => basename($localUrl),
                    'path' => $result['path'],
                    'url' => $localUrl,
                    'size' => $result['size'],
                    'mime_type' => 'image/webp',
                ]);
            }
        }

        if (!empty($replacements)) {
            $body = str_replace(array_keys($replacements), array_values($replacements), $body);
        }

        return $body;
    }

    protected function importAllAttachments(\SimpleXMLElement $channel, array $namespaces): void
    {
        foreach ($channel->item as $item) {
            $itemWp = $item->children($namespaces['wp'] ?? 'http://wordpress.org/export/1.2/');
            $postType = (string) $itemWp->post_type;
            if ($postType !== 'attachment') continue;

            $postStatus = (string) $itemWp->status;
            if ($postStatus === 'trash') continue;

            $mimeType = (string) $itemWp->postmeta->meta_value;

            $url = (string) $item->guid;
            if (empty($url)) continue;

            if (isset($this->mediaMap[$url])) continue;

            $result = $this->imageService->downloadAndProcess($url, 'wordpress-media');
            if (!$result) continue;

            $this->mediaMap[$url] = $result['url'];
            $this->stats['images']++;

            $title = strip_tags((string) $item->title);

            Media::create([
                'filename' => basename($result['url']),
                'path' => $result['path'],
                'url' => $result['url'],
                'alt_text' => $title,
                'title' => $title,
                'size' => $result['size'],
                'mime_type' => 'image/webp',
            ]);
        }
    }

    protected function buildAttachmentUrlMap(\SimpleXMLElement $channel, array $namespaces): array
    {
        $map = [];
        $wp = $channel->children($namespaces['wp'] ?? 'http://wordpress.org/export/1.2/');

        foreach ($channel->item as $item) {
            $itemWp = $item->children($namespaces['wp'] ?? 'http://wordpress.org/export/1.2/');
            $postType = (string) $itemWp->post_type;
            if ($postType !== 'attachment') continue;

            $postId = (int) $itemWp->post_id;
            $url = (string) $item->guid;
            $parentId = (int) $itemWp->post_parent;

            $map[$postId] = [
                'url' => $url,
                'parent' => $parentId,
            ];
        }

        return $map;
    }

    protected function importFeaturedImage(array $meta, Content $content, array $attachmentMap): void
    {
        $thumbnailId = $meta['_thumbnail_id'] ?? null;
        if (!$thumbnailId) return;

        if (isset($this->mediaMap[$thumbnailId])) {
            $content->update(['featured_image_url' => $this->mediaMap[$thumbnailId]]);
            return;
        }

        $attUrl = $attachmentMap[$thumbnailId]['url'] ?? null;

        if ($attUrl) {
            $result = $this->imageService->downloadAndProcess($attUrl, 'featured-images');
            if ($result) {
                $content->update(['featured_image_url' => $result['url']]);
                $this->mediaMap[$thumbnailId] = $result['url'];
                $this->stats['images']++;
            }
        }
    }
}
