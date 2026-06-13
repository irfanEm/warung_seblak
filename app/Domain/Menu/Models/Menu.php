<?php

namespace App\Domain\Menu\Models;

use App\Domain\Outlet\Models\Outlet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Menu extends Model
{
    protected $fillable = [
        'outlet_id', 'category_id', 'name', 'slug', 'description', 
        'price', 'image', 'is_available', 'stock_quantity'
    ];

    protected $casts = [
        'price' => 'integer',
        'is_available' => 'boolean',
        'stock_quantity' => 'integer',
    ];

    public function outlet(): BelongsTo
    {
        return $this->belongsTo(Outlet::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function toppings(): BelongsToMany
    {
        return $this->belongsToMany(Topping::class)
                    ->withPivot('price');
    }

    public function spicinessLevels(): BelongsToMany
    {
        return $this->belongsToMany(SpicinessLevel::class);
    }
}
