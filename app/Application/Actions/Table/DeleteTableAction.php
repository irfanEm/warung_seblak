<?php

namespace App\Application\Actions\Table;

use App\Domain\Table\Models\Table;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DeleteTableAction
{
    public function execute(int $tableId): bool
    {
        return DB::transaction(function () use ($tableId) {
            $table = Table::findOrFail($tableId);

            // Hapus file QR dari disk public jika ada
            if ($table->qr_code_image_path && Storage::disk('public')->exists($table->qr_code_image_path)) {
                Storage::disk('public')->delete($table->qr_code_image_path);
            }

            return $table->delete();
        });
    }
}
