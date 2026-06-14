<?php

declare(strict_types=1);

namespace App\Infrastructure\Payment;

use App\Contracts\PaymentGatewayInterface;
use App\Domain\Order\Models\Order;

class MidtransGateway implements PaymentGatewayInterface
{
    public function __construct()
    {
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$clientKey = config('midtrans.client_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = config('midtrans.sanitized');
        \Midtrans\Config::$is3ds = config('midtrans.3ds');
    }

    public function createTransaction(Order $order, array $itemDetails, array $customerDetails = []): array
    {
        $params = [
            'transaction_details' => [
                'order_id' => $order->order_number,
                'gross_amount' => intdiv((int) $order->total, 100), // cents → Rupiah
            ],
            'customer_details' => !empty($customerDetails) ? $customerDetails : [
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

        $token = \Midtrans\Snap::getSnapToken($params);

        return [
            'token' => $token,
            'redirect_url' => config('midtrans.is_production')
                ? "https://app.midtrans.com/snap/v2/vtweb/{$token}"
                : "https://app.sandbox.midtrans.com/snap/v2/vtweb/{$token}",
        ];
    }
}
