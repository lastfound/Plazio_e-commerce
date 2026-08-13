<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrackingLog extends Model
{
    protected $fillable = [
        'store_tracking_link_id',
        'ip_address',
        'user_agent',
        'referrer',
        'event_type',
        'order_id',
        'conversion_amount',
    ];

    public function trackingLink(): BelongsTo
    {
        return $this->belongsTo(StoreTrackingLink::class, 'store_tracking_link_id');
    }
}
