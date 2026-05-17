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
            ['name' => 'Telur', 'price' => 4000],
            ['name' => 'Ceker', 'price' => 5000],
            ['name' => 'Mie', 'price' => 3000],
            ['name' => 'Keju', 'price' => 4000],
            ['name' => 'Sosis', 'price' => 5000],
            ['name' => 'Kerupuk', 'price' => 2000],
        ];

        foreach ($toppings as $topping) {
            Topping::firstOrCreate(
                ['outlet_id' => $outlet->id, 'name' => $topping['name']],
                ['price' => $topping['price']]
            );
        }
    }
}
