<?php

namespace App\Application\Actions\Order;

use App\Domain\Order\Models\Order;
use App\Domain\Outlet\Models\Outlet;
use App\Domain\Table\Models\Table;
use Illuminate\Support\Facades\DB;

class PlaceOrderAction
{
    public function execute(
        array $cart,
        ?int $tableId = null,
        ?string $customerName = null,
        ?string $customerPhone = null,
        ?string $notes = null,
        string $status = 'pending'
    ): Order {
        return DB::transaction(function () use ($cart, $tableId, $customerName, $customerPhone, $notes, $status) {
            
            // 1. Dapatkan outlet_id
            $outletId = null;
            if ($tableId) {
                $table = Table::find($tableId);
                $outletId = $table?->outlet_id;
            }
            if (!$outletId) {
                $outletId = Outlet::first()?->id;
            }

            // 2. Hitung subtotal
            $subtotal = collect($cart)->sum('subtotal');
            $tax = 0; // Tambahkan tax logic jika diperlukan nanti
            $deliveryFee = 0;
            $discount = 0;
            $total = $subtotal + $tax + $deliveryFee - $discount;

            // 3. Generate Order Number
            $orderNumber = $this->generateOrderNumber($outletId);

            // 4. Buat Order
            $order = Order::create([
                'order_number' => $orderNumber,
                'outlet_id' => $outletId,
                'type' => $tableId ? 'dine_in' : 'delivery',
                'table_id' => $tableId,
                'customer_name' => $customerName,
                'customer_phone' => $customerPhone,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'delivery_fee' => $deliveryFee,
                'discount' => $discount,
                'total' => $total,
                'status' => $status,
                'notes' => $notes,
            ]);

            // 5. Simpan Order Items dan Toppings (Snapshot)
            foreach ($cart as $item) {
                $orderItem = $order->orderItems()->create([
                    'menu_id' => $item['menu_id'],
                    'item_name_snapshot' => $item['name'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['subtotal'],
                    'spiciness_level_id' => $item['spiciness_level']['id'] ?? null,
                    'notes' => null, // Opsional jika tiap item butuh catatan unik
                ]);

                // Simpan toppings jika ada
                if (!empty($item['toppings'])) {
                    foreach ($item['toppings'] as $topping) {
                        $orderItem->toppings()->create([
                            'topping_id' => $topping['id'],
                            'topping_name' => $topping['name'],
                            'price' => $topping['price'],
                        ]);
                    }
                }
            }

            return $order;
        });
    }

    private function generateOrderNumber(?int $outletId): string
    {
        $today = now()->format('Ymd');
        
        // Cek order terakhir hari ini. Gunakan lockForUpdate() karena dipanggil di dalam DB transaction.
        // Hal ini sangat penting untuk mencegah nomor ganda saat request konkuren (race condition).
        $lastOrder = Order::where('order_number', 'like', "INV-{$today}-%")
            ->orderByDesc('order_number')
            ->lockForUpdate()
            ->first();

        $nextNumber = 1;
        if ($lastOrder) {
            $parts = explode('-', $lastOrder->order_number);
            $nextNumber = intval(end($parts)) + 1;
        }

        return 'INV-' . $today . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
