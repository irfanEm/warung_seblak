<?php

namespace Database\Seeders;

use App\Domain\Delivery\Models\DeliverySetting;
use Illuminate\Database\Seeder;

class DeliverySettingSeeder extends Seeder
{
    public function run(): void
    {
        DeliverySetting::updateOrCreate(
            ['outlet_id' => 1],
            [
                'base_rate_per_km' => 3000,
                'minimum_charge' => 8000,
                'free_delivery_min_order' => 50000,
                'max_delivery_distance' => 10.00,
            ]
        );
    }
}
