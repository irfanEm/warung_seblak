<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use Livewire\Attributes\On;

class MenuDetail extends Component
{
    public ?array $menu = null;
    public array $selectedToppings = [];
    public int $selectedSpiciness = 2; // Default ke ID 2 (Level 1)
    public int $quantity = 1;

    // Daftar topping dummy khas seblak terstruktur dengan ID
    public array $toppingsList = [
        ['id' => 1, 'name' => 'Telur Ayam Oranye', 'price' => 3000],
        ['id' => 2, 'name' => 'Keju Melted Parut', 'price' => 5000],
        ['id' => 3, 'name' => 'Sosis Jumbo Slice', 'price' => 4000],
        ['id' => 4, 'name' => 'Bakso Sapi Kenyal', 'price' => 4000],
        ['id' => 5, 'name' => 'Ceker Ayam Empuk', 'price' => 3000],
    ];

    // Daftar tingkat kepedasan seblak terstruktur dengan ID
    public array $spicinessList = [
        ['id' => 1, 'name' => 'Tidak Pedas'],
        ['id' => 2, 'name' => 'Level 1'],
        ['id' => 3, 'name' => 'Level 2'],
        ['id' => 4, 'name' => 'Level 3'],
        ['id' => 5, 'name' => 'Level 4'],
        ['id' => 6, 'name' => 'Level 5'],
    ];

    /**
     * Listener ketika tombol "Tambah" di MenuList ditekan.
     * Mengisi state internal dan men-trigger modal di sisi browser.
     */
    #[On('open-menu-detail')]
    public function loadMenu(array $menu): void
    {
        $this->menu = $menu;
        $this->selectedToppings = [];
        $this->selectedSpiciness = 2; // ID 2 = Level 1
        $this->quantity = 1;
        
        // Memancarkan event browser agar Alpine.js membuka modal overlay
        $this->dispatch('show-detail-modal');
    }

    /**
     * Menambah kuantitas item.
     */
    public function increment(): void
    {
        $this->quantity++;
    }

    /**
     * Mengurangi kuantitas item (minimal 1).
     */
    public function decrement(): void
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    /**
     * Memproses penambahan menu ke keranjang belanja (Cart).
     */
    public function addToCart(): void
    {
        // Validasi menu terunggah
        if (!$this->menu) {
            return;
        }

        // Validasi kuantitas (integer positif minimal 1)
        if (!is_int($this->quantity) || $this->quantity < 1) {
            session()->flash('error', 'Kuantitas pesanan tidak valid.');
            return;
        }

        // Cari detail topping pilihan berdasarkan ID
        $toppingDetails = [];
        foreach ($this->selectedToppings as $toppingId) {
            foreach ($this->toppingsList as $topping) {
                if ($topping['id'] == $toppingId) {
                    $toppingDetails[] = [
                        'id' => (int) $topping['id'],
                        'name' => $topping['name'],
                        'price' => (float) $topping['price']
                    ];
                    break;
                }
            }
        }

        // Cari detail tingkat pedas pilihan berdasarkan ID
        $spicinessDetail = ['id' => 2, 'name' => 'Level 1']; // default
        foreach ($this->spicinessList as $spiciness) {
            if ($spiciness['id'] == $this->selectedSpiciness) {
                $spicinessDetail = [
                    'id' => (int) $spiciness['id'],
                    'name' => $spiciness['name']
                ];
                break;
            }
        }

        // Susun data item belanjaan terstruktur
        $itemData = [
            'menu_id' => (int) $this->menu['id'],
            'name' => $this->menu['name'],
            'price' => (float) $this->menu['price'],
            'quantity' => (int) $this->quantity,
            'toppings' => $toppingDetails,
            'spiciness' => $spicinessDetail,
        ];

        // Pancarkan event global agar ditangkap oleh komponen Cart.php
        $this->dispatch('add-to-cart', itemData: $itemData);

        // Pancarkan event browser agar Alpine menutup modal secara instan
        $this->dispatch('hide-detail-modal');
    }

    public function render()
    {
        return view('livewire.customer.menu-detail');
    }
}
