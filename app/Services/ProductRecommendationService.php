<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductRecommendation;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class ProductRecommendationService
{
    /**
     * Get AI-powered product recommendations based on viewed products.
     *
     * @param  array<int>  $viewedProductIds
     * @return EloquentCollection<int, \App\Models\Product>
     */
    public function getRecommendations(array $viewedProductIds, int $limit = 3): EloquentCollection
    {
        $isAiGenerated = false;
        $aiPrompt = null;
        $aiResponse = null;

        // If no viewed products, return random products
        if (empty($viewedProductIds)) {
            $recommendations = $this->getRandomProducts($limit);
            /** @var array<int> $recommendedIds */
            $recommendedIds = $recommendations->pluck('id')->toArray();
            $this->logRecommendation($viewedProductIds, $recommendedIds, false, null, null);

            return $recommendations;
        }

        // Create cache key based on viewed products (versioned to avoid old array cache)
        $cacheKey = 'recommendations_v2_'.md5(implode(',', $viewedProductIds));
        $logKey = $cacheKey.'_logged';

        // Check if we should log (only log once per cache period)
        $shouldLog = ! Cache::has($logKey);

        $result = Cache::remember($cacheKey, now()->addHours(1), function () use ($viewedProductIds, $limit) {
            $viewedProducts = Product::whereIn('id', $viewedProductIds)->get();

            // If viewed products not found, return random
            if ($viewedProducts->isEmpty()) {
                return ['products' => $this->getRandomProducts($limit), 'is_ai' => false, 'prompt' => null, 'response' => null];
            }

            // Try to fetch from AI
            $aiPrompt = null;
            $aiResponse = null;
            $recommendations = $this->fetchFromAI($viewedProducts, $limit, $aiPrompt, $aiResponse);

            // If AI fails, fallback to random products
            if ($recommendations->isEmpty()) {
                return ['products' => $this->getRandomProducts($limit), 'is_ai' => false, 'prompt' => null, 'response' => null];
            }

            return ['products' => $recommendations, 'is_ai' => true, 'prompt' => $aiPrompt, 'response' => $aiResponse];
        });

        // Handle old cached format (direct EloquentCollection)
        if ($result instanceof EloquentCollection) {
            if ($shouldLog) {
                /** @var array<int> $recommendedIds */
                $recommendedIds = $result->pluck('id')->toArray();
                $this->logRecommendation($viewedProductIds, $recommendedIds, false, null, null);
                Cache::put($logKey, true, now()->addHours(1));
            }

            return $result;
        }

        // New format with metadata
        /** @var array{products: EloquentCollection<int, Product>, is_ai: bool, prompt: string|null, response: string|null} $result */
        $products = $result['products'];
        $isAiGenerated = (bool) $result['is_ai'];
        $aiPrompt = $result['prompt'];
        $aiResponse = $result['response'];

        // Ensure we always return exactly $limit products
        if ($products->count() < $limit) {
            // Fill with random products if we got fewer than requested
            $existingIds = $products->pluck('id')->toArray();
            $additionalProducts = $this->getRandomProducts($limit - $products->count(), $existingIds);
            $products = $products->merge($additionalProducts)->take($limit)->values();
        }

        // Log the recommendation (only once per cache period)
        if ($shouldLog) {
            /** @var array<int> $recommendedIds */
            $recommendedIds = $products->pluck('id')->toArray();
            $this->logRecommendation(
                $viewedProductIds,
                $recommendedIds,
                $isAiGenerated,
                $aiPrompt,
                $aiResponse
            );
            Cache::put($logKey, true, now()->addHours(1));
        }

        return $products;
    }

    /**
     * Fetch recommendations from AI API.
     *
     * @param  EloquentCollection<int, \App\Models\Product>  $viewedProducts
     * @return EloquentCollection<int, \App\Models\Product>
     */
    private function fetchFromAI(EloquentCollection $viewedProducts, int $limit, ?string &$aiPrompt = null, ?string &$aiResponse = null): EloquentCollection
    {
        $apiKey = config('services.groq.api_key');

        // If API key not configured, return empty collection (will trigger fallback)
        if (empty($apiKey)) {
            Log::warning('Groq API key not configured, falling back to random recommendations');

            return new EloquentCollection;
        }

        // Get all available products
        $allProducts = Product::all();

        if ($allProducts->isEmpty()) {
            return new EloquentCollection;
        }

        // Prepare product data for AI
        /** @var array<int, array{id: int, name: string, description: string}> $productList */
        $productList = $allProducts->map(fn ($product) => [
            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description ?? '',
        ])->toArray();

        /** @var array<int, array{name: string, description: string}> $viewedProductData */
        $viewedProductData = $viewedProducts->map(fn ($product) => [
            'name' => $product->name,
            'description' => $product->description ?? '',
        ])->toArray();

        // Build prompt following the exact requirement
        $prompt = $this->buildPrompt($viewedProductData, $productList, $limit);
        $aiPrompt = $prompt;

        // Get list of models to try (primary model + fallbacks)
        /** @var string $primaryModel */
        $primaryModel = config('services.groq.model', 'openai/gpt-oss-20b');
        /** @var array<int, string> $fallbackModels */
        $fallbackModels = config('services.groq.fallback_models', []);

        // Create model list: primary first, then fallbacks (excluding primary if it's already in fallbacks)
        $modelsToTry = array_unique(array_merge([$primaryModel], $fallbackModels));

        // Try each model until one succeeds or we exhaust all options
        foreach ($modelsToTry as $model) {
            try {
                /** @var string $model */
                /** @var string $apiKey */
                $response = $this->makeGroqRequest($apiKey, $model, $prompt);

                if ($response->successful()) {
                    /** @var string|null $content */
                    $content = $response->json('choices.0.message.content');
                    $aiResponse = $content;

                    if (empty($content)) {
                        Log::warning('Groq API returned empty content', ['model' => $model]);

                        continue; // Try next model
                    }

                    Log::info('Groq API request successful', ['model' => $model]);

                    return $this->parseAIResponse($content, $allProducts, $limit);
                }

                // If 429 (rate limit), try next model
                if ($response->status() === 429) {
                    Log::warning('Groq API rate limit exceeded, trying next model', [
                        'model' => $model,
                        'status' => $response->status(),
                    ]);

                    continue; // Try next model
                }

                // For other errors, log and try next model
                Log::warning('Groq API request failed, trying next model', [
                    'model' => $model,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
            } catch (\Exception $e) {
                Log::error('Error fetching AI recommendations with model', [
                    'model' => $model,
                    'message' => $e->getMessage(),
                ]);
                // Continue to next model
            }
        }

        // All models failed
        Log::error('All Groq API models failed, falling back to random recommendations');

        return new EloquentCollection;
    }

    /**
     * Make a request to Groq API with the specified model.
     */
    private function makeGroqRequest(string $apiKey, string $model, string $prompt): \Illuminate\Http\Client\Response
    {
        return Http::timeout(30)
            ->withHeaders([
                'Authorization' => "Bearer {$apiKey}",
                'Content-Type' => 'application/json',
            ])
            ->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => $model,
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $prompt,
                    ],
                ],
                'temperature' => (float) (is_numeric(config('services.groq.temperature', 1)) ? config('services.groq.temperature', 1) : 1),
                'max_completion_tokens' => (int) (is_numeric(config('services.groq.max_completion_tokens', 8192)) ? config('services.groq.max_completion_tokens', 8192) : 8192),
                'top_p' => (float) (is_numeric(config('services.groq.top_p', 1)) ? config('services.groq.top_p', 1) : 1),
                'stream' => false,
                'reasoning_effort' => is_string(config('services.groq.reasoning_effort', 'medium')) ? config('services.groq.reasoning_effort', 'medium') : 'medium',
            ]);
    }

    /**
     * Build the prompt for AI API following the exact requirement.
     *
     * @param  array<int, array{name: string, description: string}>  $viewedProducts
     * @param  array<int, array{id: int, name: string, description: string}>  $allProducts
     */
    private function buildPrompt(array $viewedProducts, array $allProducts, int $limit): string
    {
        // Format viewed products
        $viewedList = collect($viewedProducts)
            ->map(fn ($p) => "- {$p['name']}: {$p['description']}")
            ->implode("\n");

        // Format all available products
        $productList = collect($allProducts)
            ->map(fn ($p) => "ID {$p['id']}: {$p['name']} - {$p['description']}")
            ->implode("\n");

        // Exact prompt as per requirement: "Based on these viewed products, suggest 3 similar ones from this product list:"
        return "Based on these viewed products:\n\n{$viewedList}\n\nSuggest {$limit} similar products from this product list:\n\n{$productList}\n\nReturn only the product IDs (one per line, numbers only), no explanations.";
    }

    /**
     * Parse AI response and return product models.
     *
     * @param  EloquentCollection<int, \App\Models\Product>  $allProducts
     * @return EloquentCollection<int, \App\Models\Product>
     */
    private function parseAIResponse(string $content, EloquentCollection $allProducts, int $limit): EloquentCollection
    {
        // Extract product IDs from response (handles various formats)
        preg_match_all('/\b(\d+)\b/', $content, $matches);

        if (empty($matches[1])) {
            Log::warning('No product IDs found in AI response', ['content' => $content]);

            return new EloquentCollection;
        }

        // Get unique IDs and limit to requested count
        $recommendedIds = array_slice(array_unique($matches[1]), 0, $limit);
        $recommendedIds = array_map('intval', $recommendedIds);

        // Filter out invalid IDs
        $recommendedIds = array_filter($recommendedIds, fn ($id) => $id > 0);

        if (empty($recommendedIds)) {
            return new EloquentCollection;
        }

        // Get products and maintain order
        $products = $allProducts
            ->whereIn('id', $recommendedIds)
            ->take($limit)
            ->values();

        // If we got fewer products than requested, fill with random products
        if ($products->count() < $limit) {
            $existingIds = $products->pluck('id')->toArray();
            $additionalProducts = $this->getRandomProducts($limit - $products->count(), $existingIds);
            $products = $products->merge($additionalProducts)->take($limit)->values();
        }

        return $products;
    }

    /**
     * Get random products as fallback.
     *
     * @param  array<mixed>  $excludeIds
     * @return EloquentCollection<int, \App\Models\Product>
     */
    private function getRandomProducts(int $limit, array $excludeIds = []): EloquentCollection
    {
        $query = Product::inRandomOrder();

        if (! empty($excludeIds)) {
            $query->whereNotIn('id', $excludeIds);
        }

        return $query->limit($limit)->get();
    }

    /**
     * Log recommendation to database.
     *
     * @param  array<int>  $viewedProductIds
     * @param  array<int>  $recommendedProductIds
     */
    private function logRecommendation(array $viewedProductIds, array $recommendedProductIds, bool $isAiGenerated, ?string $aiPrompt, ?string $aiResponse): void
    {
        try {
            ProductRecommendation::create([
                'viewed_product_ids' => $viewedProductIds,
                'recommended_product_ids' => $recommendedProductIds,
                'is_ai_generated' => $isAiGenerated,
                'ai_prompt' => $aiPrompt,
                'ai_response' => $aiResponse,
                'session_id' => Session::getId(),
                'user_id' => Auth::id(),
            ]);
        } catch (\Exception $e) {
            // Log error but don't break the recommendation flow
            Log::error('Failed to log product recommendation', [
                'message' => $e->getMessage(),
            ]);
        }
    }
}
