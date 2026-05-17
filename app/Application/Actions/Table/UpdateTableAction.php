<?php

namespace App\Application\Actions\Table;

use App\Domain\Table\Models\Table;
use Illuminate\Support\Facades\DB;

class UpdateTableAction
{
    public function execute(int $tableId, array $data): Table
    {
        return DB::transaction(function () use ($tableId, $data) {
            $table = Table::findOrFail($tableId);
            
            // Hanya izinkan pembaruan field spesifik, tidak mengubah token
            $table->update([
                'table_number' => $data['table_number'] ?? $table->table_number,
                'is_active' => $data['is_active'] ?? $table->is_active,
                'status' => $data['status'] ?? $table->status,
            ]);

            return $table;
        });
    }
}
