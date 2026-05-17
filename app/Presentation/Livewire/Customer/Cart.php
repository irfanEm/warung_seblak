<?php

namespace App\Presentation\Livewire\Customer;

use Livewire\Component;

class Cart extends Component
{
    public array $cart = [];

    public function mount()
    {
        $this->cart = session('cart', []);
    }

    public function updateQuantity($key, $qty)
    {
        if (isset($this->cart[$key])) {
            $qty = max(1, $qty);
            $this->cart[$key]['quantity'] = $qty;
            
            // Hitung ulang subtotal
            $unitPrice = $this->cart[$key]['price'];
            foreach ($this->cart[$key]['toppings'] as $topping) {
                $unitPrice += $topping['price'];
            }
            $this->cart[$key]['subtotal'] = $unitPrice * $qty;
            
            $this->saveSession();
        }
    }

    public function removeItem($key)
    {
        if (isset($this->cart[$key])) {
            unset($this->cart[$key]);
            $this->saveSession();
            session()->flash('message', 'Item dihapus dari keranjang.');
        }
    }

    private function saveSession()
    {
        session(['cart' => $this->cart]);
        $this->dispatch('cartUpdated');
    }

    public function render()
    {
        $total = collect($this->cart)->sum('subtotal');
        
        return view('livewire.customer.cart', compact('total'))
            ->layout('layouts.customer');
    }
}
