<?php

namespace App\Livewire\Customer;

use Livewire\Component;

class Checkout extends Component
{
    public array $items = [];
    public ?string $tableNumber = null;
    public float $subtotal = 0;
    public float $tax = 0;
    public float $total = 0;

    // Form fields
    public string $customerName = '';
    public string $customerPhone = '';

    /**
     * Memuat data keranjang dan menghitung subtotal/total pembayaran.
     */
    public function mount(): void
    {
        $this->items = session()->get('cart.items', []);
        
        // Alihkan jika keranjang masih kosong dengan pesan flash
        if (empty($this->items)) {
            session()->flash('error', 'Keranjang Anda kosong. Silakan pilih menu terlebih dahulu.');
            $this->redirect(route('customer.menu'));
            return;
        }

        $this->tableNumber = session()->get('table_number');

        // Hitung Subtotal (termasuk kustomisasi topping)
        $this->subtotal = 0;
        foreach ($this->items as $item) {
            $unitPrice = (float) $item['price'];
            foreach ($item['toppings'] as $topping) {
                $unitPrice += (float) $topping['price'];
            }
            $this->subtotal += $unitPrice * $item['quantity'];
        }

        $this->tax = 0; // PPN 0% untuk mock saat ini
        $this->total = $this->subtotal + $this->tax;
    }

    /**
     * Memproses pesanan (menyetel order dummy dan mengarahkan ke halaman sukses).
     */
    public function processPayment(): void
    {
        // Validasi input
        $this->validate([
            'customerName' => 'required|string|min:3|max:100',
            'customerPhone' => 'required|string|min:9|max:15',
        ], [
            'customerName.required' => 'Nama Pemesan wajib diisi.',
            'customerName.min' => 'Nama minimal terdiri dari 3 karakter.',
            'customerPhone.required' => 'Nomor Telepon wajib diisi.',
            'customerPhone.min' => 'Nomor Telepon minimal terdiri dari 9 digit.',
        ]);

        // Simulasikan loading state sebentar
        usleep(300000); // 300ms delay

        // Generate Nomor Invoice Dummy ter-pad dengan aman
        $orderNumber = 'INV-' . date('Ymd') . '-' . str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);

        // TODO: Integrate Midtrans Snap Payment Gateway
        // Di masa mendatang, logic Midtrans Snap Token creation akan disisipkan di sini.

        // Simpan data order dummy ke session agar dibaca halaman berikutnya
        session()->put('last_order', [
            'order_number' => $orderNumber,
            'customer_name' => $this->customerName,
            'customer_phone' => $this->customerPhone,
            'table_number' => $this->tableNumber,
            'items' => $this->items,
            'total' => $this->total,
            'status' => 'paid',
            'created_at' => now()->toDateTimeString()
        ]);

        // Bersihkan isi keranjang belanja di session setelah checkout berhasil
        session()->forget('cart.items');

        // Reset badge keranjang navigasi ke 0 secara real-time
        $this->dispatch('cart-count-updated', 0);

        // Alihkan pelanggan ke halaman sukses
        redirect()->route('customer.order.success', ['order_number' => $orderNumber]);
    }

    public function render()
    {
        return view('livewire.customer.checkout');
    }
}
