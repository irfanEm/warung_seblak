<?php

namespace Database\Seeders;

use App\Domain\Table\Models\Table;
use Hashids\Hashids;
use Illuminate\Database\Seeder;

class TableSeeder extends Seeder
{
    public function run(): void
    {
        $hashids = new Hashids(config('app.key'), 10);

        for ($i = 1; $i <= 5; $i++) {
            $table = Table::updateOrCreate(
                ['outlet_id' => 1, 'table_number' => (string) $i],
                ['status' => 'available']
            );
            
            // Generate token based on ID
            $table->update([
                'token' => $hashids->encode($table->id)
            ]);
        }
    }
}
