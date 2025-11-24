<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_recommendations', function (Blueprint $table) {
            $table->id();
            $table->json('viewed_product_ids'); // Last 3 viewed product IDs
            $table->json('recommended_product_ids'); // AI recommended product IDs
            $table->boolean('is_ai_generated')->default(true); // True if from AI, false if random fallback
            $table->text('ai_prompt')->nullable(); // The prompt sent to AI
            $table->text('ai_response')->nullable(); // The raw AI response
            $table->string('session_id')->nullable(); // Session identifier
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // User if authenticated
            $table->timestamps();

            $table->index('created_at');
            $table->index('is_ai_generated');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_recommendations');
    }
};
