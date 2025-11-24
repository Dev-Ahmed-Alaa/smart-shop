<div class="max-w-5xl mx-auto px-3 sm:px-4 lg:px-6 py-4">
    <!-- Product Details -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <!-- Product Image -->
        <div class="h-fit">
            <div
                class="relative rounded-md overflow-hidden border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 shadow-sm">
                @if ($product->image)
                    <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full max-h-[400px] object-contain"
                        loading="eager">
                @else
                    <div
                        class="w-full aspect-square bg-gradient-to-br from-zinc-100 to-zinc-200 dark:from-zinc-800 dark:to-zinc-900 flex items-center justify-center">
                        <svg class="w-12 h-12 text-zinc-300 dark:text-zinc-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                @endif
            </div>
        </div>

        <!-- Product Info -->
        <div class="space-y-3">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold mb-1.5 text-zinc-900 dark:text-white leading-tight">
                    {{ $product->name }}
                </h1>
                <div class="flex items-baseline gap-2 mb-3">
                    <p class="text-2xl font-bold text-green-600 dark:text-green-400">
                        ${{ number_format($product->price, 2) }}</p>
                </div>
            </div>

            <div class="bg-zinc-50 dark:bg-zinc-900/50 rounded-md p-3 border border-zinc-200 dark:border-zinc-700">
                <h3 class="text-xs font-semibold mb-1.5 text-zinc-900 dark:text-white uppercase tracking-wide">
                    Description</h3>
                <p class="text-xs text-zinc-600 dark:text-zinc-400 leading-relaxed">
                    {{ $product->description }}
                </p>
            </div>

            <div class="pt-1">
                <flux:button wire:click="addToCart" variant="primary"
                    class="w-full py-2 text-xs font-semibold shadow-sm hover:shadow-md transition-shadow">
                    <span class="flex items-center justify-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                        Add to Cart
                    </span>
                </flux:button>
            </div>

            @if ($message)
                <div
                    class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-300 px-2.5 py-1.5 rounded-md flex items-center gap-1.5 text-xs">
                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span class="font-medium">{{ $message }}</span>
                </div>
            @endif
        </div>
    </div>

    <!-- You Might Also Like Section -->
    @if ($this->recommendedProducts->isNotEmpty())
        <div class="mt-6">
            <h2 class="text-base font-bold text-zinc-900 dark:text-white mb-3 flex items-center gap-1.5">
                <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                    </path>
                </svg>
                You Might Also Like
            </h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-2 sm:gap-3">
                @foreach ($this->recommendedProducts as $recommendedProduct)
                    <a href="{{ route('products.show', $recommendedProduct) }}" wire:navigate class="group">
                        <div
                            class="bg-white dark:bg-zinc-800 rounded-md border border-zinc-200 dark:border-zinc-700 overflow-hidden hover:shadow-md hover:border-zinc-300 dark:hover:border-zinc-600 transition-all duration-200 h-full flex flex-col">
                            <div class="relative aspect-square overflow-hidden bg-zinc-100 dark:bg-zinc-900">
                                @if ($recommendedProduct->image)
                                    <img src="{{ $recommendedProduct->image }}" alt="{{ $recommendedProduct->name }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200"
                                        loading="lazy">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg class="w-8 h-8 text-zinc-300 dark:text-zinc-600" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="p-2 flex-1 flex flex-col">
                                <h3
                                    class="font-medium text-xs mb-1 text-zinc-900 dark:text-white line-clamp-2 group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors min-h-[2rem]">
                                    {{ $recommendedProduct->name }}
                                </h3>
                                <p
                                    class="text-[10px] text-zinc-500 dark:text-zinc-400 mb-1.5 line-clamp-2 flex-1 leading-tight">
                                    {{ $recommendedProduct->description }}
                                </p>
                                <div class="pt-1.5 border-t border-zinc-100 dark:border-zinc-700">
                                    <p class="text-sm font-bold text-green-600 dark:text-green-400">
                                        ${{ number_format($recommendedProduct->price, 2) }}</p>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>
