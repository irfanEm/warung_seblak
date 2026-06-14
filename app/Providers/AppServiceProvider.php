<?php

declare(strict_types=1);

namespace App\Providers;

use App\Contracts\PaymentGatewayInterface;
use App\Events\OrderPlaced;
use App\Events\PaymentSucceeded;
use App\Infrastructure\Payment\MidtransGateway;
use App\Listeners\ClearCart;
use App\Listeners\ReduceStock;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PaymentGatewayInterface::class, MidtransGateway::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(OrderPlaced::class, ReduceStock::class);
        Event::listen(PaymentSucceeded::class, ClearCart::class);
    }
}
