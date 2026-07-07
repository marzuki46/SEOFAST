<?php

namespace App\Console\Commands;

use App\Models\Content;
use Illuminate\Console\Command;

class CacheHtmlRender extends Command
{
    protected $signature = 'seofast:cache-html
                            {--force : Re-render even if rendered_html_path already exists}';

    protected $description = 'Backfill rendered_html_path for all content';

    public function handle(): int
    {
        $query = Content::whereNotNull('body_raw')->where('body_raw', '!=', '');

        if (!$this->option('force')) {
            $query->whereNull('rendered_html_path');
        }

        $total = $query->count();
        $bar = $this->output->createProgressBar($total);
        $bar->start();

        $processed = 0;
        $query->chunk(50, function ($contents) use ($bar, &$processed) {
            foreach ($contents as $content) {
                $hash = hash('sha256', $content->body_raw);

                if ($content->rendered_html_path && $content->content_hash === $hash) {
                    $bar->advance();
                    continue;
                }

                $html = $content->getHtmlBodyAttribute();

                $content->rendered_html_path = $html;
                $content->content_hash = $hash;
                $content->saveQuietly();

                $processed++;
                $bar->advance();
            }
        });

        $bar->finish();
        $this->newLine();
        $this->info("Done! {$processed} content(s) processed.");

        return Command::SUCCESS;
    }
}
