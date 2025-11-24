<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    @if ($checkoutSuccess)
        <div class="max-w-2xl mx-auto">
            <div
                class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 border-2 border-green-200 dark:border-green-800 rounded-2xl p-8 text-center shadow-xl">
                <div class="w-20 h-20 mx-auto mb-6 bg-green-600 rounded-full flex items-center justify-center">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold mb-3 text-green-800 dark:text-green-300">Order Confirmed!</h2>
                <p class="text-green-700 dark:text-green-400 mb-6 text-lg">Thank you for your purchase. Your order has
                    been successfully processed.</p>
                <a href="{{ route('home') }}" wire:navigate class="inline-block">
                    <flux:button variant="primary" class="px-8 py-3">
                        Continue Shopping
                    </flux:button>
                </a>
            </div>
        </div>
    @else
        @if (empty($this->cartItems))
            <div class="max-w-md mx-auto text-center py-16">
                <div
                    class="w-24 h-24 mx-auto mb-6 bg-zinc-100 dark:bg-zinc-800 rounded-full flex items-center justify-center">
                    <svg class="w-12 h-12 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold mb-3 text-zinc-900 dark:text-white">Your cart is empty</h2>
                <p class="text-zinc-600 dark:text-zinc-400 mb-8 text-lg">Start shopping to add items to your cart.</p>
                <a href="{{ route('home') }}" wire:navigate>
                    <flux:button variant="primary" class="px-8 py-3">
                        Browse Products
                    </flux:button>
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
                <!-- Cart Items -->
                <div class="lg:col-span-2 space-y-4">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-3xl font-bold text-zinc-900 dark:text-white">Shopping Cart</h2>
                        <span class="text-sm text-zinc-500 dark:text-zinc-400">{{ count($this->cartItems) }}
                            {{ Str::plural('item', count($this->cartItems)) }}</span>
                    </div>
                    @foreach ($this->cartItems as $item)
                        <div
                            class="bg-white dark:bg-zinc-800 rounded-xl border border-zinc-200 dark:border-zinc-700 p-5 hover:shadow-lg transition-shadow">
                            <div class="flex gap-5">
                                <a href="{{ route('products.show', $item['product']) }}" wire:navigate
                                    class="flex-shrink-0">
                                    @if ($item['product']->image)
                                        <img src="{{ $item['product']->image }}" alt="{{ $item['product']->name }}"
                                            class="w-28 h-28 object-cover rounded-lg border-2 border-zinc-200 dark:border-zinc-700 hover:border-green-400 transition-colors">
                                    @else
                                        <div
                                            class="w-28 h-28 bg-gradient-to-br from-zinc-100 to-zinc-200 dark:from-zinc-700 dark:to-zinc-800 rounded-lg border-2 border-zinc-200 dark:border-zinc-700 flex items-center justify-center">
                                            <svg class="w-10 h-10 text-zinc-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        </div>
                                    @endif
                                </a>

                                <div class="flex-1 min-w-0">
                                    <a href="{{ route('products.show', $item['product']) }}" wire:navigate>
                                        <h3
                                            class="font-semibold text-lg mb-1 text-zinc-900 dark:text-white hover:text-green-600 dark:hover:text-green-400 transition-colors line-clamp-2">
                                            {{ $item['product']->name }}
                                        </h3>
                                    </a>
                                    <p class="text-xl font-bold text-green-600 dark:text-green-400 mb-4">
                                        ${{ number_format($item['product']->price, 2) }}
                                    </p>

                                    <div class="flex flex-wrap items-center gap-3" x-data="{ quantity: {{ $item['quantity'] }} }"
                                        x-init="$watch('quantity', value => $wire.updateQuantity({{ $item['product']->id }}, value))">
                                        <label
                                            class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Quantity:</label>
                                        <div
                                            class="flex items-center gap-1 border border-zinc-300 dark:border-zinc-600 rounded-lg overflow-hidden">
                                            <flux:button size="sm" variant="ghost" class="!rounded-none border-0"
                                                x-on:click="if(quantity > 1) quantity--"
                                                :disabled="$item['quantity'] <= 1">
                                                -
                                            </flux:button>
                                            <span class="w-12 text-center font-semibold text-zinc-900 dark:text-white"
                                                x-text="quantity">{{ $item['quantity'] }}</span>
                                            <flux:button size="sm" variant="ghost" class="!rounded-none border-0"
                                                x-on:click="quantity++">
                                                +
                                            </flux:button>
                                        </div>
                                        <flux:button variant="danger" size="sm"
                                            wire:click="removeItem({{ $item['product']->id }})" class="ml-auto">
                                            Remove
                                        </flux:button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Cart Summary -->
                <div class="lg:col-span-1">
                    <div
                        class="bg-white dark:bg-zinc-800 rounded-xl border-2 border-zinc-200 dark:border-zinc-700 p-6 sticky top-4 shadow-lg">
                        <h2 class="text-2xl font-bold mb-6 text-zinc-900 dark:text-white">Order Summary</h2>

                        <div class="space-y-4 mb-6">
                            <div class="flex justify-between text-base">
                                <span class="text-zinc-600 dark:text-zinc-400">Subtotal:</span>
                                <span
                                    class="font-semibold text-zinc-900 dark:text-white">${{ number_format($this->cartTotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-base">
                                <span class="text-zinc-600 dark:text-zinc-400">Tax:</span>
                                <span class="font-semibold text-zinc-900 dark:text-white">$0.00</span>
                            </div>
                            <div class="border-t-2 border-zinc-200 dark:border-zinc-700 pt-4 flex justify-between">
                                <span class="text-xl font-bold text-zinc-900 dark:text-white">Total:</span>
                                <span
                                    class="text-2xl font-bold text-green-600 dark:text-green-400">${{ number_format($this->cartTotal, 2) }}</span>
                            </div>
                        </div>

                        <flux:button variant="primary"
                            class="w-full py-3.5 text-base font-semibold shadow-lg hover:shadow-xl transition-shadow"
                            wire:click="checkout">
                            <span class="flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Proceed to Checkout
                            </span>
                        </flux:button>
                    </div>
                </div>
            </div>
        @endif
    @endif
</div>
