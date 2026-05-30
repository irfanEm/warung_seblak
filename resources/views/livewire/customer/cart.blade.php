<div x-data="{ showCart: false }" 
     @toggle-cart.window="showCart = !showCart"
     @open-cart.window="showCart = true"
     @click.away="showCart = false"
     class="fixed bottom-[65px] left-1/2 -translate-x-1/2 w-full max-w-md z-20"
     style="display: none;"
     x-show="showCart"
     x-transition:enter="transition-all ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-y-12"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-transition:leave="transition-all ease-in duration-200"
     x-transition:leave-start="opacity-100 translate-y-0"
     x-transition:leave-end="opacity-0 translate-y-12">

    <!-- Bottom Sheet Wrapper (Padded above mobile bottom navigation) -->
    <div class="mx-3 mb-2 bg-white/95 backdrop-blur-md rounded-3xl border border-gray-200/80 shadow-[0_-8px_30px_rgb(0,0,0,0.12)] overflow-hidden flex flex-col max-h-[70vh]">
        
        <!-- Grab Bar / Header -->
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between shrink-0 bg-white/80">
            <div class="flex items-center space-x-2">
                <span class="text-xl">🛒</span>
                <h3 class="font-extrabold text-sm text-gray-800 tracking-tight">Keranjang Belanja</h3>
                <span class="px-2 py-0.5 rounded-full text-[9px] font-black bg-orange-100 text-orange-600">
                    {{ collect($items)->sum('quantity') }} Item
                </span>
            </div>
            
            <button @click="showCart = false" class="text-gray-400 hover:text-gray-600 transition-colors p-1 rounded-lg hover:bg-gray-50">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                    <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                </svg>
            </button>
        </div>

        <!-- Scrollable Items List -->
        <div class="flex-grow overflow-y-auto px-5 py-4 space-y-4 scrollbar-none">
            @forelse($items as $index => $item)
                <div wire:key="cart-item-{{ $index }}" 
                     class="flex items-start justify-between p-3.5 bg-gray-50 rounded-2xl border border-gray-100 hover:shadow-xs transition-all duration-200">
                    
                    <div class="flex-grow">
                        <!-- Menu Name & Spiciness -->
                        <div class="flex items-center space-x-2">
                            <h4 class="font-bold text-gray-800 text-xs leading-snug">{{ $item['name'] }}</h4>
                            <span class="px-1.5 py-0.5 rounded-md text-[8px] font-extrabold bg-red-50 text-red-600 border border-red-100/50">
                                🔥 {{ $item['spiciness']['name'] }}
                            </span>
                        </div>

                        <!-- Toppings list summary -->
                        @if(!empty($item['toppings']))
                            <div class="mt-1.5 flex flex-wrap gap-1">
                                @foreach($item['toppings'] as $topping)
                                    <span class="text-[9px] font-semibold text-gray-500 bg-white px-2 py-0.5 rounded-lg border border-gray-200/50 shadow-2xs">
                                        + {{ $topping['name'] }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <p class="text-[9px] text-gray-400 mt-1.5 font-medium">Tanpa topping ekstra</p>
                        @endif

                        <!-- Price calculation -->
                        <div class="mt-3.5 flex items-center justify-between">
                            @php
                                $unitPrice = (float) $item['price'];
                                foreach ($item['toppings'] as $topping) {
                                    $unitPrice += (float) $topping['price'];
                                }
                                $itemSubtotal = $unitPrice * $item['quantity'];
                            @endphp
                            
                            <span class="text-xs font-black text-orange-500 tracking-tight">
                                Rp {{ number_format($itemSubtotal, 0, ',', '.') }}
                            </span>
                            <span class="text-[10px] text-gray-400">
                                (Rp {{ number_format($unitPrice, 0, ',', '.') }}/porsi)
                            </span>
                        </div>
                    </div>

                    <!-- Quantity modifiers & Delete button -->
                    <div class="flex flex-col items-end space-y-2 ml-4 justify-between self-stretch">
                        <!-- Trash Button with 44x44px Touch Target -->
                        <button wire:click="removeItem({{ $index }})" 
                                class="text-gray-400 hover:text-red-500 hover:bg-red-50 min-h-[44px] min-w-[44px] flex items-center justify-center rounded-xl transition-all duration-150 active:scale-95 shrink-0"
                                title="Hapus Item">
                            <x-icons.trash class="w-5 h-5" />
                        </button>

                        <!-- Modifier Buttons with 44x44px Touch Target -->
                        <div class="flex items-center space-x-1 bg-white p-1 rounded-2xl border border-gray-200 shadow-2xs shrink-0">
                            <button wire:click="updateQuantity({{ $index }}, {{ $item['quantity'] - 1 }})" 
                                    class="min-h-[44px] min-w-[44px] flex items-center justify-center bg-gray-50 hover:bg-gray-100 rounded-xl text-sm font-extrabold text-gray-500 transition-all active:scale-90">-</button>
                            <span class="text-xs font-extrabold text-gray-800 w-5 text-center">{{ $item['quantity'] }}</span>
                            <button wire:click="updateQuantity({{ $index }}, {{ $item['quantity'] + 1 }})" 
                                    class="min-h-[44px] min-w-[44px] flex items-center justify-center bg-gray-50 hover:bg-gray-100 rounded-xl text-sm font-extrabold text-gray-500 transition-all active:scale-90">+</button>
                        </div>
                    </div>

                </div>
            @empty
                <div class="py-12 text-center">
                    <span class="text-4xl">🍜</span>
                    <p class="text-xs font-semibold text-gray-400 mt-3">Keranjang belanja Anda masih kosong.</p>
                </div>
            @endforelse
        </div>

        <!-- Footer Ringkasan & Checkout -->
        @if(!empty($items))
            <div class="p-5 border-t border-gray-100 bg-gray-50/80 shrink-0">
                <!-- Ringkasan Harga -->
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Pembayaran</span>
                    <span class="text-base font-black text-orange-500 tracking-tight">
                        Rp {{ number_format($totalPrice, 0, ',', '.') }}
                    </span>
                </div>

                <!-- Tombol Checkout -->
                {{-- TODO: Hubungkan ke halaman checkout sebenarnya --}}
                <a href="{{ Route::has('customer.checkout') ? route('customer.checkout') : '#' }}"
                   class="block w-full py-3.5 px-6 bg-orange-500 hover:bg-orange-600 text-white text-xs font-black uppercase tracking-wider rounded-2xl shadow-lg shadow-orange-500/10 hover:shadow-orange-500/20 text-center active:scale-[0.98] transition-all duration-200">
                    Lanjut ke Pembayaran
                </a>
            </div>
        @endif

    </div>

</div>
