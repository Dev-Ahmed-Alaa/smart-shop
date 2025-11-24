<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = $this->getProductsData();

        foreach ($products as $productData) {
            // Check if product already exists by name
            if (Product::where('name', $productData['name'])->exists()) {
                $this->command->info("Product '{$productData['name']}' already exists, skipping...");

                continue;
            }

            Product::create($productData);
            $this->command->info("Created product: {$productData['name']}");
        }

        $this->command->info('Product seeding completed!');
    }

    /**
     * Get real product data with real images.
     *
     * @return array<int, array{name: string, description: string, price: float, image: string}>
     */
    private function getProductsData(): array
    {
        return [
            [
                'name' => 'Wireless Bluetooth Headphones',
                'description' => 'Premium noise-cancelling wireless headphones with 30-hour battery life, crystal-clear sound quality, and comfortable over-ear design. Perfect for music lovers and professionals.',
                'price' => 199.99,
                'image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&h=800&fit=crop&auto=format',
            ],
            [
                'name' => 'Smart Watch Pro',
                'description' => 'Advanced fitness tracking smartwatch with heart rate monitor, GPS, sleep tracking, and 7-day battery life. Water-resistant and compatible with iOS and Android.',
                'price' => 299.99,
                'image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=800&h=800&fit=crop&auto=format',
            ],
            [
                'name' => 'Running Shoes',
                'description' => 'Lightweight running shoes with advanced cushioning technology, breathable mesh upper, and durable rubber outsole. Designed for maximum comfort and performance.',
                'price' => 129.99,
                'image' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=800&h=800&fit=crop&auto=format',
            ],
            [
                'name' => 'Smartphone 128GB',
                'description' => 'Latest generation smartphone with 6.7-inch OLED display, triple camera system, 5G connectivity, and all-day battery life. Available in multiple colors.',
                'price' => 799.99,
                'image' => 'https://images.unsplash.com/photo-1730212426715-f0189e690149?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            ],
            [
                'name' => 'Designer Sunglasses',
                'description' => 'Premium UV protection sunglasses with polarized lenses, lightweight frame, and stylish design. Includes protective case and cleaning cloth.',
                'price' => 149.99,
                'image' => 'https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=800&h=800&fit=crop&auto=format',
            ],
            [
                'name' => 'Laptop 15" Pro',
                'description' => 'High-performance laptop with Intel Core i7 processor, 16GB RAM, 512GB SSD, and stunning 15-inch Retina display. Perfect for work and creative projects.',
                'price' => 1299.99,
                'image' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=800&h=800&fit=crop&auto=format',
            ],
            [
                'name' => 'Digital Camera',
                'description' => 'Professional 24MP mirrorless camera with 4K video recording, interchangeable lens system, and advanced autofocus. Ideal for photography enthusiasts.',
                'price' => 899.99,
                'image' => 'https://images.unsplash.com/photo-1502920917128-1aa500764cbd?w=800&h=800&fit=crop&auto=format',
            ],
            [
                'name' => 'Wireless Speaker',
                'description' => 'Premium Bluetooth speaker with 360-degree sound, 20-hour battery life, and waterproof design. Perfect for parties and outdoor adventures.',
                'price' => 179.99,
                'image' => 'https://images.unsplash.com/photo-1582978571763-2d039e56f0c3?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            ],
            [
                'name' => 'Mechanical Keyboard',
                'description' => 'RGB backlit mechanical keyboard with Cherry MX switches, programmable keys, and ergonomic design. Perfect for gaming and typing.',
                'price' => 129.99,
                'image' => 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=800&h=800&fit=crop&auto=format',
            ],
            [
                'name' => 'Gaming Mouse',
                'description' => 'High-precision gaming mouse with customizable RGB lighting, programmable buttons, and ergonomic design. Perfect for competitive gaming.',
                'price' => 79.99,
                'image' => 'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'Leather Jacket',
                'description' => 'Classic leather jacket made from genuine leather, with quilted lining and multiple pockets. Timeless style for any season.',
                'price' => 349.99,
                'image' => 'https://images.unsplash.com/photo-1551028719-00167b16eac5?w=800&h=800&fit=crop&auto=format',
            ],
            [
                'name' => 'Designer Backpack',
                'description' => 'Durable travel backpack with laptop compartment, multiple pockets, and water-resistant material. Perfect for work, travel, and daily use.',
                'price' => 89.99,
                'image' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=800&h=800&fit=crop&auto=format',
            ],
            [
                'name' => 'Coffee Maker',
                'description' => 'Programmable coffee maker with thermal carafe, 12-cup capacity, and auto-shutoff feature. Brew perfect coffee every morning.',
                'price' => 79.99,
                'image' => 'https://images.unsplash.com/photo-1517487881594-2787fef5ebf7?w=800&h=800&fit=crop&auto=format',
            ],
            [
                'name' => 'Cotton T-Shirt',
                'description' => 'Premium 100% organic cotton t-shirt with comfortable fit, breathable fabric, and classic design. Available in multiple colors.',
                'price' => 29.99,
                'image' => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=800&h=800&fit=crop&auto=format',
            ],
            [
                'name' => 'Programming Book',
                'description' => 'Comprehensive guide to modern web development covering HTML, CSS, JavaScript, and popular frameworks. Perfect for beginners and professionals.',
                'price' => 49.99,
                'image' => 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?w=800&h=800&fit=crop&auto=format',
            ],
            [
                'name' => 'Tablet 10"',
                'description' => '10-inch tablet with high-resolution display, long battery life, and powerful processor. Perfect for reading, browsing, and entertainment.',
                'price' => 399.99,
                'image' => 'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=800&h=800&fit=crop&auto=format',
            ],
            [
                'name' => 'Stainless Steel Water Bottle',
                'description' => 'Insulated stainless steel water bottle keeps drinks cold for 24 hours or hot for 12 hours. BPA-free and leak-proof design.',
                'price' => 34.99,
                'image' => 'https://images.unsplash.com/photo-1602143407151-7111542de6e8?w=800&h=800&fit=crop&auto=format',
            ],
            [
                'name' => 'Gaming Controller',
                'description' => 'Wireless gaming controller with haptic feedback, adaptive triggers, and long battery life. Compatible with PC and consoles.',
                'price' => 69.99,
                'image' => 'https://images.unsplash.com/photo-1606144042614-b2417e99c4e3?w=800&h=800&fit=crop&auto=format',
            ],
            [
                'name' => 'Fitness Tracker',
                'description' => 'Advanced fitness tracker with heart rate monitor, step counter, sleep tracking, and smartphone notifications. Water-resistant design.',
                'price' => 99.99,
                'image' => 'https://images.unsplash.com/photo-1532435109783-fdb8a2be0baa?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            ],
            [
                'name' => 'Leather Wallet',
                'description' => 'Genuine leather wallet with RFID blocking technology, multiple card slots, and cash compartment. Slim and elegant design.',
                'price' => 59.99,
                'image' => 'https://images.unsplash.com/photo-1627123424574-724758594e93?w=800&h=800&fit=crop&auto=format',
            ],
            [
                'name' => 'Premium Perfume',
                'description' => 'Luxury fragrance with long-lasting scent, elegant bottle design, and sophisticated notes. Perfect for special occasions.',
                'price' => 89.99,
                'image' => 'https://images.unsplash.com/photo-1541643600914-78b084683601?w=800&h=800&fit=crop&auto=format',
            ],
            [
                'name' => 'Baseball Cap',
                'description' => 'Classic baseball cap with adjustable strap, breathable fabric, and embroidered logo. Available in multiple colors and sizes.',
                'price' => 24.99,
                'image' => 'https://images.unsplash.com/photo-1588850561407-ed78c282e89b?w=800&h=800&fit=crop&auto=format',
            ],
            [
                'name' => 'Smart Watch Sport',
                'description' => 'Fitness-focused smartwatch with GPS, heart rate monitor, and 50+ sport modes. Water-resistant up to 50 meters.',
                'price' => 249.99,
                'image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=800&h=800&fit=crop&auto=format',
            ],
            [
                'name' => 'Wireless Earbuds',
                'description' => 'True wireless earbuds with active noise cancellation, 8-hour battery life, and crystal-clear sound. Perfect for music and calls.',
                'price' => 149.99,
                'image' => 'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?w=800&h=800&fit=crop&auto=format',
            ],
            [
                'name' => 'Yoga Mat',
                'description' => 'Premium non-slip yoga mat with extra cushioning, eco-friendly materials, and carrying strap. Perfect for yoga, pilates, and exercise.',
                'price' => 39.99,
                'image' => 'https://images.unsplash.com/photo-1601925260368-ae2f83cf8b7f?w=800&h=800&fit=crop&auto=format',
            ],
            [
                'name' => 'Desk Lamp',
                'description' => 'Modern LED desk lamp with adjustable brightness, color temperature control, and USB charging port. Eye-friendly lighting for work.',
                'price' => 49.99,
                'image' => 'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?w=800&h=800&fit=crop&auto=format',
            ],
        ];
    }
}
