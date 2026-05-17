<?php

namespace App\Domain\Menu\Models;

use App\Domain\Outlet\Models\Outlet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $table = 'menu_categories';
    
    protected $fillable = ['outlet_id', 'name', 'slug', 'sort'];

    protected $casts = [
        'sort' => 'integer',
    ];

    public function outlet(): BelongsTo
    {
        return $this->belongsTo(Outlet::class);
    }

    public function menus(): HasMany
    {
        return $this->hasMany(Menu::class);
    }
}
