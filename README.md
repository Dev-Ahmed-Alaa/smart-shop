# SmartShop Mini

A lightweight e-commerce demo with AI-powered product recommendations. Built with Laravel and Livewire.

## What's Inside

-   User authentication (register, login, logout)
-   Product catalog with search (25+ products)
-   AI-powered product recommendations based on browsing history
-   Shopping cart with quantity management
-   Checkout flow (simulated)

## Getting Started

### What You'll Need

-   PHP 8.3+
-   Composer
-   Node.js 18+ and npm
-   SQLite (works out of the box) or MySQL/PostgreSQL
-   Groq API key (optional - app works without it, just shows random recommendations)

### Installation

1. Clone the repo:

```bash
git clone https://github.com/Dev-Ahmed-Alaa/smart-shop
cd smart-shop
```

2. Install dependencies:

```bash
composer install
npm install
```

3. Set up environment:

```bash
cp .env.example .env
php artisan key:generate
```

4. Configure database in `.env`:

```env
# SQLite works by default, no setup needed
DB_CONNECTION=sqlite

# Or use MySQL/PostgreSQL
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=smartshop
# DB_USERNAME=root
# DB_PASSWORD=
```

5. Add Groq API key (optional):

```env
# Get one from https://console.groq.com/
GROQ_API_KEY=

# These are optional tweaks
GROQ_MODEL=openai/gpt-oss-20b
GROQ_TEMPERATURE=1
GROQ_MAX_COMPLETION_TOKENS=8192
GROQ_TOP_P=1
GROQ_REASONING_EFFORT=medium
```

6. Set up the database:

```bash
php artisan migrate --force
php artisan db:seed
```

This creates a test user (`user@example.com` / `password`), 25 products with images, and some sample recommendations.

7. Build assets:

```bash
# Production
npm run build

# Or development with hot reload
npm run dev
```

8. Start the server:

```bash
php artisan serve
```

Visit `http://localhost:8000` and you're good to go.

### Quick Setup

Or just run:

```bash
composer run setup
```

This handles everything: dependencies, env file, migrations, npm install, and asset building.

## Test Credentials

-   Email: `user@example.com`
-   Password: `password`

## How the AI Recommendations Work

I went with Groq API for a few reasons:

-   **Fast**: Seriously fast inference, way faster than typical cloud services
-   **Cheap**: Good free tier, perfect for demos
-   **OpenAI-compatible**: Easy to swap providers if needed
-   **Multiple models**: Lots of options if one hits rate limits
-   **Reliable fallback**: If everything fails, it just shows random products

### The Flow

1. When you view a product, it gets added to your session (keeps the last 3 viewed products).

2. When you need recommendations (home page or product page), the app:

    - Grabs your last 3 viewed products
    - Sends them to Groq along with the full product catalog
    - Asks for 3 similar product IDs
    - Returns those products

3. Results are cached for 1 hour to avoid hammering the API.

4. If Groq is down or you don't have an API key, it falls back to 3 random products. The app always works.

### Example API Call

Here's what gets sent to Groq:

```
Based on these viewed products:

- Wireless Bluetooth Headphones: Premium noise-cancelling wireless headphones with 30-hour battery life, crystal-clear sound quality, and comfortable over-ear design. Perfect for music lovers and professionals.
- Smart Watch Pro: Advanced fitness tracking smartwatch with heart rate monitor, GPS, sleep tracking, and 7-day battery life. Water-resistant and compatible with iOS and Android.
- Running Shoes: Lightweight running shoes with advanced cushioning technology, breathable mesh upper, and durable rubber outsole. Designed for maximum comfort and performance.

Suggest 3 similar products from this product list:

ID 1: Wireless Bluetooth Headphones - Premium noise-cancelling wireless headphones...
ID 2: Smart Watch Pro - Advanced fitness tracking smartwatch...
ID 3: Running Shoes - Lightweight running shoes...
ID 4: Smartphone 128GB - Latest generation smartphone...
ID 5: Designer Sunglasses - Premium UV protection sunglasses...
... (all products listed)

Return only the product IDs (one per line, numbers only), no explanations.
```

Groq responds with something like:

```
4
18
19
```

The app extracts those IDs and shows you those products.

### Model Fallback

If the primary model (`openai/gpt-oss-20b`) hits a rate limit, it automatically tries these in order:

-   `openai/gpt-oss-120b`
-   `openai/gpt-oss-safeguard-20b`
-   `groq/compound`
-   `groq/compound-mini`
-   `llama-3.1-8b-instant`
-   `llama-3.3-70b-versatile`
-   `meta-llama/llama-4-maverick-17b-12`
-   `meta-llama/llama-4-scout-17b-16e-i`
-   `moonshotai/kimi-k2-instruct-0905`

If all models fail, you get 3 random products. No errors, no broken pages.

## Project Structure

```
app/
├── Models/
│   └── Product.php
├── Services/
│   ├── CartService.php
│   └── ProductRecommendationService.php
resources/
└── views/
    └── livewire/
        └── pages/
            ├── home.blade.php
            ├── products/
            │   └── show.blade.php
            └── cart.blade.php
```

## Testing

Run all tests:

```bash
php artisan test
```

Run specific tests:

```bash
php artisan test tests/Feature/ProductRecommendationTest.php
```

## Code Quality

Uses Laravel Pint for formatting and PHPStan (max level) for static analysis:

```bash
# Format code
vendor/bin/pint

# Static analysis
vendor/bin/phpstan analyse
```

## Tech Stack

-   Laravel 12
-   Livewire 3
-   Tailwind CSS v4
-   Alpine.js
-   Groq API
-   Filament v3 (admin panel)
-   SQLite (or MySQL/PostgreSQL)

## License

MIT
