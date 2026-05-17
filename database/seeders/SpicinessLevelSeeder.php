<?php

namespace Database\Seeders;

use App\Domain\Menu\Models\SpicinessLevel;
use Illuminate\Database\Seeder;

class SpicinessLevelSeeder extends Seeder
{
    public function run(): void
    {
        $levels = [
            ['outlet_id' => 1, 'name' => 'Tidak Pedas'],
            ['outlet_id' => 1, 'name' => 'Level 1'],
            ['outlet_id' => 1, 'name' => 'Level 2'],
            ['outlet_id' => 1, 'name' => 'Level 3'],
        ];

        foreach ($levels as $level) {
            SpicinessLevel::updateOrCreate(
                ['outlet_id' => $level['outlet_id'], 'name' => $level['name']], 
                $level
            );
        }
    }
}
