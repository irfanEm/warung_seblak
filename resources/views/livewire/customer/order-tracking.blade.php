<div class="min-h-screen bg-gray-50 py-6 px-4" wire:poll.5s="loadOrder">
    <div class="max-w-lg mx-auto">
        <!-- Header -->
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Lacak Pesanan</h1>
            <p class="text-sm text-gray-500 mt-1">Kode: <span class="font-mono font-semibold text-indigo-600">{{ $trackingCode }}</span></p>
        </div>

        @if ($notFound)
            <!-- Not Found -->
            <div class="bg-white rounded-lg shadow-sm border border-red-200 p-6 text-center">
                <svg class="mx-auto h-12 w-12 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">Pesanan Tidak Ditemukan</h3>
                <p class="mt-2 text-sm text-gray-500">Kode pesanan "{{ $trackingCode }}" tidak ditemukan.</p>
                <a href="{{ route('customer.orders') }}" class="mt-4 inline-block text-sm text-indigo-600 hover:text-indigo-800">Cari pesanan lain</a>
            </div>
        @else
            <!-- Status Timeline -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-4">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Status Pesanan</h2>

                @if ($order->status === 'cancelled')
                    <div class="flex items-center p-4 bg-red-50 rounded-lg">
                        <svg class="h-6 w-6 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        <span class="font-medium text-red-800">Pesanan Dibatalkan</span>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach ($timeline as $item)
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    @if ($currentStep >= $item['step'])
                                        <div class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center">
                                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                    @else
                                        <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center">
                                            <span class="text-xs text-gray-500">{{ $item['step'] }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium {{ $currentStep >= $item['step'] ? 'text-green-700' : 'text-gray-500' }}">
                                        {{ $item['label'] }}
                                    </p>
                                    @if ($order->status === $item['status'])
                                        <p class="text-xs text-indigo-600 font-medium">Saat ini</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Order Details -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-4">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Detail Pesanan</h2>

                <div class="space-y-3">
                    @foreach ($order->orderItems as $item)
                        <div class="flex justify-between items-start py-2 border-b border-gray-100 last:border-0">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $item->item_name_snapshot }}</p>
                                <p class="text-xs text-gray-500">{{ $item->quantity }} x Rp{{ formatRupiah($item->price) }}</p>
                                @if ($item->toppings->isNotEmpty())
                                    <p class="text-xs text-gray-400">
                                        + {{ $item->toppings->pluck('topping_name')->join(', ') }}
                                    </p>
                                @endif
                            </div>
                            <p class="text-sm font-medium text-gray-900">Rp{{ formatRupiah($item->subtotal) }}</p>
                        </div>
                    @endforeach
                </div>

                <!-- Totals -->
                <div class="mt-4 pt-4 border-t border-gray-200 space-y-2">
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Subtotal</span>
                        <span>Rp{{ formatRupiah($order->subtotal) }}</span>
                    </div>
                    @if ($order->tax > 0)
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Pajak</span>
                            <span>Rp{{ formatRupiah($order->tax) }}</span>
                        </div>
                    @endif
                    @if ($order->delivery_fee > 0)
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Ongkir</span>
                            <span>Rp{{ formatRupiah($order->delivery_fee) }}</span>
                        </div>
                    @endif
                    @if ($order->discount > 0)
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Diskon</span>
                            <span>-Rp{{ formatRupiah($order->discount) }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between text-base font-bold text-gray-900 pt-2 border-t">
                        <span>Total</span>
                        <span>Rp{{ formatRupiah($order->total) }}</span>
                    </div>
                </div>
            </div>

            <!-- Info -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="space-y-2 text-sm text-gray-600">
                    <div class="flex justify-between">
                        <span>No. Pesanan</span>
                        <span class="font-medium">{{ $order->order_number }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Tipe</span>
                        <span class="font-medium">{{ $order->type === 'dine_in' ? 'Dine-in' : 'Takeaway' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Waktu Pesan</span>
                        <span class="font-medium">{{ $order->created_at->format('d M Y, H:i') }}</span>
                    </div>
                </div>
            </div>

            <!-- Back Link -->
            <div class="mt-6 text-center">
                <a href="{{ route('customer.menu') }}" class="text-sm text-indigo-600 hover:text-indigo-800">Kembali ke Menu</a>
            </div>
        @endif
    </div>
</div>
