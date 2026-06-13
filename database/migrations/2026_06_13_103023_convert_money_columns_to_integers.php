<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Convert all monetary decimal(10,2) columns to bigint (integer cents).
     * Values are multiplied by 100 to preserve precision (e.g. 12000.00 → 1200000).
     */
    public function up(): void
    {
        // menus.price
        DB::statement("ALTER TABLE menus ALTER COLUMN price TYPE bigint USING (ROUND(price * 100))::bigint");

        // toppings.price
        DB::statement("ALTER TABLE toppings ALTER COLUMN price TYPE bigint USING (ROUND(price * 100))::bigint");

        // menu_topping.price (nullable pivot override)
        DB::statement("ALTER TABLE menu_topping ALTER COLUMN price TYPE bigint USING (ROUND(price * 100))::bigint");

        // orders: subtotal, tax, delivery_fee, discount, total
        DB::statement("ALTER TABLE orders ALTER COLUMN subtotal TYPE bigint USING (ROUND(subtotal * 100))::bigint");
        DB::statement("ALTER TABLE orders ALTER COLUMN tax TYPE bigint USING (ROUND(tax * 100))::bigint");
        DB::statement("ALTER TABLE orders ALTER COLUMN delivery_fee TYPE bigint USING (ROUND(delivery_fee * 100))::bigint");
        DB::statement("ALTER TABLE orders ALTER COLUMN discount TYPE bigint USING (ROUND(discount * 100))::bigint");
        DB::statement("ALTER TABLE orders ALTER COLUMN total TYPE bigint USING (ROUND(total * 100))::bigint");

        // order_items: price, subtotal
        DB::statement("ALTER TABLE order_items ALTER COLUMN price TYPE bigint USING (ROUND(price * 100))::bigint");
        DB::statement("ALTER TABLE order_items ALTER COLUMN subtotal TYPE bigint USING (ROUND(subtotal * 100))::bigint");

        // order_item_toppings.price
        DB::statement("ALTER TABLE order_item_toppings ALTER COLUMN price TYPE bigint USING (ROUND(price * 100))::bigint");

        // delivery_settings: monetary columns only (not max_delivery_distance which is km)
        DB::statement("ALTER TABLE delivery_settings ALTER COLUMN base_rate_per_km TYPE bigint USING (ROUND(base_rate_per_km * 100))::bigint");
        DB::statement("ALTER TABLE delivery_settings ALTER COLUMN minimum_charge TYPE bigint USING (ROUND(minimum_charge * 100))::bigint");
        DB::statement("ALTER TABLE delivery_settings ALTER COLUMN free_delivery_min_order TYPE bigint USING (ROUND(free_delivery_min_order * 100))::bigint");
    }

    /**
     * Revert bigint columns back to decimal(10,2), dividing by 100.
     */
    public function down(): void
    {
        // menus.price
        DB::statement("ALTER TABLE menus ALTER COLUMN price TYPE decimal(10,2) USING (price::float / 100)::decimal(10,2)");

        // toppings.price
        DB::statement("ALTER TABLE toppings ALTER COLUMN price TYPE decimal(10,2) USING (price::float / 100)::decimal(10,2)");

        // menu_topping.price
        DB::statement("ALTER TABLE menu_topping ALTER COLUMN price TYPE decimal(10,2) USING (price::float / 100)::decimal(10,2)");

        // orders
        DB::statement("ALTER TABLE orders ALTER COLUMN subtotal TYPE decimal(10,2) USING (subtotal::float / 100)::decimal(10,2)");
        DB::statement("ALTER TABLE orders ALTER COLUMN tax TYPE decimal(10,2) USING (tax::float / 100)::decimal(10,2)");
        DB::statement("ALTER TABLE orders ALTER COLUMN delivery_fee TYPE decimal(10,2) USING (delivery_fee::float / 100)::decimal(10,2)");
        DB::statement("ALTER TABLE orders ALTER COLUMN discount TYPE decimal(10,2) USING (discount::float / 100)::decimal(10,2)");
        DB::statement("ALTER TABLE orders ALTER COLUMN total TYPE decimal(10,2) USING (total::float / 100)::decimal(10,2)");

        // order_items
        DB::statement("ALTER TABLE order_items ALTER COLUMN price TYPE decimal(10,2) USING (price::float / 100)::decimal(10,2)");
        DB::statement("ALTER TABLE order_items ALTER COLUMN subtotal TYPE decimal(10,2) USING (subtotal::float / 100)::decimal(10,2)");

        // order_item_toppings
        DB::statement("ALTER TABLE order_item_toppings ALTER COLUMN price TYPE decimal(10,2) USING (price::float / 100)::decimal(10,2)");

        // delivery_settings
        DB::statement("ALTER TABLE delivery_settings ALTER COLUMN base_rate_per_km TYPE decimal(10,2) USING (base_rate_per_km::float / 100)::decimal(10,2)");
        DB::statement("ALTER TABLE delivery_settings ALTER COLUMN minimum_charge TYPE decimal(10,2) USING (minimum_charge::float / 100)::decimal(10,2)");
        DB::statement("ALTER TABLE delivery_settings ALTER COLUMN free_delivery_min_order TYPE decimal(10,2) USING (free_delivery_min_order::float / 100)::decimal(10,2)");
    }
};
