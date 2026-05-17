<?php

namespace Database\Seeders;

use App\Domain\Menu\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['outlet_id' => 1, 'name' => 'Seblak', 'slug' => 'seblak', 'sort' => 1],
            ['outlet_id' => 1, 'name' => 'Minuman', 'slug' => 'minuman', 'sort' => 2],
            ['outlet_id' => 1, 'name' => 'Cemilan', 'slug' => 'cemilan', 'sort' => 3],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(['outlet_id' => $cat['outlet_id'], 'slug' => $cat['slug']], $cat);
        }
    }
}
