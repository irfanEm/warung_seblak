<?php

namespace App\Application\Actions\Table;

use App\Domain\Outlet\Models\Outlet;
use App\Domain\Table\Models\Table;
use Hashids\Hashids;
use Illuminate\Support\Facades\DB;

class CreateTableAction
{
    public function execute(array $data): Table
    {
        return DB::transaction(function () use ($data) {
            // Jika outlet_id tidak dikirim, ambil outlet pertama sebagai default
            if (empty($data['outlet_id'])) {
                $data['outlet_id'] = Outlet::first()->id ?? 1;
            }

            // Simpan record awal tanpa token
            $table = Table::create([
                'outlet_id' => $data['outlet_id'],
                'table_number' => $data['table_number'],
                'is_active' => $data['is_active'] ?? true,
                'status' => $data['status'] ?? 'available',
            ]);

            // Generate token menggunakan Hashids berdasarkan ID tabel
            $hashids = new Hashids(config('app.key'), 10);
            $token = $hashids->encode($table->id);

            // Update record dengan token
            $table->update(['token' => $token]);

            return $table;
        });
    }
}
