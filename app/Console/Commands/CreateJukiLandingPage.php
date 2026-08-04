<?php

namespace App\Console\Commands;

use App\Models\Page;
use Illuminate\Console\Command;

class CreateJukiLandingPage extends Command
{
    protected $signature = 'seofast:create-juki-landing {--slug=landing-juki : URL slug untuk halaman landing}';
    protected $description = 'Create the Juki animated landing page using the juki-landing template';

    public function handle(): int
    {
        $slug = (string) $this->option('slug');
        $slug = trim($slug, '/');

        $page = Page::updateOrCreate(
            ['slug' => $slug],
            [
                'title' => 'Juki Website Developer Solo - Jasa Pembuatan Website, Sistem & AI Automation Profesional',
                'template' => 'juki-landing',
                'meta_title' => 'Juki Website Developer Solo - Jasa Pembuatan Website, Sistem & AI Automation Profesional',
                'meta_description' => 'Juki Website Developer Solo: Ahli pembuatan website company profile, toko online, sistem informasi ERP/CRM, AI automation chatbot, dan jasa backlink pyramid aman. Layanan SEO Surakarta, Jawa Tengah. Konsultasi gratis!',
                'is_published' => true,
            ]
        );

        $this->info("Juki landing page created: /{$page->slug}");
        $this->warn('Buka halaman tersebut untuk melihat template juki-landing.');

        return Command::SUCCESS;
    }
}
