<?php

namespace Database\Seeders;

use App\Domain\Outlet\Models\Outlet;
use Illuminate\Database\Seeder;

class OutletSeeder extends Seeder
{
    public function run(): void
    {
        Outlet::firstOrCreate(
            ['id' => 1],
            [
                'name' => 'Warung Seblak Ibun',
                'address' => 'Jl. Cendrawasih No. 10, Bandung',
                'lat' => -6.917464,
                'lon' => 107.619123,
            ]
        );
    }
}
