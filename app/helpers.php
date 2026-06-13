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
