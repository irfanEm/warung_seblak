<?php

namespace App\Presentation\Livewire\Customer;

use App\Domain\Order\Models\Order;
use Livewire\Component;
use Illuminate\Http\Request;

class PaymentCallback extends Component
{
    public $trackingCode;
    public $status;
    public $title;
    public $message;

    public function mount(Request $request)
    {
        // Resolve tracking code from query parameter
        $trackingParam = $request->query('tracking_code');

        // Fallback: Midtrans may also return order_id (which is our order_number)
        if (!$trackingParam && $request->query('order_id')) {
            $order = Order::where('order_number', $request->query('order_id'))->first();
            $trackingParam = $order?->tracking_code;
        }

        $this->trackingCode = $trackingParam;
        $routeName = request()->route()->getName();

        if ($routeName === 'customer.checkout.finish') {
            $this->status = 'success';
            $this->title = 'Pembayaran Berhasil / Sedang Diproses';
            $this->message = 'Terima kasih! Pesanan Anda segera disiapkan.';
        } elseif ($routeName === 'customer.checkout.unfinish') {
            $this->status = 'pending';
            $this->title = 'Pembayaran Tertunda';
            $this->message = 'Anda menutup layar pembayaran. Silakan lunasi tagihan Anda.';
        } elseif ($routeName === 'customer.checkout.error') {
            $this->status = 'error';
            $this->title = 'Pembayaran Gagal';
            $this->message = 'Maaf, terjadi kesalahan pada proses pembayaran Anda.';
        } else {
            $this->status = 'unknown';
            $this->title = 'Status Tidak Diketahui';
            $this->message = '';
        }
    }

    public function render()
    {
        return view('livewire.customer.payment-callback')->layout('layouts.customer');
    }
}
