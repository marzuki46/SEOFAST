<?php

namespace App\Console\Commands;

use App\Models\Content;
use Illuminate\Console\Command;

class AuditUrls extends Command
{
    protected $signature = 'seofast:audit-urls {--limit=500}';
    protected $description = 'Audit URL structure of published content';

    public function handle(): int
    {
        $limit = (int) $this->option('limit');
        $contents = Content::whereIn('status', ['published', 'draft'])
            ->whereNotNull('slug')
            ->orderByDesc('id')
            ->limit($limit)
            ->get(['id', 'meta_title', 'slug', 'target_keyword', 'status']);

        $results = [];
        $stopWords = ['dan', 'di', 'ke', 'dari', 'yang', 'ini', 'itu', 'dengan', 'untuk', 'pada', 'adalah',
            'akan', 'telah', 'sudah', 'bisa', 'dapat', 'tidak', 'ada', 'juga', 'atau', 'serta',
            'the', 'and', 'is', 'in', 'it', 'of', 'to', 'a', 'an', 'for', 'on', 'with', 'as'];

        foreach ($contents as $content) {
            $slug = $content->slug;
            $segments = explode('/', $slug);
            $lastSegment = end($segments);
            $depth = count($segments);
            $slugLength = strlen($lastSegment);
            $hasNumbers = preg_match('/\d/', $lastSegment);
            $hasUppercase = $lastSegment !== strtolower($lastSegment);
            $hasUnderscores = str_contains($lastSegment, '_');
            $hasDoubleHyphens = str_contains($lastSegment, '--');
            $segmentWords = explode('-', $lastSegment);
            $stopWordsInSlug = array_intersect($segmentWords, $stopWords);
            $keywordInSlug = $content->target_keyword
                ? str_contains(strtolower($slug), strtolower(str_replace(' ', '-', $content->target_keyword)))
                : null;

            $issues = [];
            if ($depth > 3) $issues[] = 'Deep URL';
            if ($slugLength > 60) $issues[] = 'Long slug';
            if ($hasNumbers) $issues[] = 'Contains numbers';
            if ($hasUppercase) $issues[] = 'Has uppercase';
            if ($hasUnderscores) $issues[] = 'Has underscores';
            if ($hasDoubleHyphens) $issues[] = 'Double hyphens';
            if (!empty($stopWordsInSlug)) $issues[] = 'Stop words in slug';
            if ($keywordInSlug === false && $content->target_keyword) $issues[] = 'Keyword not in URL';

            $score = 100;
            if ($depth > 3) $score -= 15;
            if ($slugLength > 60) $score -= 15;
            if ($slugLength > 80) $score -= 10;
            if ($hasNumbers) $score -= 10;
            if ($hasUppercase) $score -= 10;
            if ($hasUnderscores) $score -= 15;
            if ($hasDoubleHyphens) $score -= 10;
            if (!empty($stopWordsInSlug)) $score -= 10;
            if ($keywordInSlug === false && $content->target_keyword) $score -= 20;

            $results[] = [
                'content_id' => $content->id,
                'title' => $content->title,
                'slug' => $content->slug,
                'target_keyword' => $content->target_keyword,
                'status' => $content->status,
                'depth' => $depth,
                'slug_length' => $slugLength,
                'keyword_in_url' => $keywordInSlug === true ? true : ($keywordInSlug === false ? false : null),
                'issues' => $issues,
                'score' => max(0, $score),
            ];
        }

        $total = count($results);
        $avgScore = $total > 0 ? round(array_sum(array_column($results, 'score')) / $total, 1) : 0;
        $goodUrls = count(array_filter($results, fn($r) => $r['score'] >= 80));
        $needsWork = count(array_filter($results, fn($r) => $r['score'] < 80 && $r['score'] >= 50));
        $poor = count(array_filter($results, fn($r) => $r['score'] < 50));
        $totalIssues = array_sum(array_map(fn($r) => count($r['issues']), $results));

        $this->table(
            ['ID', 'Slug', 'Depth', 'Len', 'Score', 'Issues'],
            collect($results)->sortBy('score')->take(20)->map(fn($r) => [
                $r['content_id'],
                substr($r['slug'], 0, 40),
                $r['depth'],
                $r['slug_length'],
                $r['score'],
                implode(', ', array_slice($r['issues'], 0, 3)),
            ])
        );

        $this->newLine();
        $this->info("URL Audit Summary:");
        $this->info("  Total URLs: {$total}");
        $this->info("  Avg Score: {$avgScore}/100");
        $this->info("  Good (≥80): {$goodUrls}");
        $this->info("  Needs Work (50-79): {$needsWork}");
        $this->info("  Poor (<50): {$poor}");
        $this->info("  Total Issues: {$totalIssues}");

        // Store in cache for the admin view
        cache()->forever('url_audit_results', $results);
        cache()->forever('url_audit_summary', [
            'total' => $total,
            'avg_score' => $avgScore,
            'good' => $goodUrls,
            'needs_work' => $needsWork,
            'poor' => $poor,
            'total_issues' => $totalIssues,
            'audited_at' => now(),
        ]);

        return Command::SUCCESS;
    }
}
