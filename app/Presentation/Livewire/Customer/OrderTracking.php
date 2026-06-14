<?php

namespace App\Presentation\Livewire\Customer;

use App\Domain\Order\Models\Order;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.customer')]
class OrderTracking extends Component
{
    public ?Order $order = null;
    public string $trackingCode = '';
    public bool $notFound = false;

    public function mount($trackingCode)
    {
        $this->trackingCode = $trackingCode;
        $this->loadOrder();
    }

    public function loadOrder()
    {
        $this->order = Order::with(['orderItems.toppings', 'orderItems.menu'])
            ->where('tracking_code', $this->trackingCode)
            ->first();

        $this->notFound = $this->order === null;
    }

    public function render()
    {
        $statusTimeline = [
            'pending' => 0,
            'payment_pending' => 0,
            'paid' => 1,
            'confirmed' => 2,
            'preparing' => 3,
            'ready' => 4,
            'completed' => 5,
            'cancelled' => -1,
        ];

        $currentStep = $this->order ? ($statusTimeline[$this->order->status] ?? 0) : 0;

        $timeline = [
            ['label' => 'Pembayaran Diterima', 'status' => 'paid', 'step' => 1],
            ['label' => 'Pesanan Dikonfirmasi', 'status' => 'confirmed', 'step' => 2],
            ['label' => 'Sedang Disiapkan', 'status' => 'preparing', 'step' => 3],
            ['label' => 'Siap Diambil', 'status' => 'ready', 'step' => 4],
            ['label' => 'Selesai', 'status' => 'completed', 'step' => 5],
        ];

        $statusLabels = [
            'pending' => 'Menunggu',
            'payment_pending' => 'Menunggu Pembayaran',
            'paid' => 'Lunas',
            'confirmed' => 'Dikonfirmasi',
            'preparing' => 'Sedang Disiapkan',
            'ready' => 'Siap Diambil',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
        ];

        return view('livewire.customer.order-tracking', compact(
            'currentStep',
            'timeline',
            'statusLabels'
        ));
    }
}
