<?php
// database/seeders/OutletSeeder.php
namespace Database\Seeders;

use App\Domain\Outlet\Models\Outlet;
use Illuminate\Database\Seeder;

class OutletSeeder extends Seeder
{
    public function run(): void
    {
        Outlet::firstOrCreate(
            ['name' => 'Warung Seblak Mantap'],
            [
                'address' => 'Jl. Raya No. 123, Bandung',
                'tax_rate' => 10.00, // 10% PPN
                'lat' => -6.917464,
                'lon' => 107.619123,
            ]
        );
    }
}
