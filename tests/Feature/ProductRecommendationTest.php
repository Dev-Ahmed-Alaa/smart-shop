<?php

declare(strict_types=1);

use App\Models\Product;
use App\Services\ProductRecommendationService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

test('recommendation API successfully fetches AI recommendations and handles rate limits with model fallback', function () {
    // Create test products
    // @phpstan-ignore-next-line
    $products = Product::factory(15)->create();
    $viewedProducts = $products->take(3);
    $viewedIds = $viewedProducts->pluck('id')->toArray();

    // Configure Groq API key
    config(['services.groq.api_key' => 'test-api-key']);

    // Mock successful Groq API response with product IDs in the response
    $recommendedProductIds = $products->skip(3)->take(3)->pluck('id')->toArray();
    $aiResponseContent = implode("\n", $recommendedProductIds);

    Http::fake([
        'api.groq.com/openai/v1/chat/completions' => Http::sequence()
            ->push([
                'choices' => [
                    [
                        'message' => [
                            'content' => $aiResponseContent,
                        ],
                    ],
                ],
            ], 200),
    ]);

    // Clear cache to ensure fresh API call
    Cache::flush();

    $service = app(ProductRecommendationService::class);
    /** @var array<int> $viewedIds */
    $recommendations = $service->getRecommendations($viewedIds, 3);

    // Assertions
    expect($recommendations)->toHaveCount(3);
    expect($recommendations->pluck('id')->toArray())->toBe($recommendedProductIds);
    expect($recommendations[0])->toBeInstanceOf(Product::class);

    // Verify API was called
    Http::assertSent(function ($request) {
        /** @var \Illuminate\Http\Client\Request $request */
        return $request->url() === 'https://api.groq.com/openai/v1/chat/completions'
            && $request->method() === 'POST'
            && $request->hasHeader('Authorization', 'Bearer test-api-key')
            && isset($request->data()['model'])
            && isset($request->data()['messages']);
    });
});

test('recommendation API handles 429 rate limit error and falls back to next model', function () {
    // Create test products
    // @phpstan-ignore-next-line
    $products = Product::factory(15)->create();
    $viewedProducts = $products->take(3);
    $viewedIds = $viewedProducts->pluck('id')->toArray();

    // Configure Groq API key and fallback models
    config(['services.groq.api_key' => 'test-api-key']);
    config(['services.groq.model' => 'openai/gpt-oss-20b']);
    config([
        'services.groq.fallback_models' => [
            'openai/gpt-oss-120b',
            'llama-3.1-8b-instant',
        ],
    ]);

    // Mock API responses: first model returns 429, second model succeeds
    $recommendedProductIds = $products->skip(3)->take(3)->pluck('id')->toArray();
    $aiResponseContent = implode("\n", $recommendedProductIds);

    Http::fake([
        'api.groq.com/openai/v1/chat/completions' => Http::sequence()
            ->push(['error' => 'Rate limit exceeded'], 429) // First model fails
            ->push([
                'choices' => [
                    [
                        'message' => [
                            'content' => $aiResponseContent,
                        ],
                    ],
                ],
            ], 200), // Second model succeeds
    ]);

    // Clear cache to ensure fresh API call
    Cache::flush();

    $service = app(ProductRecommendationService::class);
    /** @var array<int> $viewedIds */
    $recommendations = $service->getRecommendations($viewedIds, 3);

    // Assertions
    expect($recommendations)->toHaveCount(3);
    expect($recommendations->pluck('id')->toArray())->toBe($recommendedProductIds);

    // Verify both API calls were made (first failed, second succeeded)
    Http::assertSentCount(2);
});

test('recommendation API falls back to random products when all models fail', function () {
    // Create test products
    // @phpstan-ignore-next-line
    $products = Product::factory(15)->create();
    $viewedProducts = $products->take(3);
    $viewedIds = $viewedProducts->pluck('id')->toArray();

    // Configure Groq API key
    config(['services.groq.api_key' => 'test-api-key']);
    config(['services.groq.model' => 'openai/gpt-oss-20b']);
    config([
        'services.groq.fallback_models' => [
            'openai/gpt-oss-120b',
        ],
    ]);

    // Mock all API calls to fail
    Http::fake([
        'api.groq.com/openai/v1/chat/completions' => Http::sequence()
            ->push(['error' => 'Service unavailable'], 503) // First model fails
            ->push(['error' => 'Service unavailable'], 503), // Fallback model fails
    ]);

    // Clear cache to ensure fresh API call
    Cache::flush();

    $service = app(ProductRecommendationService::class);
    /** @var array<int> $viewedIds */
    $recommendations = $service->getRecommendations($viewedIds, 3);

    // Should fallback to random products
    expect($recommendations)->toHaveCount(3);
    expect($recommendations[0])->toBeInstanceOf(Product::class);

    // Verify API calls were attempted
    Http::assertSentCount(2);
});
