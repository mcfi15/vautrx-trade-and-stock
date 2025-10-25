<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Cryptocurrency;
use App\Models\TradingPair;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {

        // Create test user
        // User::create([
        //     'name' => 'Test User',
        //     'email' => 'user@test.com',
        //     'password' => Hash::make('password'),
        //     'is_admin' => false,
        //     'is_active' => true,
        // ]);

        


        $this->call([
            AdminSeeder::class,
            CryptocurrencySeeder::class,
            // Add other seeders here
        ]);
    }
}
