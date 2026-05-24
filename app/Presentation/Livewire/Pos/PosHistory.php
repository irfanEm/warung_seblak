<?php

namespace App\Presentation\Livewire\Pos;

use App\Domain\Order\Models\Order;
use Livewire\Component;

class PosHistory extends Component
{
    // Date filter state (defaults to today's date)
    public string $filterDate = '';

    /**
     * Mount component and initialize filter to today.
     */
    public function mount(): void
    {
        $this->filterDate = now()->format('Y-m-d');
    }

    /**
     * Render the component with filtered orders and metrics.
     */
    public function render()
    {
        $query = Order::with(['orderItems.toppings', 'orderItems.spicinessLevel', 'table'])
            ->whereIn('type', ['dine_in', 'takeaway']);

        // Filter by specific date if provided
        if (!empty($this->filterDate)) {
            $query->whereDate('created_at', $this->filterDate);
        }

        // Retrieve orders
        $orders = $query->orderBy('created_at', 'desc')->get();

        // Calculate summary metrics for the cashier shift (filter status paid or completed)
        $posFinishedOrders = $orders->whereIn('status', ['paid', 'completed']);
        $totalRevenue = $posFinishedOrders->sum('total');
        $totalOrdersCount = $posFinishedOrders->count();

        return view('livewire.pos.pos-history', [
            'orders' => $orders,
            'totalRevenue' => $totalRevenue,
            'totalOrdersCount' => $totalOrdersCount,
        ])->layout('layouts.pos');
    }
}
