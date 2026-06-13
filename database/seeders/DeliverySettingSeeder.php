<?php
// database/seeders/DeliverySettingSeeder.php
namespace Database\Seeders;

use App\Domain\Delivery\Models\DeliverySetting;
use App\Domain\Outlet\Models\Outlet;
use Illuminate\Database\Seeder;

class DeliverySettingSeeder extends Seeder
{
    public function run(): void
    {
        $outlet = Outlet::first();

        DeliverySetting::firstOrCreate(
            ['outlet_id' => $outlet->id],
            [
                'base_rate_per_km' => 300000,
                'minimum_charge' => 800000,
                'free_delivery_min_order' => 5000000,
                'max_delivery_distance' => 10,
            ]
        );
    }
}
