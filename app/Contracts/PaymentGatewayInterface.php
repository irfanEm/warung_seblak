<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Domain\Order\Models\Order;

interface PaymentGatewayInterface
{
    /**
     * Create a payment transaction and return the snap token + redirect URL.
     *
     * @param  Order  $order           The order to charge.
     * @param  array  $itemDetails     Midtrans-formatted item details.
     * @param  array  $customerDetails Optional customer info override.
     * @return array{token: string, redirect_url: string}
     */
    public function createTransaction(Order $order, array $itemDetails, array $customerDetails = []): array;
}
