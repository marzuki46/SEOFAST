<?php

namespace App\Console\Commands;

use App\Models\BrokenLink;
use App\Models\Content;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ScanBrokenLinks extends Command
{
    protected $signature = 'seofast:scan-links {--limit=50} {--force}';
    protected $description = 'Scan published content for broken internal/external links';

    protected array $checked = [];

    public function handle(): int
    {
        $limit = (int) $this->option('limit');
        $force = $this->option('force');
        $client = new Client(['timeout' => 10, 'http_errors' => false, 'headers' => ['User-Agent' => 'SEOFAST-BrokenLinkChecker/1.0']]);

        $query = Content::where('status', 'published')->whereNotNull('body_raw');

        if (!$force) {
            // Only re-check links not checked in last 7 days
            $query->whereDoesntHave('brokenLinks', fn($q) => $q->where('checked_at', '>', now()->subDays(7)));
        }

        $contents = $query->orderBy('updated_at', 'desc')->limit($limit)->get();
        $isCli = $this->laravel->runningInConsole();
        $count = $contents->count();

        if ($isCli) {
            $this->info("Scanning {$count} published content items...");
            $bar = $this->output->createProgressBar($count);
            $bar->start();
        }

        $totalLinks = 0;
        $brokenCount = 0;

        foreach ($contents as $i => $content) {
            $links = $this->extractLinks($content->body_raw, $content);
            $totalLinks += count($links);

            foreach ($links as $link) {
                $urlHash = md5($link['url']);
                $alreadyChecked = in_array($urlHash, $this->checked);

                $statusCode = null;
                $error = null;
                $isBroken = false;
                $checkedAt = now();

                if (!$alreadyChecked) {
                    try {
                        $response = $client->head($link['url'], ['allow_redirects' => true]);
                        $statusCode = $response->getStatusCode();
                        if ($statusCode >= 400) {
                            $isBroken = true;
                            $brokenCount++;
                        }
                    } catch (ConnectException $e) {
                        $error = 'Connection failed: ' . $e->getMessage();
                        $isBroken = true;
                        $brokenCount++;
                    } catch (RequestException $e) {
                        $error = Str::limit($e->getMessage(), 250);
                        $isBroken = true;
                        $brokenCount++;
                    } catch (\Exception $e) {
                        $error = Str::limit($e->getMessage(), 250);
                        $isBroken = true;
                        $brokenCount++;
                    }
                    $this->checked[] = $urlHash;
                }

                BrokenLink::updateOrCreate(
                    ['content_id' => $content->id, 'url_hash' => $urlHash],
                    [
                        'url' => $link['url'],
                        'anchor_text' => $link['anchor_text'],
                        'link_type' => $link['link_type'],
                        'status_code' => $statusCode,
                        'error' => $error,
                        'is_broken' => $isBroken,
                        'checked_at' => $checkedAt,
                    ]
                );
            }

            if ($isCli) {
                $bar->advance();
            }
        }

        if ($isCli) {
            $bar->finish();
            $this->newLine(2);
        }

        $msg = "Done! {$totalLinks} links checked, {$brokenCount} broken found.";
        $this->info($msg);

        return Command::SUCCESS;
    }

    protected function extractLinks(string $body, Content $content): array
    {
        $links = [];
        $appUrl = config('app.url');

        // Markdown links: [text](url)
        preg_match_all('/\[([^\]]*)\]\(([^)]+)\)/', $body, $matches, PREG_SET_ORDER);
        foreach ($matches as $m) {
            $url = trim($m[2]);
            $anchor = trim($m[1]);
            if ($this->isValidUrl($url)) {
                $links[] = $this->makeLink($url, $anchor, $appUrl);
            }
        }

        // Markdown images: ![alt](url)
        preg_match_all('/!\[([^\]]*)\]\(([^)]+)\)/', $body, $matches, PREG_SET_ORDER);
        foreach ($matches as $m) {
            $url = trim($m[2]);
            if ($this->isValidUrl($url)) {
                $links[] = [
                    'url' => $url,
                    'anchor_text' => $m[1] ?: '[image]',
                    'link_type' => str_starts_with($url, $appUrl) || str_starts_with($url, '/') ? 'internal' : 'external',
                ];
            }
        }

        // Plain/inline <a href> tags (if any HTML in body)
        preg_match_all('/<a\s[^>]*href="([^"]+)"/i', $body, $matches, PREG_SET_ORDER);
        foreach ($matches as $m) {
            $url = trim($m[1]);
            if ($this->isValidUrl($url)) {
                $hash = md5($url);
                $exists = array_filter($links, fn($l) => md5($l['url']) === $hash);
                if (!$exists) {
                    $links[] = $this->makeLink($url, '', $appUrl);
                }
            }
        }

        // Resolve relative URLs
        foreach ($links as &$link) {
            if (str_starts_with($link['url'], '/')) {
                $link['url'] = $appUrl . $link['url'];
                $link['link_type'] = 'internal';
            }
        }

        return array_slice($links, 0, 50);
    }

    protected function isValidUrl(string $url): bool
    {
        if (empty($url)) return false;
        // Skip anchors, javascript, mailto, tel
        if (str_starts_with($url, '#') || str_starts_with($url, 'javascript:') || str_starts_with($url, 'mailto:') || str_starts_with($url, 'tel:')) {
            return false;
        }
        // Accept protocol URLs or relative paths
        return str_starts_with($url, 'http://') || str_starts_with($url, 'https://') || str_starts_with($url, '/');
    }

    protected function makeLink(string $url, string $anchor, string $appUrl): array
    {
        return [
            'url' => $url,
            'anchor_text' => $anchor ?: $url,
            'link_type' => str_starts_with($url, $appUrl) ? 'internal' : 'external',
        ];
    }
}
