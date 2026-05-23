<?php

namespace App\Presentation\Livewire\Customer;

use App\Domain\Table\Models\Table;
use Livewire\Component;

class Checkout extends Component
{
    public array $cart = [];
    public ?Table $table = null;
    
    public string $customerName = '';
    public string $notes = '';

    public function mount()
    {
        $this->cart = session('cart', []);
        
        if (empty($this->cart)) {
            return redirect()->route('customer.menu');
        }

        $tableId = session('table_id');
        if (!$tableId) {
            session()->flash('message', 'Anda belum memilih/scan meja.');
            session()->flash('message_type', 'error');
            return redirect()->route('customer.menu');
        }

        $this->table = Table::find($tableId);
        if (!$this->table) {
            return redirect()->route('customer.menu');
        }
    }

    public function placeOrder(\App\Application\Actions\Order\PlaceOrderAction $action)
    {
        // Validasi opsional
        $this->validate([
            'customerName' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:255',
        ]);

        try {
            // 1. Eksekusi Action Pembuatan Pesanan (status pending)
            $order = $action->execute(
                cart: $this->cart,
                tableId: $this->table?->id,
                customerName: $this->customerName,
                customerPhone: null, // belum ada di form
                notes: $this->notes
            );
            
            // 2. Bangun $itemDetails untuk Midtrans
            $itemDetails = [];
            foreach ($order->orderItems as $item) {
                $itemDetails[] = [
                    'id' => $item->menu_id ?? 'item-'.$item->id,
                    'price' => (int) ($item->price + $item->toppings->sum('price')),
                    'quantity' => $item->quantity,
                    'name' => mb_substr($item->item_name_snapshot, 0, 50),
                ];
            }

            // 3. Panggil Gateway Midtrans
            $snapToken = \App\Infrastructure\Payment\MidtransGateway::createTransaction($order, $itemDetails);

            // 4. Update status ke payment_pending
            $order->update(['status' => 'payment_pending']);
            
            // 5. Bersihkan session cart dan flash token
            session()->forget('cart');
            $this->dispatch('cartUpdated');
            session()->flash('snap_token', $snapToken);

            // 6. Redirect ke halaman Payment
            return redirect()->route('customer.payment', ['orderNumber' => $order->order_number]);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Order Error: ' . $e->getMessage());
            session()->flash('message', 'Terjadi kesalahan saat memproses pesanan.');
            session()->flash('message_type', 'error');
        }
    }

    public function render()
    {
        $total = collect($this->cart)->sum('subtotal');
        return view('livewire.customer.checkout', compact('total'))
            ->layout('layouts.customer');
    }
}
