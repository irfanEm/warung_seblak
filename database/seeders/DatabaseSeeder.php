<?php
// database/seeders/DatabaseSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $this->call([
                RoleAndPermissionSeeder::class,
                OutletSeeder::class,
                UserSeeder::class,
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
