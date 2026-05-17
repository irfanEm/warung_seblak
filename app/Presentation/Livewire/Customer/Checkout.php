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

    public function placeOrder()
    {
        // Validasi opsional
        $this->validate([
            'customerName' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:255',
        ]);

        // TODO: Simpan ke tabel Orders di database (Integrasi DB)
        
        // Bersihkan session
        session()->forget('cart');
        
        // Flash message
        session()->flash('message', 'Pesanan berhasil dibuat! Makanan segera diantar ke meja Anda.');
        session()->flash('message_type', 'success');

        // Redirect
        return redirect()->route('customer.menu');
    }

    public function render()
    {
        $total = collect($this->cart)->sum('subtotal');
        return view('livewire.customer.checkout', compact('total'))
            ->layout('layouts.customer');
    }
}
