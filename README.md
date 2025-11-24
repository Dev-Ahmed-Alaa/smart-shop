# SmartShop Mini - AI-Powered Product Recommender

A minimal e-commerce demo application built with Laravel, Livewire, and AI-powered product recommendations.

## Features

- **User Authentication**: Standard Laravel authentication (Register/Login/Logout)
- **Product Catalog**: Browse and search through 20+ products
- **AI-Powered Recommendations**: Personalized product suggestions based on viewing history
- **Shopping Cart**: Session-based cart with quantity controls
- **Checkout Simulation**: Simulated payment processing

## Requirements

- PHP 8.2 or higher
- Composer
- Node.js and npm
- SQLite (or MySQL/PostgreSQL)
- Groq API Key (optional, falls back to random recommendations if not configured)

## Setup Instructions

### Prerequisites

- PHP 8.3 or higher
- Composer
- Node.js 18+ and npm
- SQLite (default) or MySQL/PostgreSQL
- Groq API Key (optional, for AI recommendations)

### Step-by-Step Installation

1. **Clone the repository:**
```bash
git clone <repository-url>
cd smart-shop
```

2. **Install PHP dependencies:**
```bash
composer install
```

3. **Install Node.js dependencies:**
```bash
npm install
```

4. **Copy the environment file:**
```bash
cp .env.example .env
```

5. **Generate application key:**
```bash
php artisan key:generate
```

6. **Configure your database in `.env`:**
```env
# For SQLite (default - no additional setup needed)
DB_CONNECTION=sqlite

# Or use MySQL/PostgreSQL
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=smartshop
# DB_USERNAME=root
# DB_PASSWORD=
```

7. **Configure Groq API key for AI recommendations:**
```env
> **Note:** The `GROQ_API_KEY` shown in this README is a **test key** included only for demonstration purposes.  
> It is **valid** and can be used for any real requests.  
> 
> If you want to run this project, please generate your own API key from:  
> https://console.groq.com/
GROQ_API_KEY=

# Optional: Customize Groq API settings
GROQ_MODEL=openai/gpt-oss-20b
GROQ_TEMPERATURE=1
GROQ_MAX_COMPLETION_TOKENS=8192
GROQ_TOP_P=1
GROQ_REASONING_EFFORT=medium
```

8. **Run migrations and seed the database:**
```bash
php artisan migrate --force
php artisan db:seed
```

This will create:
- A test user (`user@example.com` / `password`)
- 25 real products with images
- Sample product recommendations

9. **Build frontend assets:**
```bash
# For production
npm run build

# Or for development (with hot reload)
npm run dev
```

10. **Start the development server:**
```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

### Quick Start (Using Composer Setup Script)

Alternatively, you can use the setup script:
```bash
composer run setup
```

This will automatically:
- Install dependencies
- Copy `.env` file
- Generate application key
- Run migrations
- Install npm dependencies
- Build frontend assets

## Default Test User

- **Email**: `user@example.com`
- **Password**: `password`

## AI Recommendation System

### Which AI API We Used and Why

**We chose Groq API** for the following reasons:

1. **OpenAI-Compatible Interface**: Groq uses the OpenAI API format, making it easy to integrate and switch between providers if needed.

2. **High Performance**: Groq provides extremely fast inference speeds, often 10-100x faster than traditional cloud-based AI services, which is crucial for real-time product recommendations.

3. **Cost-Effective**: Groq offers competitive pricing with generous free tier limits, making it ideal for demos and production applications.

4. **Multiple Model Options**: Groq supports various models including:
   - OpenAI-compatible models (`openai/gpt-oss-20b`, `openai/gpt-oss-120b`)
   - Meta Llama models (`llama-3.1-8b-instant`, `llama-3.3-70b-versatile`)
   - Groq's own models (`groq/compound`, `groq/compound-mini`)
   - Moonshot AI models (`moonshotai/kimi-k2-instruct-0905`)

5. **Automatic Fallback**: The system includes intelligent fallback logic that automatically tries alternative models if the primary model hits rate limits (429 errors) or fails, ensuring high availability.

6. **Developer-Friendly**: Simple API structure, excellent documentation, and easy-to-use console for managing API keys.

### How It Works

1. **Product Viewing Tracking**: When a user views a product, it's added to their session's viewed products list (stores the last 3 viewed products).

2. **Recommendation Request**: When recommendations are needed (on the home page or product detail page), the system:
   - Retrieves the last 3 viewed products from the session
   - Sends the viewed products' names and descriptions to the Groq API
   - Includes the full product catalog in the prompt
   - Asks the AI to suggest similar products from the available catalog
   - Parses the AI response to extract product IDs
   - Returns the recommended products

3. **Caching**: Recommendations are cached for 1 hour based on the viewed product IDs to reduce API calls and improve performance.

4. **Fallback**: If the Groq API key is not configured, the API request fails, or all models fail, the system automatically falls back to showing 3 random products. This ensures the application always works, even without AI integration.

### Example Prompt Sent to the API

Here's an actual example of the prompt sent to Groq API:

```
Based on these viewed products:

