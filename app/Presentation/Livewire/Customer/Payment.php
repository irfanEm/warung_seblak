<?php

namespace App\Presentation\Livewire\Customer;

use App\Domain\Order\Models\Order;
use Livewire\Component;

class Payment extends Component
{
    public Order $order;
    public string $snapToken;

    public function mount($orderNumber)
    {
        $this->order = Order::where('order_number', $orderNumber)->firstOrFail();
        
        $token = session('snap_token');
        if (!$token) {
            // Jika token tidak ada di session (misal user refresh page), 
            // kita bisa redirect atau harusnya generate ulang jika belum dibayar.
            // Untuk flow ini kita akan redirect ke halaman error atau menu jika tidak ada.
            session()->flash('message', 'Sesi pembayaran tidak valid atau sudah kadaluarsa.');
            return redirect()->route('customer.menu');
        }

        $this->snapToken = $token;
    }

    public function render()
    {
        return view('livewire.customer.payment')->layout('layouts.customer');
    }
}
