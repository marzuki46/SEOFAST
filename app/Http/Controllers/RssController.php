<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\SystemSetting;
use Illuminate\Http\Response;

class RssController extends Controller
{
    /**
     * Render RSS 2.0 Feed for blog articles.
     */
    public function feed(): Response
    {
        $siteName = SystemSetting::get('site_name', config('app.name', 'Juki'));
        $siteDescription = SystemSetting::get('site_description', 'Layanan Jasa Pembuatan Website, SEO, Otomasi & AI');
        $siteUrl = url('/');
        $feedUrl = url('/feed');

        $posts = Content::withoutGlobalScopes()
            ->where('status', 'published')
            ->whereNull('deleted_at')
            ->latest('published_at')
            ->limit(50)
            ->get();

        $lastBuildDate = $posts->first()?->published_at?->toRssString() ?? now()->toRssString();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:content="http://purl.org/rss/1.0/modules/content/">' . "\n";
        $xml .= '<channel>' . "\n";
        $xml .= '  <title>' . htmlspecialchars($siteName, ENT_XML1) . '</title>' . "\n";
        $xml .= '  <link>' . htmlspecialchars($siteUrl, ENT_XML1) . '</link>' . "\n";
        $xml .= '  <description>' . htmlspecialchars($siteDescription, ENT_XML1) . '</description>' . "\n";
        $xml .= '  <language>id-ID</language>' . "\n";
        $xml .= '  <lastBuildDate>' . $lastBuildDate . '</lastBuildDate>' . "\n";
        $xml .= '  <atom:link href="' . htmlspecialchars($feedUrl, ENT_XML1) . '" rel="self" type="application/rss+xml" />' . "\n";
        $xml .= '  <atom:link rel="hub" href="https://pubsubhubbub.appspot.com/" />' . "\n";

        foreach ($posts as $post) {
            $postUrl = url('/blog/' . $post->slug);
            $pubDate = ($post->published_at ?? $post->created_at)?->toRssString() ?? now()->toRssString();
            $title = $post->meta_title ?: ($post->title ?: $post->target_keyword);
            $description = $post->meta_description ?: strip_tags(substr($post->body_raw ?? '', 0, 300));

            $xml .= '  <item>' . "\n";
            $xml .= '    <title>' . htmlspecialchars($title, ENT_XML1) . '</title>' . "\n";
            $xml .= '    <link>' . htmlspecialchars($postUrl, ENT_XML1) . '</link>' . "\n";
            $xml .= '    <guid isPermaLink="true">' . htmlspecialchars($postUrl, ENT_XML1) . '</guid>' . "\n";
            $xml .= '    <pubDate>' . $pubDate . '</pubDate>' . "\n";
            $xml .= '    <description><![CDATA[' . $description . ']]></description>' . "\n";
            $xml .= '  </item>' . "\n";
        }

        $xml .= '</channel>' . "\n";
        $xml .= '</rss>';

        return response($xml, 200, [
            'Content-Type' => 'application/rss+xml; charset=utf-8',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }
}