- Wireless Bluetooth Headphones: Premium noise-cancelling wireless headphones with 30-hour battery life, crystal-clear sound quality, and comfortable over-ear design. Perfect for music lovers and professionals.
- Smart Watch Pro: Advanced fitness tracking smartwatch with heart rate monitor, GPS, sleep tracking, and 7-day battery life. Water-resistant and compatible with iOS and Android.
- Running Shoes: Lightweight running shoes with advanced cushioning technology, breathable mesh upper, and durable rubber outsole. Designed for maximum comfort and performance.

Suggest 3 similar products from this product list:

ID 1: Wireless Bluetooth Headphones - Premium noise-cancelling wireless headphones with 30-hour battery life, crystal-clear sound quality, and comfortable over-ear design. Perfect for music lovers and professionals.
ID 2: Smart Watch Pro - Advanced fitness tracking smartwatch with heart rate monitor, GPS, sleep tracking, and 7-day battery life. Water-resistant and compatible with iOS and Android.
ID 3: Running Shoes - Lightweight running shoes with advanced cushioning technology, breathable mesh upper, and durable rubber outsole. Designed for maximum comfort and performance.
ID 4: Smartphone 128GB - Latest generation smartphone with 6.7-inch OLED display, triple camera system, 5G connectivity, and all-day battery life. Available in multiple colors.
ID 5: Designer Sunglasses - Premium UV protection sunglasses with polarized lenses, lightweight frame, and stylish design. Includes protective case and cleaning cloth.
... (continues for all products)

Return only the product IDs (one per line, numbers only), no explanations.
```

**Expected AI Response:**
```
4
18
19
```

The system then extracts these IDs and returns the corresponding products.

### Model Fallback Strategy

The system implements a smart fallback mechanism:

1. **Primary Model**: Uses `openai/gpt-oss-20b` by default (configurable via `GROQ_MODEL`)

2. **Fallback Models**: If the primary model returns a 429 (rate limit) error, the system automatically tries these models in order:
   - `openai/gpt-oss-120b`
   - `openai/gpt-oss-safeguard-20b`
   - `groq/compound`
   - `groq/compound-mini`
   - `llama-3.1-8b-instant`
   - `llama-3.3-70b-versatile`
   - `meta-llama/llama-4-maverick-17b-12`
   - `meta-llama/llama-4-scout-17b-16e-i`
   - `moonshotai/kimi-k2-instruct-0905`

3. **Complete Fallback**: If all models fail, the system returns 3 random products from the catalog.

## Project Structure

```
app/
├── Models/
│   └── Product.php          # Product model
├── Services/
│   ├── CartService.php      # Session-based cart management
│   └── ProductRecommendationService.php  # AI recommendation logic
resources/
└── views/
    └── livewire/
        └── pages/
            ├── home.blade.php           # Home page with product listing
            ├── products/
            │   └── show.blade.php       # Product detail page
            └── cart.blade.php          # Shopping cart page
```

## Running Tests

Run the test suite:

```bash
php artisan test
```

Run specific test files:

```bash
php artisan test tests/Feature/ProductRecommendationTest.php
php artisan test tests/Feature/CartTest.php
```

## Code Quality

The project follows Laravel best practices and includes:

- **Laravel Pint**: Code style formatting
- **PHPStan**: Static analysis (max level)
- **Pest**: Testing framework

Run code quality checks:

```bash
# Format code
vendor/bin/pint

# Run static analysis
vendor/bin/phpstan analyse
```

## Technologies Used

- **Laravel 12**: PHP framework
- **Livewire 3**: Full-stack framework for dynamic interfaces
- **Flux UI**: Component library for Livewire
- **Tailwind CSS v4**: Utility-first CSS framework
- **Alpine.js**: Lightweight JavaScript framework (included with Livewire)
- **Groq API**: AI-powered recommendations (OpenAI-compatible, fast inference)
- **Filament v3**: Admin panel for viewing AI recommendations
- **SQLite**: Database (can be changed to MySQL/PostgreSQL)

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

