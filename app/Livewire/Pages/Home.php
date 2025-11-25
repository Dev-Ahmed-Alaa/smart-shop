<?php

namespace App\Livewire\Pages;

use App\Models\Product;
use App\Services\ProductRecommendationService;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Home extends Component
{
    public string $search = '';

    /**
     * Get the products based on search query.
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product>
     */
    public function getProductsProperty(): \Illuminate\Database\Eloquent\Collection
    {
        return Product::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', "%{$this->search}%")
                    ->orWhere('description', 'like', "%{$this->search}%");
            })
            ->latest()
            ->get();
    }

    /**
     * Get recommended products based on viewed products.
     * Uses AI to generate personalized recommendations from the last 3 viewed products.
     * Always returns exactly 3 products - AI-powered when possible, random as fallback.
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product>
     */
    public function getRecommendedProductsProperty(): \Illuminate\Database\Eloquent\Collection
    {
        // Get last 3 viewed products from session
        /** @var array<int> $viewedProductIds */
        $viewedProductIds = session('viewed_products', []);

        // Get recommendations from AI service (with fallback to random)
        // The service will:
        // 1. Try AI if there are viewed products
        // 2. Fall back to random if AI fails or no viewed products
        // 3. Always return exactly 3 products
        $service = app(ProductRecommendationService::class);

        return $service->getRecommendations($viewedProductIds, 3);
    }

    /**
     * Render the component.
     */
    public function render(): View
    {
        $view = view('livewire.pages.home');
        /** @var View $layoutView */
        $layoutView = $view->layout('components.layouts.app', ['title' => __('SmartShop')]);

        return $layoutView;
    }
}
