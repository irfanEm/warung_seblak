<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Carbon\Carbon;

#[Layout('layouts.kitchen')]
class KitchenBoard extends Component
{
    public $orders = [];

    public function mount()
    {
        $this->loadOrders();
    }

    public function loadOrders()
    {
        $allOrders = session('admin.orders', []);
        $activeStatuses = ['paid', 'confirmed', 'preparing', 'ready'];

        // Filter status aktif
        $filtered = array_filter($allOrders, function($order) use ($activeStatuses) {
            return in_array($order['status'], $activeStatuses);
        });

        // Urutkan descending (terbaru di atas)
        usort($filtered, function($a, $b) {
            return strtotime($b['created_at']) <=> strtotime($a['created_at']);
        });

        $this->orders = $filtered;
    }

    public function updateStatus($orderId, $newStatus)
    {
        $allOrders = session('admin.orders', []);
        
        foreach ($allOrders as &$order) {
            if ($order['id'] == $orderId) {
                $order['status'] = $newStatus;
                break;
            }
        }

        session(['admin.orders' => $allOrders]);
        
        // Muat ulang daftar order
        $this->loadOrders();
    }

    public function render()
    {
        $this->loadOrders(); // Auto load setiap kali render (untuk polling)
        return view('livewire.admin.kitchen-board');
    }
}
