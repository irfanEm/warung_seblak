<?php

namespace Database\Seeders;

use App\Domain\Menu\Models\Topping;
use Illuminate\Database\Seeder;

class ToppingSeeder extends Seeder
{
    public function run(): void
    {
        $toppings = [
            ['outlet_id' => 1, 'name' => 'Telur', 'price' => 3000],
            ['outlet_id' => 1, 'name' => 'Sosis', 'price' => 4000],
            ['outlet_id' => 1, 'name' => 'Bakso', 'price' => 4000],
            ['outlet_id' => 1, 'name' => 'Mie', 'price' => 2000],
            ['outlet_id' => 1, 'name' => 'Keju', 'price' => 3000],
        ];

        foreach ($toppings as $topping) {
            Topping::updateOrCreate(
                ['outlet_id' => $topping['outlet_id'], 'name' => $topping['name']], 
                $topping
            );
        }
    }
}
