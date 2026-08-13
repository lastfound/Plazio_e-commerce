<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'store_id',
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'discount_price',
        'stock',
        'weight_grams',
        'image',
        'specs',
        'is_local_umkm',
        'is_featured',
        'rating',
        'reviews_count',
        'sales_count',
        'platform_commission_percent',
    ];

    protected $casts = [
        'specs' => 'array',
        'is_local_umkm' => 'boolean',
        'is_featured' => 'boolean',
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'rating' => 'decimal:2',
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class);
    }

    public function getEffectivePriceAttribute(): float
    {
        return $this->discount_price && $this->discount_price > 0 ? (float) $this->discount_price : (float) $this->price;
    }
}
