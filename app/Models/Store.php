<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Store extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'tagline',
        'description',
        'logo',
        'banner',
        'city',
        'is_verified',
        'is_local_umkm',
        'rating',
        'subscription_tier',
        'instant_payout_enabled',
        'balance',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'is_local_umkm' => 'boolean',
        'instant_payout_enabled' => 'boolean',
        'balance' => 'decimal:2',
        'rating' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function trackingLinks(): HasMany
    {
        return $this->hasMany(StoreTrackingLink::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function payouts(): HasMany
    {
        return $this->hasMany(SellerPayout::class);
    }
}
