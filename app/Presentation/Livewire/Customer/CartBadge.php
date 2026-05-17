<?php

namespace App\Presentation\Livewire\Customer;

use Livewire\Attributes\On;
use Livewire\Component;

class CartBadge extends Component
{
    public int $count = 0;

    public function mount()
    {
        $this->count = count(session('cart', []));
    }

    #[On('cartUpdated')]
    public function refreshCount()
    {
        $this->count = count(session('cart', []));
    }

    public function render()
    {
        return view('livewire.customer.cart-badge');
    }
}
