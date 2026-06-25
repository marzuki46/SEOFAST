<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Default Admin
        User::updateOrCreate(
            ['email' => 'admin@seofast.test'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'tenant_id' => null,
            ]
        );

        // Custom user requested
        User::updateOrCreate(
            ['email' => 'ohmjuki@gmail.com'],
            [
                'name' => 'Marzuki',
                'password' => Hash::make('Marzuk1'),
                'role' => 'admin',
                'tenant_id' => null,
            ]
        );

        $this->command->info('Admin users seeded successfully.');
    }
}