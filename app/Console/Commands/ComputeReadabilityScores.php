<?php

namespace App\Console\Commands;

use App\Models\Content;
use Illuminate\Console\Command;

class ComputeReadabilityScores extends Command
{
    protected $signature = 'seofast:compute-readability {--force} {--limit=500}';
    protected $description = 'Compute readability scores for published/draft content';

    public function handle(): int
    {
        $force = $this->option('force');
        $limit = (int) $this->option('limit');

        $query = Content::whereIn('status', ['published', 'draft'])
            ->whereNotNull('body_raw');

        if (!$force) {
            $query->whereNull('readability_score');
        }

        $contents = $query->orderByDesc('id')->limit($limit)->get();
        $this->info("Computing readability for {$contents->count()} content items...");
        $bar = $this->output->createProgressBar($contents->count());
        $bar->start();

        $updated = 0;
        foreach ($contents as $content) {
            $score = $this->computeReadability($content->body_raw);
            Content::withoutGlobalScopes()->where('id', $content->id)->update(['readability_score' => $score]);
            $updated++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("Done! {$updated} readability scores computed.");

        return Command::SUCCESS;
    }

    protected function computeReadability(?string $body): float
    {
        if (!$body) return 0;

        $text = strip_tags($body);
        $text = preg_replace('/!\[.*?\]\(.*?\)/', '', $text);
        $text = preg_replace('/\[.*?\]\(.*?\)/', '', $text);
        $text = preg_replace('/#{1,6}\s/', '', $text);
        $text = preg_replace('/[*_~`>|:-]/', '', $text);
        $text = trim(preg_replace('/\s+/', ' ', $text));

        if (empty($text) || strlen($text) < 50) return 0;

        $sentences = preg_split('/[.!?]+/', $text, -1, PREG_SPLIT_NO_EMPTY);
        $totalSentences = count($sentences);
        if ($totalSentences === 0) return 0;

        $words = preg_split('/\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);
        $totalWords = count($words);
        if ($totalWords === 0) return 0;

        $totalChars = mb_strlen(preg_replace('/\s+/', '', $text));

        $avgWordsPerSentence = $totalWords / $totalSentences;
        $avgCharsPerWord = $totalWords > 0 ? $totalChars / $totalWords : 0;

        // Score: lower avg sentence length + lower avg word length = higher readability
        // Scale: 100 - (words_per_sentence * 1.5 + chars_per_word * 8)
        // Typical ranges: wps=10-30, cpw=4-8
        $rawScore = 100 - ($avgWordsPerSentence * 1.5 + $avgCharsPerWord * 8);

        return round(max(0, min(100, $rawScore)), 2);
    }
}
