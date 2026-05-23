<?php

namespace App\Presentation\Livewire\Kitchen;

use App\Domain\Order\Models\Order;
use Livewire\Component;

class KitchenDisplay extends Component
{
    // Toggle state for HTML5 audio notification
    public bool $soundEnabled = true;

    // Track the last seen count of pending orders to trigger audio when a new order arrives
    public int $lastPendingCount = 0;

    /**
     * Mount the component and initialize the last pending count.
     */
    public function mount(): void
    {
        $this->lastPendingCount = Order::where('status', 'pending')->count();
    }

    /**
     * Change the status of an order with robust state validation.
     * Allowed flow: pending -> confirmed -> preparing -> ready -> completed.
     *
     * @param int $orderId
     * @param string $newStatus
     */
    public function changeStatus(int $orderId, string $newStatus): void
    {
        $order = Order::findOrFail($orderId);

        // Define strict valid status transitions
        $allowedTransitions = [
            'pending' => 'confirmed',
            'confirmed' => 'preparing',
            'preparing' => 'ready',
            'ready' => 'completed',
        ];

        // Validate the transition
        if (!isset($allowedTransitions[$order->status]) || $allowedTransitions[$order->status] !== $newStatus) {
            session()->flash('error', "Transisi status dari '{$order->status}' ke '{$newStatus}' tidak diperbolehkan.");
            return;
        }

        // Update the order status
        $order->status = $newStatus;
        $order->save();

        session()->flash('success', "Pesanan {$order->order_number} berhasil diperbarui menjadi '{$newStatus}'.");

        // Immediately update our lastPendingCount to prevent triggering a sound for this change
        $this->lastPendingCount = Order::where('status', 'pending')->count();
    }

    /**
     * Toggle the notification sound setting.
     */
    public function toggleSound(): void
    {
        $this->soundEnabled = !$this->soundEnabled;
    }

    /**
     * Get premium Tailwind styling configurations based on order status.
     *
     * @param string $status
     * @return array
     */
    public function getStatusColor(string $status): array
    {
        return match ($status) {
            'pending' => [
                'border' => 'border-l-4 border-yellow-500',
                'bg' => 'bg-yellow-950/20 border-yellow-900/40',
                'badge' => 'bg-yellow-500/20 text-yellow-300 border border-yellow-500/30',
                'btn' => 'bg-yellow-600 hover:bg-yellow-500 text-gray-950 hover:scale-105 active:scale-95',
                'btn_text' => 'Terima Pesanan'
            ],
            'confirmed' => [
                'border' => 'border-l-4 border-blue-500',
                'bg' => 'bg-blue-950/20 border-blue-900/40',
                'badge' => 'bg-blue-500/20 text-blue-300 border border-blue-500/30',
                'btn' => 'bg-blue-600 hover:bg-blue-500 text-white hover:scale-105 active:scale-95',
                'btn_text' => 'Siapkan'
            ],
            'preparing' => [
                'border' => 'border-l-4 border-orange-500',
                'bg' => 'bg-orange-950/20 border-orange-900/40',
                'badge' => 'bg-orange-500/20 text-orange-300 border border-orange-500/30',
                'btn' => 'bg-orange-600 hover:bg-orange-500 text-white hover:scale-105 active:scale-95',
                'btn_text' => 'Selesai Masak'
            ],
            'ready' => [
                'border' => 'border-l-4 border-green-500',
                'bg' => 'bg-green-950/20 border-green-900/40',
                'badge' => 'bg-green-500/20 text-green-300 border border-green-500/30',
                'btn' => 'bg-green-600 hover:bg-green-500 text-white hover:scale-105 active:scale-95',
                'btn_text' => 'Sajikan & Selesai'
            ],
            default => [
                'border' => 'border-l-4 border-gray-600',
                'bg' => 'bg-gray-900 border-gray-800',
                'badge' => 'bg-gray-500/20 text-gray-400 border border-gray-600/30',
                'btn' => 'bg-gray-700 text-gray-300 cursor-not-allowed',
                'btn_text' => 'N/A'
            ],
        };
    }

    /**
     * Render the Livewire component.
     */
    public function render()
    {
        // Eager load related data: order items, toppings, spiciness level, and table
        $orders = Order::with(['orderItems.toppings', 'orderItems.spicinessLevel', 'table'])
            ->whereIn('status', ['pending', 'confirmed', 'preparing', 'ready'])
            ->orderBy('created_at', 'asc') // Oldest orders on top so kitchen works on them first
            ->get();

        $pendingCount = $orders->where('status', 'pending')->count();

        // If the number of pending orders has increased, dispatch the audio notification
        if ($pendingCount > $this->lastPendingCount) {
            if ($this->soundEnabled) {
                $this->dispatch('orderAdded');
            }
        }

        $this->lastPendingCount = $pendingCount;

        // Group counts for summary badge metrics
        $metrics = [
            'pending' => $orders->where('status', 'pending')->count(),
            'confirmed' => $orders->where('status', 'confirmed')->count(),
            'preparing' => $orders->where('status', 'preparing')->count(),
            'ready' => $orders->where('status', 'ready')->count(),
        ];

        return view('livewire.kitchen.kitchen-display', [
            'orders' => $orders,
            'metrics' => $metrics,
        ])->layout('layouts.kitchen');
    }
}
