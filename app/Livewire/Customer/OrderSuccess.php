<?php

namespace App\Livewire\Customer;

use Livewire\Component;

class OrderSuccess extends Component
{
    public string $orderNumber = '';
    public string $status = 'paid';
    public string $estimatedTime = '15-20 menit';

    /**
     * Menginisialisasi parameter nomor pesanan.
     */
    public function mount(string $order_number): void
    {
        $this->orderNumber = $order_number;

        // Ambil data order dummy dari session jika tersedia (opsional)
        $orderData = session()->get('last_order');
        if ($orderData && $orderData['order_number'] === $order_number) {
            $this->status = $orderData['status'];
        }
    }

    public function render()
    {
        return view('livewire.customer.order-success');
    }
}
