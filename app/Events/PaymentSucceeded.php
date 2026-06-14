<?php

declare(strict_types=1);

namespace App\Events;

use App\Domain\Order\Models\Order;
use Illuminate\Foundation\Events\Dispatchable;

class PaymentSucceeded
{
    use Dispatchable;

    public function __construct(public readonly Order $order)
    {
    }
}
