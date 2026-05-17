<?php

namespace Database\Seeders;

use App\Domain\Menu\Models\Category;
use App\Domain\Menu\Models\Menu;
use App\Domain\Menu\Models\SpicinessLevel;
use App\Domain\Menu\Models\Topping;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $seblakCat = Category::where('slug', 'seblak')->first()->id;
        $minumanCat = Category::where('slug', 'minuman')->first()->id;
        $cemilanCat = Category::where('slug', 'cemilan')->first()->id;

        $menus = [
            [
                'outlet_id' => 1, 'category_id' => $seblakCat, 'name' => 'Seblak Original', 
                'slug' => 'seblak-original', 'price' => 12000, 'is_available' => true
            ],
            [
                'outlet_id' => 1, 'category_id' => $seblakCat, 'name' => 'Seblak Komplit', 
                'slug' => 'seblak-komplit', 'price' => 18000, 'is_available' => true
            ],
            [
                'outlet_id' => 1, 'category_id' => $seblakCat, 'name' => 'Seblak Ceker', 
                'slug' => 'seblak-ceker', 'price' => 15000, 'is_available' => true
            ],
            [
                'outlet_id' => 1, 'category_id' => $minumanCat, 'name' => 'Teh Manis', 
                'slug' => 'teh-manis', 'price' => 5000, 'is_available' => true
            ],
            [
                'outlet_id' => 1, 'category_id' => $minumanCat, 'name' => 'Es Jeruk', 
                'slug' => 'es-jeruk', 'price' => 7000, 'is_available' => true
            ],
            [
                'outlet_id' => 1, 'category_id' => $cemilanCat, 'name' => 'Tahu Crispy', 
                'slug' => 'tahu-crispy', 'price' => 8000, 'is_available' => true
            ],
        ];

        $allToppings = Topping::pluck('id')->toArray();
        $allSpiciness = SpicinessLevel::pluck('id')->toArray();

        foreach ($menus as $menuData) {
            $menu = Menu::updateOrCreate(
                ['outlet_id' => $menuData['outlet_id'], 'slug' => $menuData['slug']],
                $menuData
            );

            // Jika kategori seblak, pasang topping & spiciness
            if ($menuData['category_id'] === $seblakCat) {
                $menu->toppings()->sync($allToppings);
                $menu->spicinessLevels()->sync($allSpiciness);
            }
        }
    }
}
