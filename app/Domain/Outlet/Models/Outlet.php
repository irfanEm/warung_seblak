<?php

namespace App\Domain\Outlet\Models;

use App\Domain\Delivery\Models\DeliverySetting;
use App\Domain\Menu\Models\Category;
use App\Domain\Menu\Models\Menu;
use App\Domain\Menu\Models\SpicinessLevel;
use App\Domain\Menu\Models\Topping;
use App\Domain\Table\Models\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Outlet extends Model
{
    protected $fillable = ['name', 'address', 'lat', 'lon'];

    protected $casts = [
        'lat' => 'float',
        'lon' => 'float',
    ];

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    public function menus(): HasMany
    {
        return $this->hasMany(Menu::class);
    }

    public function toppings(): HasMany
    {
        return $this->hasMany(Topping::class);
    }

    public function spicinessLevels(): HasMany
    {
        return $this->hasMany(SpicinessLevel::class);
    }

    public function tables(): HasMany
    {
        return $this->hasMany(Table::class);
    }

    public function deliverySetting(): HasOne
    {
        return $this->hasOne(DeliverySetting::class);
    }
}
