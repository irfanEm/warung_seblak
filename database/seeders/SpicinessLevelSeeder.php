<?php
// database/seeders/SpicinessLevelSeeder.php
namespace Database\Seeders;

use App\Domain\Menu\Models\SpicinessLevel;
use App\Domain\Outlet\Models\Outlet;
use Illuminate\Database\Seeder;

class SpicinessLevelSeeder extends Seeder
{
    public function run(): void
    {
        $outlet = Outlet::first();

        $levels = ['Tidak Pedas', 'Level 1', 'Level 2', 'Level 3'];

        foreach ($levels as $level) {
            SpicinessLevel::firstOrCreate([
                'outlet_id' => $outlet->id, 
                'name' => $level
            ]);
        }
    }
}
