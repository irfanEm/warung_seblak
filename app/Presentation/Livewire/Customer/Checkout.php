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
        if ($tableId) {
            $this->table = Table::find($tableId);
        }
    }

    public function placeOrder(\App\Application\Actions\Order\PlaceOrderAction $action)
    {
        // Validasi opsional
        $this->validate([
            'customerName' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:255',
        ]);
        
        $this->customerName = strip_tags($this->customerName);

        try {
            // 1. Eksekusi Action Pembuatan Pesanan (status pending)
            $order = $action->execute(
                cart: $this->cart,
                tableId: $this->table?->id,
                customerName: $this->customerName,
                customerPhone: null, // belum ada di form
                notes: $this->notes
            );
            
            // 2. Bangun $itemDetails untuk Midtrans (convert cents → Rupiah)
            $itemDetails = [];
            foreach ($order->orderItems as $item) {
                $unitPriceCents = (int) ($item->price + $item->toppings->sum('price'));
                $itemDetails[] = [
                    'id' => $item->menu_id ?? 'item-'.$item->id,
                    'price' => intdiv($unitPriceCents, 100),
                    'quantity' => $item->quantity,
                    'name' => mb_substr($item->item_name_snapshot, 0, 50),
                ];
            }

            // 3. Panggil Gateway Midtrans
            $snapToken = \App\Infrastructure\Payment\MidtransGateway::createTransaction($order, $itemDetails);

            // 4. Update status ke payment_pending dan simpan snap_token
            $order->update([
                'status' => 'payment_pending',
                'snap_token' => $snapToken
            ]);
            
            // 5. Dispatch event update cart (jangan hapus session di sini)
            $this->dispatch('cartUpdated');

            // 6. Redirect ke halaman Payment
            return redirect()->route('customer.payment', ['trackingCode' => $order->tracking_code]);

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
