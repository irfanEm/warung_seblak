<?php

namespace App\Livewire\Customer;

use Livewire\Component;

class OrderTracking extends Component
{
    public string $orderNumber = '';
    public string $status = 'paid';
    
    // Status alur pelacakan pesanan seblak
    public array $statusHistory = [
        'paid' => [
            'label' => 'Pembayaran Lunas',
            'desc' => 'Pembayaran berhasil dikonfirmasi oleh sistem.',
            'step' => 1
        ],
        'confirmed' => [
            'label' => 'Pesanan Diterima',
            'desc' => 'Pesanan telah diterima kasir & masuk antrean dapur.',
            'step' => 2
        ],
        'preparing' => [
            'label' => 'Sedang Dimasak',
            'desc' => 'Koki sedang meracik bumbu & memasak seblak Anda.',
            'step' => 3
        ],
        'ready' => [
            'label' => 'Siap Disajikan',
            'desc' => 'Seblak panas siap disajikan ke meja atau diambil!',
            'step' => 4
        ]
    ];

    /**
     * Menginisialisasi nomor pesanan dan status.
     */
    public function mount(string $order_number): void
    {
        $this->orderNumber = $order_number;

        // Ambil data order dari session untuk menjaga sinkronisasi status
        $orderData = session()->get('last_order');
        if ($orderData && $orderData['order_number'] === $order_number) {
            $this->status = $orderData['status'] ?? 'paid';
        }
    }

    /**
     * Memajukan status secara berkala melalui polling wire:poll.
     */
    public function updateStatus(): void
    {
        if ($this->status === 'paid') {
            $this->status = 'confirmed';
        } elseif ($this->status === 'confirmed') {
            $this->status = 'preparing';
        } elseif ($this->status === 'preparing') {
            $this->status = 'ready';
        }

        // Simpan status terbaru ke session agar sinkron saat reload halaman
        $orderData = session()->get('last_order');
        if ($orderData && $orderData['order_number'] === $this->orderNumber) {
            $orderData['status'] = $this->status;
            session()->put('last_order', $orderData);
        }
    }

    /**
     * Mendapatkan estimasi waktu saji berdasarkan status pesanan saat ini.
     */
    public function getEstimatedTime(): string
    {
        return match ($this->status) {
            'paid' => 'Menunggu konfirmasi kasir...',
            'confirmed' => '± 20 menit',
            'preparing' => '± 10 menit lagi',
            'ready' => 'Silakan ambil di kasir!',
            default => 'Memproses...'
        };
    }

    public function render()
    {
        // Hitung langkah progres saat ini
        $currentStep = $this->statusHistory[$this->status]['step'] ?? 1;

        return view('livewire.customer.order-tracking', [
            'currentStep' => $currentStep
        ]);
    }
}
