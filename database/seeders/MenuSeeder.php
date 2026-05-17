<?php
// database/seeders/MenuSeeder.php
namespace Database\Seeders;

use App\Domain\Menu\Models\Category;
use App\Domain\Menu\Models\Menu;
use App\Domain\Menu\Models\SpicinessLevel;
use App\Domain\Menu\Models\Topping;
use App\Domain\Outlet\Models\Outlet;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $outlet = Outlet::first();

        $categories = Category::where('outlet_id', $outlet->id)->pluck('id', 'slug');
        $toppings = Topping::where('outlet_id', $outlet->id)->pluck('id', 'name');
        $spiciness = SpicinessLevel::where('outlet_id', $outlet->id)->pluck('id', 'name');

        $menus = [
            [
                'name' => 'Seblak Original', 'price' => 12000, 'category_slug' => 'makanan',
                'toppings' => ['Telur', 'Kerupuk'], 'spiciness' => ['Level 1', 'Level 2']
            ],
            [
                'name' => 'Seblak Komplit', 'price' => 20000, 'category_slug' => 'makanan',
                'toppings' => ['Telur', 'Ceker', 'Mie', 'Keju'], 'spiciness' => ['Level 1', 'Level 2', 'Level 3']
            ],
            [
                'name' => 'Seblak Ceker', 'price' => 15000, 'category_slug' => 'makanan',
                'toppings' => ['Ceker'], 'spiciness' => ['Level 2', 'Level 3']
            ],
            [
                'name' => 'Baso Aci Kuah', 'price' => 10000, 'category_slug' => 'makanan',
                'toppings' => ['Telur'], 'spiciness' => ['Tidak Pedas', 'Level 1']
            ],
            [
                'name' => 'Es Teh Manis', 'price' => 5000, 'category_slug' => 'minuman',
                'toppings' => [], 'spiciness' => []
            ],
            [
                'name' => 'Es Jeruk', 'price' => 7000, 'category_slug' => 'minuman',
                'toppings' => [], 'spiciness' => []
            ],
            [
                'name' => 'Tahu Crispy', 'price' => 8000, 'category_slug' => 'snack',
                'toppings' => ['Sosis'], 'spiciness' => ['Level 1']
            ]
        ];

        foreach ($menus as $m) {
            $menu = Menu::firstOrCreate(
                ['outlet_id' => $outlet->id, 'slug' => Str::slug($m['name'])],
                [
                    'category_id' => $categories[$m['category_slug']],
                    'name' => $m['name'],
                    'price' => $m['price'],
                    'is_available' => true,
                    'stock_quantity' => null
                ]
            );

            // Sync Toppings
            $toppingIds = collect($m['toppings'])->map(fn($t) => $toppings[$t] ?? null)->filter()->toArray();
            $menu->toppings()->sync($toppingIds);

            // Sync Spiciness Levels
            $spicinessIds = collect($m['spiciness'])->map(fn($s) => $spiciness[$s] ?? null)->filter()->toArray();
            $menu->spicinessLevels()->sync($spicinessIds);
        }
    }
}
