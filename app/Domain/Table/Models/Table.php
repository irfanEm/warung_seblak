<?php

namespace App\Domain\Table\Models;

use App\Domain\Outlet\Models\Outlet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Table extends Model
{
    protected $fillable = ['outlet_id', 'table_number', 'token', 'status'];

    public function outlet(): BelongsTo
    {
        return $this->belongsTo(Outlet::class);
    }
}
