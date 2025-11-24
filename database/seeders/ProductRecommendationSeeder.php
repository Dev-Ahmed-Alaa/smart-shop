<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductRecommendation;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductRecommendationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $products = Product::all();

        if ($products->isEmpty()) {
            $this->command->warn('No products found. Please run ProductSeeder first.');

            return;
        }

        // Create some AI-generated recommendations
        for ($i = 0; $i < 10; $i++) {
            $viewedProducts = $products->random(3)->pluck('id')->toArray();
            $recommendedProducts = $products->random(3)->pluck('id')->toArray();

            ProductRecommendation::create([
                'viewed_product_ids' => $viewedProducts,
                'recommended_product_ids' => $recommendedProducts,
                'is_ai_generated' => true,
                'ai_prompt' => 'Based on these viewed products, suggest 3 similar products from this product list.',
                'ai_response' => implode("\n", $recommendedProducts),
                'session_id' => fake()->uuid(),
                'user_id' => $users->isNotEmpty() ? $users->random()->id : null,
            ]);
        }

        // Create some random fallback recommendations
        for ($i = 0; $i < 5; $i++) {
            $viewedProducts = $products->random(2)->pluck('id')->toArray();
            $recommendedProducts = $products->random(3)->pluck('id')->toArray();

            ProductRecommendation::create([
                'viewed_product_ids' => $viewedProducts,
                'recommended_product_ids' => $recommendedProducts,
                'is_ai_generated' => false,
                'session_id' => fake()->uuid(),
                'user_id' => $users->isNotEmpty() ? $users->random()->id : null,
            ]);
        }

        $this->command->info('Created 15 product recommendations (10 AI-generated, 5 random fallback).');
    }
}
