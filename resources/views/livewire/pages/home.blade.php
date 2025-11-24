<div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-6 py-4">
    <!-- Hero Section -->
    <div class="text-center mb-4">
        <h1 class="text-2xl sm:text-3xl font-bold mb-1 text-zinc-900 dark:text-white">
            Welcome to SmartShop
        </h1>
        <p class="text-xs sm:text-sm text-zinc-500 dark:text-zinc-400">
            Discover amazing products powered by AI recommendations
        </p>
    </div>

    <!-- Search Bar -->
    <div class="max-w-xl mx-auto mb-4">
        <flux:field>
            <flux:input type="text" placeholder="Search products..." wire:model.live.debounce.300ms="search"
                class="w-full text-sm" />
        </flux:field>
    </div>

    <!-- Products Grid -->
    <div class="mb-6">
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-lg font-bold text-zinc-900 dark:text-white">Products</h2>
            <span class="text-xs text-zinc-500 dark:text-zinc-400">{{ $this->products->count() }} items</span>
        </div>
        @if ($this->products->isEmpty())
            <div
                class="text-center py-8 bg-zinc-50 dark:bg-zinc-900/50 rounded-lg border border-zinc-200 dark:border-zinc-800">
                <svg class="w-12 h-12 mx-auto mb-2 text-zinc-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <p class="text-zinc-600 dark:text-zinc-400 text-sm">No products found.</p>
            </div>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-2 sm:gap-3">
                @foreach ($this->products as $product)
                    <a href="{{ route('products.show', $product) }}" wire:navigate class="group">
                        <div
                            class="bg-white dark:bg-zinc-800 rounded-md border border-zinc-200 dark:border-zinc-700 overflow-hidden hover:shadow-md hover:border-zinc-300 dark:hover:border-zinc-600 transition-all duration-200 h-full flex flex-col">
                            <div class="relative aspect-square overflow-hidden bg-zinc-100 dark:bg-zinc-900">
                                @if ($product->image)
                                    <img src="{{ $product->image }}" alt="{{ $product->name }}"
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
                                    {{ $product->name }}
                                </h3>
                                <p
                                    class="text-[10px] text-zinc-500 dark:text-zinc-400 mb-1.5 line-clamp-2 flex-1 leading-tight">
                                    {{ $product->description }}
                                </p>
                                <div class="pt-1.5 border-t border-zinc-100 dark:border-zinc-700">
                                    <p class="text-sm font-bold text-green-600 dark:text-green-400">
                                        ${{ number_format($product->price, 2) }}</p>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Recommended Products Section -->
    @if ($this->recommendedProducts->isNotEmpty())
        <div class="mt-6">
            <h2 class="text-base font-bold text-zinc-900 dark:text-white mb-3 flex items-center gap-1.5">
                <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z">
                    </path>
                </svg>
                <span>Recommended for You</span>
                <span class="text-xs font-normal text-green-600 dark:text-green-400">(AI-Powered)</span>
            </h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-2 sm:gap-3">
                @foreach ($this->recommendedProducts as $product)
                    <a href="{{ route('products.show', $product) }}" wire:navigate class="group">
                        <div
                            class="bg-white dark:bg-zinc-800 rounded-md border-2 border-green-200 dark:border-green-800 overflow-hidden hover:shadow-md hover:border-green-300 dark:hover:border-green-700 transition-all duration-200 h-full flex flex-col relative">
                            <div class="absolute top-1.5 right-1.5 z-10">
                                <span
                                    class="bg-green-600 text-white text-[9px] font-semibold px-1 py-0.5 rounded-full shadow-sm">
                                    AI
                                </span>
                            </div>
                            <div class="relative aspect-square overflow-hidden bg-zinc-100 dark:bg-zinc-900">
                                @if ($product->image)
                                    <img src="{{ $product->image }}" alt="{{ $product->name }}"
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
                                    {{ $product->name }}
                                </h3>
                                <p
                                    class="text-[10px] text-zinc-500 dark:text-zinc-400 mb-1.5 line-clamp-2 flex-1 leading-tight">
                                    {{ $product->description }}
                                </p>
                                <div class="pt-1.5 border-t border-zinc-100 dark:border-zinc-700">
                                    <p class="text-sm font-bold text-green-600 dark:text-green-400">
                                        ${{ number_format($product->price, 2) }}</p>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>
