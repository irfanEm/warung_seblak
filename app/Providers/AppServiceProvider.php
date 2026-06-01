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
        // Auto-discovery via config/livewire.php class_namespace
    }
}
