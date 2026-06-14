<?php

if (! function_exists('formatRupiah')) {
    /**
     * Format an integer amount (in cents/sen) to Indonesian Rupiah display string.
     *
     * @param  int  $amount  Amount in cents (e.g. 1200000 = Rp 12.000)
     * @return string
     */
    function formatRupiah(int $amount): string
    {
        return 'Rp ' . number_format(intdiv($amount, 100), 0, ',', '.');
    }
}

if (! function_exists('formatRupiahShort')) {
    /**
     * Format Rupiah without the "Rp " prefix, for use inside compact UI elements.
     *
     * @param  int  $amount  Amount in cents
     * @return string
     */
    function formatRupiahShort(int $amount): string
    {
        return number_format(intdiv($amount, 100), 0, ',', '.');
    }
}

if (! function_exists('generateTrackingCode')) {
    /**
     * Generate a unique 8-character alphanumeric tracking code.
     * Avoids ambiguous characters (0/O, 1/l/I) for readability.
     *
     * @return string 8-character uppercase alphanumeric code
     */
    function generateTrackingCode(): string
    {
        // Unambiguous characters only: digits 2-9 and uppercase letters minus O, I, L
        $chars = '23456789ABCDEFGHJKMNPQRSTUVWXYZ';
        $length = 8;
        $max = strlen($chars) - 1;

        do {
            $code = '';
            for ($i = 0; $i < $length; $i++) {
                $code .= $chars[random_int(0, $max)];
            }
        } while (\App\Domain\Order\Models\Order::where('tracking_code', $code)->exists());

        return $code;
    }
}
