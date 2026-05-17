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

// Rute Publik (Customer Scan)
Route::get('/menu/{token}', [\App\Http\Controllers\CustomerMenuController::class, 'scan'])->name('customer.menu');

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
});

// POS / Kasir Group
Route::middleware(['auth', 'role:Admin|Kasir'])->prefix('pos')->name('pos.')->group(function () {
    Route::get('/orders', function () {
        return "POS Dashboard";
    })->name('orders');
});

// Kitchen / Dapur Group
Route::middleware(['auth', 'role:Admin|Dapur'])->prefix('kitchen')->name('kitchen.')->group(function () {
    Route::get('/board', function () {
        return "Kitchen Dashboard";
    })->name('board');
});

// Driver Group
Route::middleware(['auth', 'role:Admin|Driver'])->prefix('driver')->name('driver.')->group(function () {
    Route::get('/deliveries', function () {
        return "Driver Dashboard";
    })->name('deliveries');
});
