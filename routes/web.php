<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', '/menu');

// Autentikasi
Route::middleware('guest')->group(function () {
    Route::get('/login', \App\Presentation\Livewire\Auth\Login::class)->name('login');
});

Route::post('/logout', function () {
    Illuminate\Support\Facades\Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

// Rute Publik (Customer Scan & Pemesanan)
Route::get('/menu', \App\Presentation\Livewire\Customer\CustomerMenu::class)->name('customer.menu');
Route::get('/menu/{token}', [\App\Http\Controllers\CustomerMenuController::class, 'scan'])
    ->where('token', '[A-Za-z0-9\-\_]+')
    ->name('customer.scan');
Route::get('/orders', \App\Presentation\Livewire\Customer\OrderLookup::class)->name('customer.orders');
Route::get('/orders/tracking/{trackingCode}', \App\Presentation\Livewire\Customer\OrderTracking::class)->name('customer.order.tracking');
Route::get('/cart', \App\Presentation\Livewire\Customer\Cart::class)->name('customer.cart');
Route::get('/checkout', \App\Presentation\Livewire\Customer\Checkout::class)->name('customer.checkout');
Route::get('/payment/{trackingCode}', \App\Presentation\Livewire\Customer\Payment::class)->name('customer.payment');
Route::get('/checkout/finish', \App\Presentation\Livewire\Customer\PaymentCallback::class)->name('customer.checkout.finish');
Route::get('/checkout/unfinish', \App\Presentation\Livewire\Customer\PaymentCallback::class)->name('customer.checkout.unfinish');
Route::get('/checkout/error', \App\Presentation\Livewire\Customer\PaymentCallback::class)->name('customer.checkout.error');

// Admin Group
Route::middleware(['auth', 'role:Admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', \App\Presentation\Livewire\Admin\AdminDashboard::class)->name('dashboard');
    
    // Menu Management
    Route::get('/menus', \App\Presentation\Livewire\Admin\Menu\ListMenu::class)->name('menu.index');
    Route::get('/menus/create', \App\Presentation\Livewire\Admin\Menu\MenuForm::class)->name('menu.create');
    Route::get('/menus/{menuId}/edit', \App\Presentation\Livewire\Admin\Menu\MenuForm::class)->name('menu.edit');

    // Table Management
    Route::get('/tables', \App\Presentation\Livewire\Admin\Table\ListTable::class)->name('table.index');
    Route::get('/tables/create', \App\Presentation\Livewire\Admin\Table\TableForm::class)->name('table.create');
    Route::get('/tables/{tableId}/edit', \App\Presentation\Livewire\Admin\Table\TableForm::class)->name('table.edit');
    Route::get('/tables/{tableId}/print', [\App\Http\Controllers\TablePrintController::class, 'print'])->name('table.print');

    // User Management
    Route::get('/users', \App\Presentation\Livewire\Admin\User\ListUser::class)->name('users.index');
});

// POS / Kasir Group
Route::middleware(['auth', 'role:Admin|Kasir'])->prefix('pos')->name('pos.')->group(function () {
    Route::get('/', \App\Presentation\Livewire\Pos\PosScreen::class)->name('index');
    Route::get('/history', \App\Presentation\Livewire\Pos\PosHistory::class)->name('history');
    Route::get('/receipt/{orderId}', function (int $orderId) {
        $order = \App\Domain\Order\Models\Order::with('orderItems.toppings')->findOrFail($orderId);
        $outlet = \App\Domain\Outlet\Models\Outlet::first();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pos.receipt', compact('order', 'outlet'));
        $pdf->setPaper([0, 0, 226.77, 800], 'portrait'); // 80mm width

        return $pdf->stream("struk-{$order->order_number}.pdf");
    })->name('receipt');
});

// Kitchen / Dapur Group
Route::middleware(['auth', 'role:Admin|Dapur'])->group(function () {
    Route::get('/kitchen', \App\Presentation\Livewire\Kitchen\KitchenDisplay::class)->name('kitchen.index');
    
    // Redirect old dashboard to the new KDS screen
    Route::get('/kitchen/board', function () {
        return redirect()->route('kitchen.index');
    })->name('kitchen.board');
});

// Driver Group
Route::middleware(['auth', 'role:Admin|Driver'])->prefix('driver')->name('driver.')->group(function () {
    Route::get('/deliveries', function () {
        return "Driver Dashboard";
    })->name('deliveries');
});
