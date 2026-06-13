<?php

namespace App\Domain\Order\Models;

use App\Domain\Menu\Models\Topping;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItemTopping extends Model
{
    // No timestamps since pivot
    public $timestamps = false;
    
    // Use auto-increment ID
    public $incrementing = true;

    // Define primary key
    protected $primaryKey = 'id';

    protected $fillable = [
        'order_item_id', 'topping_id', 'topping_name', 'price'
    ];

    protected $casts = [
        'price' => 'integer',
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
