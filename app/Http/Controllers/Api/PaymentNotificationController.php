<?php

namespace App\Http\Controllers\Api;

use App\Domain\Order\Models\Order;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
class PaymentNotificationController extends Controller
{
    public function __invoke(Request $request)
    {
        // Set konfigurasi midtrans
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');

        try {
            $notification = new \Midtrans\Notification();
            
            // Verifikasi signature key
            $serverKey = config('midtrans.server_key');
            $signatureKey = hash('sha512', $notification->order_id . $notification->status_code . $notification->gross_amount . $serverKey);
            
            if ($signatureKey !== $notification->signature_key) {
                Log::warning("Midtrans Notification: Invalid signature key for Order {$notification->order_id}.");
                return response()->json(['message' => 'Invalid signature key'], 403);
            }
            
            $transactionStatus = $notification->transaction_status;
            $orderId = $notification->order_id;
            $transactionId = $notification->transaction_id;

            $order = Order::where('order_number', $orderId)->first();

            if (!$order) {
                Log::warning("Midtrans Notification: Order $orderId not found.");
                return response()->json(['message' => 'Order not found'], 404);
            }

            // Update status berdasarkan transaction_status
            if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
                $order->update([
                    'status' => 'paid',
                    'midtrans_transaction_id' => $transactionId
                ]);
                // Note: session()->forget('cart') here is in webhook (API) context — it does NOT
                // affect the customer's browser session. Cart is cleared in PaymentCallback.php
                // when the customer lands back on the site after payment.
                session()->forget('cart');
            } else if ($transactionStatus == 'pending') {
                $order->update(['status' => 'payment_pending']);
            } else if ($transactionStatus == 'deny' || $transactionStatus == 'expire' || $transactionStatus == 'cancel') {
                $order->update(['status' => 'cancelled']);
            }

            return response()->json(['message' => 'OK']);
            
        } catch (\Exception $e) {
            Log::error('Midtrans Notification Error: ' . $e->getMessage());
            return response()->json(['message' => 'Error handling notification'], 500);
        }
    }
}
