<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\PaymentSucceeded;

class ClearCart
{
    /**
     * Clear the session-based customer cart after successful payment.
     */
    public function handle(PaymentSucceeded $event): void
    {
        session()->forget('cart');
    }
}
