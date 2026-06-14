<?php

namespace App\Presentation\Livewire\Customer;

use App\Domain\Order\Models\Order;
use Livewire\Component;

class OrderLookup extends Component
{
    public string $trackingCode = '';

    public function track()
    {
        $this->validate([
            'trackingCode' => 'required|string|size:8',
        ]);

        $order = Order::where('tracking_code', strtoupper($this->trackingCode))->first();

        if (!$order) {
            $this->addError('trackingCode', 'Kode pesanan tidak ditemukan.');
            return;
        }

        return redirect()->route('customer.order.tracking', ['trackingCode' => $order->tracking_code]);
    }

    public function render()
    {
        return view('livewire.customer.order-lookup')->layout('layouts.customer');
    }
}
