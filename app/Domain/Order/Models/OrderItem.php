<?php

namespace App\Domain\Order\Models;

use App\Domain\Menu\Models\Menu;
use App\Domain\Menu\Models\SpicinessLevel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id', 'menu_id', 'item_name_snapshot', 'price', 
        'quantity', 'subtotal', 'spiciness_level_id', 'notes'
    ];

    protected $casts = [
        'price' => 'float',
        'subtotal' => 'float',
        'quantity' => 'integer',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    public function spicinessLevel(): BelongsTo
    {
        return $this->belongsTo(SpicinessLevel::class);
    }

    public function toppings(): HasMany
    {
        return $this->hasMany(OrderItemTopping::class);
    }
}
