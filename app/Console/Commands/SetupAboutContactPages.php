<?php

namespace App\Console\Commands;

use App\Models\Page;
use Illuminate\Console\Command;

class SetupAboutContactPages extends Command
{
    protected $signature = 'seofast:setup-about-contact';
    protected $description = 'Setup About Us pages (about-us & about) to use the juki-about navy+gold template with correct SEO meta';

    protected array $aboutPages = [
        'about-us' => 'About Us',
        'about'    => 'Tentang Saya — Tri Marzuki | Juki Digital Marketing',
    ];

    public function handle(): int
    {
        $metaTitle = 'Tentang Juki Digital Marketing | Tri Marzuki — Web & App Developer, SEO Specialist';
        $metaDescription = 'Kenal lebih dekat Tri Marzuki, founder Juki Digital Marketing & Juki Website Developer Solo. AI Automation, Web & App Development, Digital Marketing, dan SEO specialist di Grogol, Sukoharjo, Jawa Tengah.';

        foreach ($this->aboutPages as $slug => $title) {
            $page = Page::updateOrCreate(
                ['slug' => $slug],
                [
                    'title' => $title,
                    'template' => 'juki-about',
                    'meta_title' => $metaTitle,
                    'meta_description' => $metaDescription,
                    'is_published' => true,
                ]
            );

            $this->info("Page '{$slug}' ({$page->id}) menggunakan template juki-about.");
        }

        $this->warn('Halaman /contact dilayani contact.blade.php (sudah ikut git), tidak perlu perubahan DB.');

        return Command::SUCCESS;
    }
}
