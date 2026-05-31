<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Carbon\Carbon;

#[Layout('layouts.admin')]
class Dashboard extends Component
{
    public int $totalOrdersToday = 0;
    public float $revenueToday = 0;
    public int $activeOrders = 0;
    public int $pendingPayments = 0;
    public array $recentOrders = [];

    public function mount()
    {
        $orders = session('admin.orders', []);

        if (empty($orders)) {
            $orders = $this->generateDummyOrders();
            session(['admin.orders' => $orders]);
        }

        $today = Carbon::today()->format('Y-m-d');

        $this->totalOrdersToday = collect($orders)
            ->filter(fn($o) => str_starts_with($o['created_at'], $today))
            ->count();

        $this->revenueToday = collect($orders)
            ->filter(fn($o) => str_starts_with($o['created_at'], $today) && !in_array($o['status'], ['cancelled']))
            ->sum('total');

        $this->activeOrders = collect($orders)
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->count();

        $this->pendingPayments = collect($orders)
            ->whereIn('status', ['pending', 'payment_pending'])
            ->count();

        $this->recentOrders = collect($orders)
            ->sortByDesc('created_at')
            ->take(5)
            ->values()
            ->all();
    }

    private function generateDummyOrders(): array
    {
        $dummy = [];
        $statuses = ['pending', 'paid', 'preparing', 'ready', 'completed', 'cancelled'];
        $types = ['dine_in', 'delivery', 'pos'];
        $names = ["Andi Pratama", "Siti Nurhaliza", "Budi Santoso", "Dewi Lestari", "Agus Setiawan", "Rini Yulianti", "Joko Purwanto", "Ayu Wandira", "Dedi Saputra", "Fitri Handayani"];
        $menuItems = [
            ['name' => 'Seblak Original', 'price' => 15000],
            ['name' => 'Seblak Spesial', 'price' => 25000],
            ['name' => 'Seblak Ceker', 'price' => 20000],
            ['name' => 'Es Teh', 'price' => 5000],
            ['name' => 'Jeruk Hangat', 'price' => 6000],
            ['name' => 'Tahu Goreng', 'price' => 1000],
            ['name' => 'Tempe Goreng', 'price' => 1000],
        ];

        for ($i = 1; $i <= 15; $i++) {
            $date = Carbon::now()->subDays(rand(0, 14))->subHours(rand(1, 10)); // Hari ini, kemarin, minggu lalu
            $type = $types[array_rand($types)];
            
            $itemCount = rand(1, 3);
            $orderItems = [];
            $subtotal = 0;
            
            for ($j = 0; $j < $itemCount; $j++) {
                $menu = $menuItems[array_rand($menuItems)];
                $qty = rand(1, 3);
                $subtotal += $menu['price'] * $qty;
                $orderItems[] = [
                    'name' => $menu['name'],
                    'quantity' => $qty,
                    'price' => $menu['price'],
                    'toppings' => str_contains(strtolower($menu['name']), 'seblak') ? ['Sosis', 'Bakso'] : [],
                    'spiciness' => str_contains(strtolower($menu['name']), 'seblak') ? ['Sedang', 'Pedas', 'Sangat Pedas'][rand(0, 2)] : null
                ];
            }

            $order = [
                'id' => $i,
                'order_number' => 'INV-' . $date->format('Ymd') . '-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'customer_name' => $names[array_rand($names)],
                'customer_phone' => '0812' . rand(10000000, 99999999),
                'type' => $type,
                'table_number' => $type === 'dine_in' ? 'Meja ' . rand(1, 5) : null,
                'delivery_address' => $type === 'delivery' ? 'Jl. Merdeka No. ' . rand(1, 100) . ', Jakarta' : null,
                'status' => $statuses[array_rand($statuses)],
                'subtotal' => $subtotal,
                'tax' => 0,
                'created_at' => $date->format('Y-m-d H:i:s'),
                'items' => $orderItems,
                'notes' => rand(0, 1) ? 'Tolong dipercepat ya' : ''
            ];

            $order['tax'] = $order['subtotal'] * 0.11;
            $order['total'] = $order['subtotal'] + $order['tax'];

            $dummy[] = $order;
        }

        return $dummy;
    }

    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}
