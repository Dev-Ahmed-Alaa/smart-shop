<?php

namespace App\Livewire\Pages;

use App\Services\CartService;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Cart extends Component
{
    public bool $checkoutSuccess = false;

    /**
     * Listen for cart update events.
     *
     * @var array<string>
     */
    protected $listeners = ['cart-updated'];

    /**
     * Get cart items.
     *
     * @return array<int, array{product: \App\Models\Product, quantity: int}>
     */
    public function getCartItemsProperty(): array
    {
        $cartService = app(CartService::class);

        return $cartService->getItems();
    }

    /**
     * Get cart total.
     */
    public function getCartTotalProperty(): float
    {
        $cartService = app(CartService::class);

        return $cartService->getTotal();
    }

    /**
     * Update product quantity in cart.
     */
    public function updateQuantity(int $productId, int $quantity): void
    {
        $cartService = app(CartService::class);
        $cartService->update($productId, $quantity);
        $this->dispatch('cart-updated');
    }

    /**
     * Remove product from cart.
     */
    public function removeItem(int $productId): void
    {
        $cartService = app(CartService::class);
        $cartService->remove($productId);
        $this->dispatch('cart-updated');
    }

    /**
     * Process checkout.
     */
    public function checkout(): void
    {
        // Simulate payment processing
        sleep(1);

        $cartService = app(CartService::class);
        $cartService->clear();
        $this->checkoutSuccess = true;
        $this->dispatch('cart-updated');
    }

    /**
     * Render the component.
     */
    public function render(): View
    {
        // @phpstan-ignore-next-line
        return view('livewire.pages.cart')
            ->layout('components.layouts.app', ['title' => __('Shopping Cart')]);
    }
}
