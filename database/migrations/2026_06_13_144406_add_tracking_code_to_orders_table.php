<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('tracking_code', 8)->unique()->nullable()->after('order_number');
        });

        // Backfill existing orders with tracking codes
        $orders = \App\Domain\Order\Models\Order::whereNull('tracking_code')->get();
        foreach ($orders as $order) {
            $order->tracking_code = generateTrackingCode();
            $order->saveQuietly();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropUnique(['tracking_code']);
            $table->dropColumn('tracking_code');
        });
    }
};
