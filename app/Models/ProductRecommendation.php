<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductRecommendation extends Model
{
    /** @use HasFactory<\Illuminate\Database\Eloquent\Factories\Factory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'viewed_product_ids',
        'recommended_product_ids',
        'is_ai_generated',
        'ai_prompt',
        'ai_response',
        'session_id',
        'user_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'viewed_product_ids' => 'array',
            'recommended_product_ids' => 'array',
            'is_ai_generated' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the user that owns the recommendation.
     *
     * @return BelongsTo<\App\Models\User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the viewed products.
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product>
     */
    public function viewedProducts()
    {
        return Product::whereIn('id', $this->viewed_product_ids ?? [])->get();
    }

    /**
     * Get the recommended products.
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product>
     */
    public function recommendedProducts()
    {
        return Product::whereIn('id', $this->recommended_product_ids ?? [])->get();
    }
}
