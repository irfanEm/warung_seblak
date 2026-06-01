<?php

namespace App\Presentation\Livewire\Customer;

use Livewire\Component;

class OrderLookup extends Component
{
    public string $orderNumber = '';

    public function track()
    {
        $this->validate([
            'orderNumber' => 'required|string',
        ]);

        return redirect()->route('customer.order.tracking', ['order_number' => $this->orderNumber]);
    }

    public function render()
    {
        return view('livewire.customer.order-lookup')->layout('layouts.customer');
    }
}
