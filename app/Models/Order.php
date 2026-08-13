<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'buyer_id',
        'store_id',
        'store_tracking_link_id',
        'total_product_amount',
        'shipping_fee',
        'platform_fee',
        'total_paid_amount',
        'status',
        'shipping_courier',
        'tracking_number',
        'recipient_name',
        'recipient_phone',
        'shipping_address',
        'escrow_released_at',
    ];

    protected $casts = [
        'escrow_released_at' => 'datetime',
        'total_product_amount' => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'platform_fee' => 'decimal:2',
        'total_paid_amount' => 'decimal:2',
    ];

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function trackingLink(): BelongsTo
    {
        return $this->belongsTo(StoreTrackingLink::class, 'store_tracking_link_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function dispute(): HasOne
    {
        return $this->hasOne(Dispute::class);
    }
}
