<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use Livewire\Attributes\On;

class Cart extends Component
{
    public array $items = [];

    /**
     * Inisialisasi keranjang belanja dari session.
     */
    public function mount(): void
    {
        $this->items = session()->get('cart.items', []);
        
        // Emit jumlah item pertama kali dimuat agar badge layout sinkron
        $this->dispatchCartCount();
    }

    /**
     * Menambahkan item ke keranjang belanja.
     * Menggabungkan item jika nama menu, tingkat kepedasan (ID), dan topping (ID) sama persis.
     */
    #[On('add-to-cart')]
    public function addItem(array $itemData): void
    {
        $foundKey = null;

        // Urutkan topping berdasarkan ID agar pembandingan topping sama persis (independen dari urutan klik)
        $newToppings = $itemData['toppings'];
        usort($newToppings, fn($a, $b) => $a['id'] <=> $b['id']);

        foreach ($this->items as $index => $existingItem) {
            // Bandingkan ID menu dan ID tingkat pedas
            if ($existingItem['menu_id'] === $itemData['menu_id'] && $existingItem['spiciness']['id'] === $itemData['spiciness']['id']) {
                $existingToppings = $existingItem['toppings'];
                usort($existingToppings, fn($a, $b) => $a['id'] <=> $b['id']);

                if (json_encode($existingToppings) === json_encode($newToppings)) {
                    $foundKey = $index;
                    break;
                }
            }
        }

        if ($foundKey !== null) {
            $this->items[$foundKey]['quantity'] += $itemData['quantity'];
        } else {
            $itemData['toppings'] = $newToppings; // Simpan topping yang sudah terurut
            $this->items[] = $itemData;
        }

        $this->saveCart();

        // Emit event agar panel keranjang otomatis terbuka secara visual
        $this->dispatch('open-cart');
    }

    /**
     * Memperbarui kuantitas item.
     */
    public function updateQuantity(int $index, int $qty): void
    {
        if (isset($this->items[$index])) {
            if ($qty > 0) {
                $this->items[$index]['quantity'] = $qty;
            } else {
                $this->removeItem($index);
                return;
            }
            $this->saveCart();
        }
    }

    /**
     * Menghapus item dari keranjang.
     */
    public function removeItem(int $index): void
    {
        if (isset($this->items[$index])) {
            unset($this->items[$index]);
            $this->items = array_values($this->items); // Reset indeks array agar berurutan
            $this->saveCart();
        }
    }

    /**
     * Menyimpan data keranjang ke session dan memperbarui badge layout.
     */
    protected function saveCart(): void
    {
        session()->put('cart.items', $this->items);
        $this->dispatchCartCount();
    }

    /**
     * Memancarkan event jumlah item belanjaan.
     */
    protected function dispatchCartCount(): void
    {
        $totalCount = (int) collect($this->items)->sum('quantity');
        $this->dispatch('cart-count-updated', $totalCount);
    }

    /**
     * Menghitung total harga belanjaan secara keseluruhan (termasuk topping).
     */
    public function getTotalPrice(): float
    {
        $grandTotal = 0;
        foreach ($this->items as $item) {
            $itemTotal = (float) $item['price'];
            foreach ($item['toppings'] as $topping) {
                $itemTotal += (float) $topping['price'];
            }
            $grandTotal += $itemTotal * $item['quantity'];
        }
        return $grandTotal;
    }

    public function render()
    {
        return view('livewire.customer.cart', [
            'totalPrice' => $this->getTotalPrice()
        ]);
    }
}
