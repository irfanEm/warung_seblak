<?php

namespace App\Domain\Order\Models;

use App\Domain\Menu\Models\Topping;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItemTopping extends Model
{
    // No timestamps since pivot
    public $timestamps = false;
    
    // No auto-increment ID
    public $incrementing = false;

    // Remove primary key to simplify, or define composite
    protected $primaryKey = null;

    protected $fillable = [
        'order_item_id', 'topping_id', 'topping_name', 'price'
    ];

    protected $casts = [
        'price' => 'float',
    ];

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function topping(): BelongsTo
    {
        return $this->belongsTo(Topping::class);
    }
}
