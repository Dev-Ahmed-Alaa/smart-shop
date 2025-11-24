<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('Seeding users...');
        // Create test user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
        ]);

        // Create additional users for testing
        User::factory(5)->create();

        $this->command->info('Seeding products...');
        $this->call(ProductSeeder::class);

        $this->command->info('Seeding product recommendations...');
        $this->call(ProductRecommendationSeeder::class);

        $this->command->info('Database seeding completed successfully!');
        $this->command->info('Login credentials: user@example.com / password');
    }
}
