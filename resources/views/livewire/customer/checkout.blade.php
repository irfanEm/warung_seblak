<div class="px-4 py-6">
    <!-- Header -->
    <div class="flex items-center mb-6">
        <a href="{{ route('customer.cart') }}" class="mr-3 text-gray-500 hover:text-gray-700 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </a>
        <h1 class="text-xl font-bold text-gray-900">Selesaikan Pesanan</h1>
    </div>

    <!-- Informasi Meja -->
    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6 flex items-center justify-between">
        <div>
            <p class="text-xs text-amber-800 font-medium">Nomor Meja Anda</p>
            <p class="text-2xl font-black text-amber-900 leading-none mt-1">Meja {{ $table->table_number ?? '?' }}</p>
        </div>
        <div class="w-12 h-12 bg-amber-200 rounded-full flex items-center justify-center text-amber-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
        </div>
    </div>

    <!-- Ringkasan Pesanan -->
    <div class="bg-white border border-gray-100 rounded-xl shadow-sm mb-6 overflow-hidden">
        <div class="px-4 py-3 bg-gray-50 border-b border-gray-100">
            <h2 class="text-sm font-bold text-gray-700">Ringkasan Pesanan</h2>
        </div>
        <div class="p-4 space-y-3">
            @foreach($cart as $item)
                <div class="flex justify-between text-sm">
                    <div class="flex gap-2 text-gray-800">
                        <span class="font-medium text-gray-500">{{ $item['quantity'] }}x</span>
                        <div>
                            <span class="font-medium">{{ $item['name'] }}</span>
                            @if(!empty($item['toppings']) || !empty($item['spiciness_level']))
                                <div class="text-[10px] text-gray-500 mt-0.5">
                                    {{ !empty($item['spiciness_level']) ? '[' . $item['spiciness_level']['name'] . ']' : '' }}
                                    {{ !empty($item['toppings']) ? '+ ' . collect($item['toppings'])->pluck('name')->join(', ') : '' }}
                                </div>
                            @endif
                        </div>
                    </div>
                    <span class="font-medium text-gray-900 whitespace-nowrap ml-2">{{ formatRupiah($item['subtotal']) }}</span>
                </div>
            @endforeach
            
            <div class="pt-3 mt-3 border-t border-dashed border-gray-200 flex justify-between items-center">
                <span class="font-bold text-gray-900">Total Pembayaran</span>
                <span class="text-lg font-black text-amber-600">{{ formatRupiah($total) }}</span>
            </div>
        </div>
    </div>

    <!-- Form Data Pemesan -->
    <div class="bg-white border border-gray-100 rounded-xl shadow-sm p-4 mb-24">
        <h2 class="text-sm font-bold text-gray-700 mb-4">Informasi Tambahan</h2>
        
        <div class="space-y-4">
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Nama Pemesan (Opsional)</label>
                <input type="text" wire:model="customerName" placeholder="Contoh: Ibun" class="w-full text-sm rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Catatan Tambahan (Opsional)</label>
                <textarea wire:model="notes" rows="2" placeholder="Contoh: Jangan pakai bawang goreng..." class="w-full text-sm rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500"></textarea>
            </div>
        </div>
    </div>

    <!-- Bottom Fixed Button -->
    <div class="fixed bottom-16 left-0 right-0 p-4 bg-white border-t border-gray-100 shadow-[0_-4px_10px_-1px_rgba(0,0,0,0.05)] z-20">
        <button wire:click="placeOrder" class="w-full flex items-center justify-center bg-amber-600 hover:bg-amber-700 text-white font-bold py-3.5 px-4 rounded-xl shadow-md transition active:scale-[0.98]">
            <span wire:loading.remove wire:target="placeOrder">Pesan Sekarang</span>
            <span wire:loading wire:target="placeOrder">Memproses...</span>
        </button>
    </div>

</div>
