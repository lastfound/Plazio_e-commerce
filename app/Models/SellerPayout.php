<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SellerPayout extends Model
{
    protected $fillable = [
        'store_id',
        'amount',
        'payout_speed',
        'bank_name',
        'account_number',
        'account_name',
        'reference_code',
        'status',
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
}
