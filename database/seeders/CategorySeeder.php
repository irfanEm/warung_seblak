<?php
// database/seeders/CategorySeeder.php
namespace Database\Seeders;

use App\Domain\Menu\Models\Category;
use App\Domain\Outlet\Models\Outlet;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $outlet = Outlet::first();

        $categories = [
            ['name' => 'Makanan', 'slug' => 'makanan', 'sort' => 1],
            ['name' => 'Minuman', 'slug' => 'minuman', 'sort' => 2],
            ['name' => 'Snack', 'slug' => 'snack', 'sort' => 3],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(
                ['outlet_id' => $outlet->id, 'slug' => $cat['slug']],
                ['name' => $cat['name'], 'sort' => $cat['sort']]
            );
        }
    }
}
