<?php
// database/seeders/ToppingSeeder.php
namespace Database\Seeders;

use App\Domain\Menu\Models\Topping;
use App\Domain\Outlet\Models\Outlet;
use Illuminate\Database\Seeder;

class ToppingSeeder extends Seeder
{
    public function run(): void
    {
        $outlet = Outlet::first();

        $toppings = [
            ['name' => 'Telur', 'price' => 400000],
            ['name' => 'Ceker', 'price' => 500000],
            ['name' => 'Mie', 'price' => 300000],
            ['name' => 'Keju', 'price' => 400000],
            ['name' => 'Sosis', 'price' => 500000],
            ['name' => 'Kerupuk', 'price' => 200000],
        ];

        foreach ($toppings as $topping) {
            Topping::firstOrCreate(
                ['outlet_id' => $outlet->id, 'name' => $topping['name']],
                ['price' => $topping['price']]
            );
        }
    }
}
