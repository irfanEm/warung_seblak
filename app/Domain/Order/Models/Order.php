<?php

namespace App\Domain\Order\Models;

use App\Domain\Outlet\Models\Outlet;
use App\Domain\Table\Models\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'order_number', 'outlet_id', 'type', 'table_id', 'delivery_address_id',
        'customer_name', 'customer_phone', 'subtotal', 'tax', 'delivery_fee',
        'discount', 'total', 'status', 'notes', 'applied_promo_id', 'assigned_driver_id'
    ];

    protected $casts = [
        'subtotal' => 'float',
        'tax' => 'float',
        'delivery_fee' => 'float',
        'discount' => 'float',
        'total' => 'float',
    ];

    public function outlet(): BelongsTo
    {
        return $this->belongsTo(Outlet::class);
    }

    public function table(): BelongsTo
    {
        return $this->belongsTo(Table::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
