<?php

namespace App\Console\Commands;

use App\Models\Menu;
use Illuminate\Console\Command;

class FixPrimaryMenu extends Command
{
    protected $signature = 'seofast:fix-primary-menu';
    protected $description = 'Normalize primary menu item URLs to relative paths (fix seofast.test absolute links)';

    protected array $map = [
        'Home'       => '/',
        'Product'    => '/produk',
        'Blog'       => '/blog',
        'About Us'   => '/about-us',
        'Contact Us' => '/contact',
    ];

    public function handle(): int
    {
        $menu = Menu::where('location', 'primary')->with('items')->first();

        if (! $menu || $menu->items->isEmpty()) {
            $this->warn('Tidak ada menu primary yang ditemukan.');

            return Command::FAILURE;
        }

        foreach ($menu->items as $item) {
            if (isset($this->map[$item->title])) {
                $item->url = $this->map[$item->title];
                $item->save();
                $this->info("{$item->title} => {$item->url}");
            }
        }

        return Command::SUCCESS;
    }
}
