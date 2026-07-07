<?php

namespace App\Console\Commands;

use App\Models\CanonicalMapping;
use App\Models\Content;
use Illuminate\Console\Command;

class DetectDuplicateContent extends Command
{
    protected $signature = 'seofast:detect-duplicates {--threshold=0.6} {--limit=200}';
    protected $description = 'Detect duplicate or near-duplicate published content using word overlap';

    public function handle(): int
    {
        $threshold = (float) $this->option('threshold');
        $limit = (int) $this->option('limit');

        $contents = Content::whereIn('status', ['published', 'draft'])
            ->whereNotNull('body_raw')
            ->orderByDesc('id')
            ->limit($limit)
            ->get(['id', 'tenant_id', 'target_keyword', 'body_raw']);

        $isCli = $this->laravel->runningInConsole();
        $count = $contents->count();

        if ($isCli) {
            $this->info("Comparing {$count} content items...");
            $bar = $this->output->createProgressBar($count);
            $bar->start();
        }

        $detected = 0;
        $processed = [];

        foreach ($contents as $i => $a) {
            $tokensA = $this->tokenize($a->body_raw);
            if (count($tokensA) < 10) {
                if ($isCli) $bar->advance();
                continue;
            }

            foreach ($contents as $b) {
                if ($a->id >= $b->id) continue;
                if (isset($processed[$a->id][$b->id])) continue;

                $tokensB = $this->tokenize($b->body_raw);
                if (count($tokensB) < 10) continue;

                $similarity = $this->jaccardSimilarity($tokensA, $tokensB);

                if ($similarity >= $threshold) {
                    CanonicalMapping::updateOrCreate(
                        ['content_id' => $a->id, 'canonical_target_id' => $b->id],
                        [
                            'tenant_id' => $a->tenant_id,
                            'similarity_score' => $similarity,
                            'reason' => 'duplicate_intent',
                            'is_resolved' => false,
                        ]
                    );
                    $processed[$a->id][$b->id] = true;
                    $detected++;
                }
            }

            if ($isCli) $bar->advance();
        }

        if ($isCli) {
            $bar->finish();
            $this->newLine(2);
        }

        $this->info("Done! {$detected} similar pairs detected (threshold: {$threshold}).");

        return Command::SUCCESS;
    }

    protected function tokenize(?string $text): array
    {
        if (!$text) return [];
        $text = strip_tags($text);
        $text = strtolower($text);
        $words = preg_split('/\W+/u', $text, -1, PREG_SPLIT_NO_EMPTY);
        $stopWords = ['dan', 'di', 'ke', 'dari', 'yang', 'ini', 'itu', 'dengan', 'untuk', 'pada', 'adalah',
            'akan', 'telah', 'sudah', 'bisa', 'dapat', 'tidak', 'ada', 'juga', 'atau', 'serta',
            'the', 'and', 'is', 'in', 'it', 'of', 'to', 'a', 'an', 'for', 'on', 'with', 'as',
            'by', 'at', 'from', 'or', 'be', 'are', 'was', 'were', 'been', 'being'];
        return array_diff($words, $stopWords);
    }

    protected function jaccardSimilarity(array $a, array $b): float
    {
        $setA = array_unique($a);
        $setB = array_unique($b);
        $intersection = count(array_intersect($setA, $setB));
        $union = count(array_unique(array_merge($setA, $setB)));
        return $union > 0 ? $intersection / $union : 0;
    }
}
