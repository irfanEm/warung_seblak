<?php
// database/seeders/TableSeeder.php
namespace Database\Seeders;

use App\Domain\Outlet\Models\Outlet;
use App\Domain\Table\Models\Table;
use Hashids\Hashids;
use Illuminate\Database\Seeder;

class TableSeeder extends Seeder
{
    public function run(): void
    {
        $outlet = Outlet::first();
        $hashids = new Hashids(config('app.key'), 10);

        for ($i = 1; $i <= 5; $i++) {
            $table = Table::firstOrCreate(
                ['outlet_id' => $outlet->id, 'table_number' => (string) $i],
                ['status' => 'available'] // Default tersedia
            );

            // Perbarui token setelah meja dibuat jika masih kosong
            if (empty($table->token)) {
                $table->update([
                    'token' => $hashids->encode($table->id)
                ]);
            }
        }
    }
}
