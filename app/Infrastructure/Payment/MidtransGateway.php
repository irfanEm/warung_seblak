<?php

namespace App\Infrastructure\Payment;

use App\Domain\Order\Models\Order;
class MidtransGateway
{
    /**
     * Set the Midtrans configuration.
     */
    private static function configure()
    {
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$clientKey = config('midtrans.client_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = config('midtrans.sanitized');
        \Midtrans\Config::$is3ds = config('midtrans.3ds');
    }

    /**
     * Create a new transaction in Midtrans and get the Snap Token.
     */
    public static function createTransaction(Order $order, array $itemDetails): string
    {
        self::configure();

        $params = [
            'transaction_details' => [
                'order_id' => $order->order_number,
                'gross_amount' => intdiv((int) $order->total, 100), // Convert cents to Rupiah
            ],
            'customer_details' => [
                'first_name' => $order->customer_name ?? 'Pelanggan',
                'phone' => $order->customer_phone ?? '',
            ],
            'item_details' => $itemDetails,
            'callbacks' => [
                'finish' => route('customer.checkout.finish', ['tracking_code' => $order->tracking_code]),
                'unfinish' => route('customer.checkout.unfinish', ['tracking_code' => $order->tracking_code]),
                'error' => route('customer.checkout.error', ['tracking_code' => $order->tracking_code]),
            ],
        ];

        return \Midtrans\Snap::getSnapToken($params);
    }
}
