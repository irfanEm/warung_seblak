<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use Carbon\Carbon;

#[Layout('layouts.admin')]
class OrderList extends Component
{
    public array $orders = [];
    public ?string $filterStatus = 'all';
    public string $search = '';
    
    public ?array $selectedOrder = null;
    public bool $showDetail = false;

    public function mount()
    {
        $this->loadOrders();
    }

    private function loadOrders()
    {
        $orders = session('admin.orders', []);

        if (empty($orders)) {
            // Kita panggil class Dashboard untuk generate dummy agar konsisten, 
            // atau buat ulang di sini. Kita buat manual agar mandiri.
            $orders = $this->generateDummyOrders();
            session(['admin.orders' => $orders]);
        }

        $this->orders = $orders;
    }

    public function refreshData()
    {
        $this->orders = $this->generateDummyOrders();
        session(['admin.orders' => $this->orders]);
    }

    #[Computed]
    public function filteredOrders()
    {
        $filtered = collect($this->orders);

        if ($this->filterStatus && $this->filterStatus !== 'all') {
            $filtered = $filtered->where('status', $this->filterStatus);
        }

        if (trim($this->search) !== '') {
            $search = strtolower(trim($this->search));
            $filtered = $filtered->filter(function ($order) use ($search) {
                return str_contains(strtolower($order['order_number']), $search) ||
                       str_contains(strtolower($order['customer_name']), $search);
            });
        }

        return $filtered->sortByDesc('created_at')->values()->all();
    }

    public function viewOrder(int $id)
    {
        $this->selectedOrder = collect($this->orders)->firstWhere('id', $id);
        if ($this->selectedOrder) {
            $this->showDetail = true;
        }
    }

    public function updateStatus(int $id, string $newStatus)
    {
        $validStatuses = ['pending', 'paid', 'preparing', 'ready', 'completed', 'cancelled'];
        
        if (!in_array($newStatus, $validStatuses)) {
            return;
        }

        foreach ($this->orders as &$order) {
            if ($order['id'] === $id) {
                $order['status'] = $newStatus;
                break;
            }
        }

        session(['admin.orders' => $this->orders]);
        
        // Update selectedOrder jika sedang dibuka
        if ($this->selectedOrder && $this->selectedOrder['id'] === $id) {
            $this->selectedOrder['status'] = $newStatus;
        }
    }

    public function deleteOrder(int $id)
    {
        $this->orders = array_values(array_filter($this->orders, function ($order) use ($id) {
            return $order['id'] !== $id;
        }));

        session(['admin.orders' => $this->orders]);
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
        return view('livewire.admin.order-list');
    }
}
