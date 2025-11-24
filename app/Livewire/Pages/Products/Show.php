<?php

namespace App\Livewire\Pages\Products;

use App\Models\Product;
use App\Services\CartService;
use App\Services\ProductRecommendationService;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Show extends Component
{
    public Product $product;

    public ?string $message = null;

    /**
     * Mount the component.
     * Track viewed products in session (store last 3).
     */
    public function mount(Product $product): void
    {
        $this->product = $product;

        // Track viewed products - store last 3 in session
        /** @var array<int> $viewedProductIds */
        $viewedProductIds = session('viewed_products', []);

        // Remove current product if already in list (to avoid duplicates)
        $viewedProductIds = array_values(array_filter($viewedProductIds, fn ($id) => $id !== $product->id));

        // Add current product to the beginning
        array_unshift($viewedProductIds, $product->id);

        // Keep only last 3 viewed products
        $viewedProductIds = array_slice($viewedProductIds, 0, 3);

        // Store in session
        session(['viewed_products' => $viewedProductIds]);
    }

    /**
     * Get recommended products based on viewed products.
     * Uses AI to generate personalized recommendations, excluding current product.
     * Falls back to random products if AI fails.
     * Always returns exactly 3 products.
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product>
     */
    public function getRecommendedProductsProperty(): \Illuminate\Database\Eloquent\Collection
    {
        // Get last 3 viewed products from session
        /** @var array<int> $viewedProductIds */
        $viewedProductIds = session('viewed_products', []);

        // Get recommendations from AI service (with fallback to random)
        $service = app(ProductRecommendationService::class);
        $recommendations = $service->getRecommendations($viewedProductIds, 3);

        // Exclude current product from recommendations
        $filteredRecommendations = $recommendations->filter(fn ($product) => $product->id !== $this->product->id);

        // If after filtering we have fewer than 3 products, fill with random products excluding current
        if ($filteredRecommendations->count() < 3) {
            $existingIds = $filteredRecommendations->pluck('id')->toArray();
            $existingIds[] = $this->product->id; // Also exclude current product

            $additionalProducts = Product::whereNotIn('id', $existingIds)
                ->inRandomOrder()
                ->limit(3 - $filteredRecommendations->count())
                ->get();

            $filteredRecommendations = $filteredRecommendations->merge($additionalProducts)->take(3)->values();
        }

        // If we still have no recommendations, get random products excluding current
        if ($filteredRecommendations->isEmpty()) {
            return Product::where('id', '!=', $this->product->id)
                ->inRandomOrder()
                ->limit(3)
                ->get();
        }

        // Return exactly 3 products
        return $filteredRecommendations->take(3)->values();
    }

    /**
     * Add product to cart.
     */
    public function addToCart(): void
    {
        $cartService = app(CartService::class);
        $cartService->add($this->product->id, 1);

        $this->message = 'Product added to cart!';
        $this->dispatch('cart-updated');
    }

    /**
     * Render the component.
     */
    public function render(): View
    {
        // @phpstan-ignore-next-line
        return view('livewire.pages.products.show')
            ->layout('components.layouts.app', ['title' => $this->product->name]);
    }
}
