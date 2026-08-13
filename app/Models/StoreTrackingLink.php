<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StoreTrackingLink extends Model
{
    protected $fillable = [
        'store_id',
        'product_id',
        'name',
        'code',
        'channel',
        'target_type',
        'clicks_count',
        'conversions_count',
        'total_revenue',
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(TrackingLog::class);
    }

    public function getConversionRateAttribute(): float
    {
        if ($this->clicks_count == 0) return 0.0;
        return round(($this->conversions_count / $this->clicks_count) * 100, 2);
    }

    public function getFullUrlAttribute(): string
    {
        if ($this->target_type === 'product' && $this->product) {
            return url('/p/' . $this->product->slug . '?ref=' . $this->code);
        }
        return url('/toko/' . $this->store->slug . '?ref=' . $this->code);
    }
}
