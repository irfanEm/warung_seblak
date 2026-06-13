<div class="px-4 py-6">
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Keranjang Saya</h1>
    </div>

    @if(empty($cart))
        <div class="flex flex-col items-center justify-center py-16 text-center">
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4 text-gray-300">
                <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <h2 class="text-lg font-bold text-gray-900">Keranjang Kosong</h2>
            <p class="text-gray-500 text-sm mt-1 mb-6">Belum ada seblak yang dipilih nih!</p>
            <a href="{{ route('customer.menu') }}" class="bg-amber-600 text-white font-bold px-6 py-2.5 rounded-full shadow-sm hover:bg-amber-700 active:scale-95 transition">
                Lihat Menu
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($cart as $key => $item)
                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex gap-4">
                    <div class="flex-1 flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-start">
                                <h3 class="font-bold text-gray-900 text-sm leading-tight">{{ $item['name'] }}</h3>
                                <button wire:click="removeItem('{{ $key }}')" class="text-gray-400 hover:text-red-500 p-1 -mt-1 -mr-1 transition" title="Hapus Item">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                </button>
                            </div>
                            
                            <!-- Variants Details -->
                            <div class="mt-1">
                                @if(!empty($item['spiciness_level']))
                                    <span class="inline-block bg-red-100 text-red-800 text-[10px] px-2 py-0.5 rounded-full font-medium mb-1">
                                        {{ $item['spiciness_level']['name'] }}
                                    </span>
                                @endif
                                @if(!empty($item['toppings']))
                                    <p class="text-[11px] text-gray-500 leading-tight">
                                        + {{ collect($item['toppings'])->pluck('name')->join(', ') }}
                                    </p>
                                @endif
                            </div>
                        </div>
                        
                        <div class="mt-4 flex items-center justify-between">
                            <span class="font-bold text-amber-600 text-sm">{{ formatRupiah($item['subtotal']) }}</span>
                            
                            <div class="flex items-center space-x-2 bg-gray-50 border border-gray-200 rounded-lg p-0.5">
                                <button wire:click="updateQuantity('{{ $key }}', {{ $item['quantity'] - 1 }})" class="w-6 h-6 flex items-center justify-center bg-white rounded shadow-sm text-gray-600 hover:text-amber-600 active:bg-gray-100 focus:outline-none transition">
                                    <span class="text-xs font-bold">-</span>
                                </button>
                                <span class="text-xs font-bold w-4 text-center">{{ $item['quantity'] }}</span>
                                <button wire:click="updateQuantity('{{ $key }}', {{ $item['quantity'] + 1 }})" class="w-6 h-6 flex items-center justify-center bg-white rounded shadow-sm text-gray-600 hover:text-amber-600 active:bg-gray-100 focus:outline-none transition">
                                    <span class="text-xs font-bold">+</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Sticky Checkout Panel (di atas bottom nav) -->
        <div class="fixed bottom-16 left-0 right-0 p-4 bg-white border-t border-gray-100 shadow-[0_-4px_10px_-1px_rgba(0,0,0,0.05)] z-20">
            <div class="flex justify-between items-center mb-3">
                <span class="text-sm font-medium text-gray-500">Total Tagihan</span>
                <span class="text-lg font-bold text-gray-900">{{ formatRupiah($total) }}</span>
            </div>
            <a href="{{ route('customer.checkout') }}" class="w-full flex items-center justify-center bg-amber-600 hover:bg-amber-700 text-white font-bold py-3.5 px-4 rounded-xl shadow-md transition active:scale-[0.98]">
                Lanjut ke Pembayaran
            </a>
        </div>
        <!-- Tambahan padding bawah ekstra untuk mengimbangi sticky panel -->
        <div class="h-24"></div>
    @endif
</div>
