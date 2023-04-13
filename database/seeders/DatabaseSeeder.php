<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\Supplier::create([
            'name' => 'brazilian_provider',
            'api_base_url' => 'http://616d6bdb6dacbb001794ca17.mockapi.io/devnology/brazilian_provider'
        ]);

        \App\Models\Supplier::create([
            'name' => 'european_provider',
            'api_base_url' => 'http://616d6bdb6dacbb001794ca17.mockapi.io/devnology/european_provider'
        ]);
    }
}
