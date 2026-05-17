<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Domain\Menu\Repositories\MenuRepositoryInterface::class, 
            \App\Infrastructure\Persistence\Eloquent\MenuRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Livewire\Livewire::component('customer.cart-badge', \App\Presentation\Livewire\Customer\CartBadge::class);
        \Livewire\Livewire::component('customer.customer-menu', \App\Presentation\Livewire\Customer\CustomerMenu::class);
        \Livewire\Livewire::component('customer.cart', \App\Presentation\Livewire\Customer\Cart::class);
        \Livewire\Livewire::component('customer.checkout', \App\Presentation\Livewire\Customer\Checkout::class);
    }
}
