<?php

return [
    'server_key' => env('MIDTRANS_SERVER_KEY'),
    'client_key' => env('MIDTRANS_CLIENT_KEY'),
    'merchant_id' => env('MIDTRANS_MERCHANT_ID'),
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
    'sanitized' => env('MIDTRANS_SANITIZED', true),
    '3ds' => env('MIDTRANS_3DS', true),

    /*
    |--------------------------------------------------------------------------
    | IP Whitelist for Webhook Notifications
    |--------------------------------------------------------------------------
    |
    | Comma-separated CIDR ranges of Midtrans notification servers.
    | Update these with the latest IPs from Midtrans documentation:
    | https://docs.midtrans.com/reference/payment-notification-service
    |
    */
    'whitelist_ips' => array_filter(
        array_map('trim', explode(',', env('MIDTRANS_WHITELIST_IPS', '')))
    ),
];
