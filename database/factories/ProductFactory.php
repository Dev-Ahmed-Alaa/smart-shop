<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = [
            'Electronics',
            'Clothing',
            'Home & Garden',
            'Sports',
            'Books',
            'Toys',
            'Beauty',
            'Automotive',
            'Food & Beverages',
            'Health',
        ];

        $category = fake()->randomElement($categories);

        // Predefined list of product images for consistent display
        $productImages = [
            'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400&h=400&fit=crop', // Headphones
            'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=400&h=400&fit=crop', // Watch
            'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=400&h=400&fit=crop', // Shoes
            'https://images.unsplash.com/photo-1546868871-7041f2a55e12?w=400&h=400&fit=crop', // Smartphone
            'https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=400&h=400&fit=crop', // Sunglasses
            'https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=400&h=400&fit=crop', // Laptop
            'https://images.unsplash.com/photo-1503602642458-232111445657?w=400&h=400&fit=crop', // Camera
            'https://images.unsplash.com/photo-1572569511254-d8f925fe2cbb?w=400&h=400&fit=crop', // Speaker
            'https://images.unsplash.com/photo-1586495777744-4413f21062fa?w=400&h=400&fit=crop', // Keyboard
            'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=400&h=400&fit=crop', // Mouse
            'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=400&h=400&fit=crop', // Jacket
            'https://images.unsplash.com/photo-1551028719-00167b16eac5?w=400&h=400&fit=crop', // Bag
            'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=400&h=400&fit=crop', // Coffee Maker
            'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=400&h=400&fit=crop', // T-Shirt
            'https://images.unsplash.com/photo-1571781926291-c477ebfd024b?w=400&h=400&fit=crop', // Book
            'https://images.unsplash.com/photo-1531297484001-80022131f5a1?w=400&h=400&fit=crop', // Tablet
            'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=400&h=400&fit=crop', // Backpack
            'https://images.unsplash.com/photo-1572635196243-9c5c68b83b5c?w=400&h=400&fit=crop', // Water Bottle
            'https://images.unsplash.com/photo-1567427017947-545c5f8d16ad?w=400&h=400&fit=crop', // Gaming Controller
            'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=400&h=400&fit=crop', // Fitness Tracker
            'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=400&h=400&fit=crop', // Wallet
            'https://images.unsplash.com/photo-1572635196243-9c5c68b83b5c?w=400&h=400&fit=crop', // Perfume
            'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=400&h=400&fit=crop', // Hat
            'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=400&h=400&fit=crop', // Smart Watch
            'https://images.unsplash.com/photo-1567427017947-545c5f8d16ad?w=400&h=400&fit=crop', // Earbuds
        ];

        return [
            'name' => fake()->words(3, true),
            'description' => fake()->paragraph(3),
            'price' => fake()->randomFloat(2, 10, 1000),
            'image' => fake()->randomElement($productImages),
        ];
    }
}
