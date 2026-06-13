<?php

namespace App\Domain\Delivery\Models;

use App\Domain\Outlet\Models\Outlet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliverySetting extends Model
{
    protected $fillable = [
        'outlet_id', 'base_rate_per_km', 'minimum_charge', 
        'free_delivery_min_order', 'max_delivery_distance'
    ];

    protected $casts = [
        'base_rate_per_km' => 'integer',
        'minimum_charge' => 'integer',
        'free_delivery_min_order' => 'integer',
        'max_delivery_distance' => 'float',
    ];

    public function outlet(): BelongsTo
    {
        return $this->belongsTo(Outlet::class);
    }
}
