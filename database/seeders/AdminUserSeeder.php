<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command?->warn('AdminUserSeeder tidak membuat akun bawaan. Buat admin melalui installer atau panel admin.');
    }
}
