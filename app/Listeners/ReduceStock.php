<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Domain\Menu\Models\Menu;
use App\Events\OrderPlaced;

class ReduceStock
{
    /**
     * Reduce menu stock for each item in the placed order.
     * Marks menu unavailable when stock reaches zero.
     */
    public function handle(OrderPlaced $event): void
    {
        $order = $event->order;
        $order->load('orderItems');

        foreach ($order->orderItems as $item) {
            $menu = Menu::find($item->menu_id);

            if (!$menu || $menu->stock_quantity === null) {
                continue;
            }

            $newStock = max(0, $menu->stock_quantity - $item->quantity);
            $menu->stock_quantity = $newStock;

            if ($newStock === 0) {
                $menu->is_available = false;
            }

            $menu->save();
        }
    }
}
