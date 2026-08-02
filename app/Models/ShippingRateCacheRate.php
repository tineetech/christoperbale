<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingRateCacheRate extends Model
{
    protected $fillable = [
        'shipping_rate_cache_id',
        'response_json',
    ];

    public function cache()
    {
        return $this->belongsTo(ShippingRateCache::class, 'shipping_rate_cache_id');
    }
}
