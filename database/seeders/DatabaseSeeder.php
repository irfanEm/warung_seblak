<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $this->call([
                UserSeeder::class,
                OutletSeeder::class,
                CategorySeeder::class,
                ToppingSeeder::class,
                SpicinessLevelSeeder::class,
                MenuSeeder::class,
                TableSeeder::class,
                DeliverySettingSeeder::class,
            ]);
        });
    }
}
