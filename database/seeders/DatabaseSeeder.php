<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create 10 fake users
        $users = User::factory(10)->create();

        // Create 100 fake products and spread them across those 10 users
        Product::factory(100)
        ->recycle($users)
        ->create();
    }
}
