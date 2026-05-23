<div class="px-4 py-8">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 text-center">
        <h1 class="text-xl font-bold text-gray-900 mb-2">Pembayaran Pesanan</h1>
        <p class="text-sm text-gray-500 mb-6">Selesaikan pembayaran Anda agar pesanan segera kami siapkan.</p>
        
        <div class="bg-gray-50 rounded-xl p-4 mb-6 text-left">
            <p class="text-xs text-gray-500 font-medium mb-1">Nomor Pesanan</p>
            <p class="text-sm font-bold text-gray-900 mb-3">{{ $order->order_number }}</p>
            
            <p class="text-xs text-gray-500 font-medium mb-1">Total Tagihan</p>
            <p class="text-2xl font-black text-amber-600">Rp {{ number_format($order->total, 0, ',', '.') }}</p>
        </div>

        <!-- Tombol Bayar -->
        <button id="pay-button" class="w-full bg-amber-600 hover:bg-amber-700 text-white font-bold py-3.5 px-4 rounded-xl shadow-md transition active:scale-[0.98]">
            Lanjutkan Pembayaran
        </button>
    </div>

    <!-- Pengecualian script inline: Midtrans Snap.js memerlukan client key dan pemanggilan dari window secara langsung -->
    <script src="{{ config('midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
    <script>
        document.getElementById('pay-button').onclick = function () {
            window.snap.pay('{{ $snapToken }}', {
                onSuccess: function(result) {
                    window.location.href = '{{ route('customer.checkout.finish', ['order_id' => $order->order_number]) }}';
                },
                onPending: function(result) {
                    window.location.href = '{{ route('customer.checkout.finish', ['order_id' => $order->order_number]) }}';
                },
                onError: function(result) {
                    window.location.href = '{{ route('customer.checkout.error', ['order_id' => $order->order_number]) }}';
                },
                onClose: function() {
                    window.location.href = '{{ route('customer.checkout.unfinish', ['order_id' => $order->order_number]) }}';
                }
            });
        };
    </script>
</div>
