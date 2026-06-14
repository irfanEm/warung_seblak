<?php

namespace App\Presentation\Livewire\Admin;

use App\Domain\Menu\Models\Menu;
use App\Domain\Order\Models\Order;
use App\Domain\Table\Models\Table;
use App\Traits\HasRoleAuthorization;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class AdminDashboard extends Component
{
    use HasRoleAuthorization;

    public function mount()
    {
        $this->authorizeRole('Admin');
    }

    public function render()
    {
        // Metrics for today
        $totalOrdersToday = Order::whereDate('created_at', today())->count();
        $revenueToday = Order::whereDate('created_at', today())
            ->whereNotIn('status', ['cancelled'])
            ->sum('total');
        $activeOrders = Order::whereNotIn('status', ['completed', 'cancelled'])->count();
        $availableMenus = Menu::where('is_available', true)->count();
        $activeTables = Table::where('is_active', true)->count();

        // Recent orders (last 5)
        $recentOrders = Order::with('orderItems')
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        return view('livewire.admin.dashboard', compact(
            'totalOrdersToday',
            'revenueToday',
            'activeOrders',
            'availableMenus',
            'activeTables',
            'recentOrders'
        ));
    }
}
