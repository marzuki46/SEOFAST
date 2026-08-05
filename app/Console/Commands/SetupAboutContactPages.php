<?php

namespace App\Console\Commands;

use App\Models\Page;
use Illuminate\Console\Command;

class SetupAboutContactPages extends Command
{
    protected $signature = 'seofast:setup-about-contact';
    protected $description = 'Setup the About Us page to use the juki-about navy+gold template with correct SEO meta';

    public function handle(): int
    {
        $about = Page::updateOrCreate(
            ['slug' => 'about-us'],
            [
                'title' => 'About Us',
                'template' => 'juki-about',
                'meta_title' => 'Tentang Juki Digital Marketing | Tri Marzuki — Web & App Developer, SEO Specialist',
                'meta_description' => 'Kenal lebih dekat Tri Marzuki, founder Juki Digital Marketing & Juki Website Developer Solo. AI Automation, Web & App Development, Digital Marketing, dan SEO specialist di Grogol, Sukoharjo, Jawa Tengah.',
                'is_published' => true,
            ]
        );

        $this->info("About Us page ({$about->id}) menggunakan template juki-about.");
        $this->warn('Halaman /contact dilayani contact.blade.php (sudah ikut git), tidak perlu perubahan DB.');

        return Command::SUCCESS;
    }
}
