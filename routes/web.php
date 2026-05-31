<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

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
Route::get('/menu/{token}', [\App\Http\Controllers\CustomerMenuController::class, 'scan'])
    ->where('token', '[A-Za-z0-9\-\_]+')
    ->name('customer.scan');

Route::get('/menu', function () {
    return view('customer.menu');
})->name('customer.menu');
Route::get('/cart', \App\Presentation\Livewire\Customer\Cart::class)->name('customer.cart');
Route::get('/checkout', \App\Livewire\Customer\Checkout::class)->name('customer.checkout');
Route::get('/order/success/{order_number}', \App\Livewire\Customer\OrderSuccess::class)->name('customer.order.success');
Route::get('/order/tracking/{order_number}', \App\Livewire\Customer\OrderTracking::class)->name('customer.order.tracking');
Route::get('/payment/{orderNumber}', \App\Presentation\Livewire\Customer\Payment::class)->name('customer.payment');
Route::get('/checkout/finish', \App\Presentation\Livewire\Customer\PaymentCallback::class)->name('customer.checkout.finish');
Route::get('/checkout/unfinish', \App\Presentation\Livewire\Customer\PaymentCallback::class)->name('customer.checkout.unfinish');
Route::get('/checkout/error', \App\Presentation\Livewire\Customer\PaymentCallback::class)->name('customer.checkout.error');

// Admin Group
Route::middleware(['auth', 'role:Admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', \App\Presentation\Livewire\Admin\AdminDashboard::class)->name('dashboard');

    // TODO: Tambahkan middleware auth dan role admin (Sudah tersemat di middleware group ini)
    
    // Menu Management (Livewire 3 Session-based CRUD)
    Route::get('/menus', \App\Livewire\Admin\MenuList::class)->name('menus.index');

    // Category Management
    Route::get('/categories', \App\Livewire\Admin\CategoryList::class)->name('categories.index');

    // Topping Management
    Route::get('/toppings', \App\Livewire\Admin\ToppingList::class)->name('toppings.index');

    // Spiciness Levels Management
    Route::get('/spiciness', \App\Livewire\Admin\SpicinessLevelList::class)->name('spiciness.index');

    // Table Management
    Route::get('/tables', \App\Livewire\Admin\TableList::class)->name('tables.index');
    Route::get('/tables/{id}/print', function ($id) {
        $tables = session('admin.tables', []);
        $table = collect($tables)->firstWhere('id', (int) $id);
        if (!$table) abort(404, 'Meja tidak ditemukan');
        return view('admin.tables.print', ['table' => $table]);
    })->name('tables.print');
});

// POS / Kasir Group
Route::middleware(['auth', 'role:Admin|Kasir'])->prefix('pos')->name('pos.')->group(function () {
    Route::get('/', \App\Presentation\Livewire\Pos\PosScreen::class)->name('index');
    Route::get('/history', \App\Presentation\Livewire\Pos\PosHistory::class)->name('history');
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
