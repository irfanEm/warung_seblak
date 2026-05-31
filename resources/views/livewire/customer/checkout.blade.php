<div class="px-4 py-6 pb-24 space-y-6">
    <!-- Header with Back Button -->
    <div class="flex items-center">
        <a href="{{ Route::has('customer.menu') ? route('customer.menu') : '#' }}" 
           class="mr-3 text-gray-500 hover:text-gray-700 hover:bg-gray-50 p-2 rounded-xl transition-colors min-h-[44px] min-w-[44px] flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <h1 class="text-base font-extrabold text-gray-900 tracking-tight">Selesaikan Pesanan</h1>
    </div>

    <!-- Table Information (QR scan confirm banner) -->
    @if($tableNumber)
        <div class="bg-orange-50 border border-orange-100 rounded-3xl p-4 flex items-center justify-between shadow-[0_2px_4px_rgba(249,115,22,0.03)]">
            <div>
                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-wider">Pemesanan Untuk</p>
                <p class="text-lg font-black text-gray-800 leading-none mt-1">{{ $tableNumber }}</p>
            </div>
            <div class="w-12 h-12 bg-orange-100 rounded-2xl flex items-center justify-center text-orange-600">
                <x-icons.table class="w-6 h-6" />
            </div>
        </div>
    @endif

    <!-- Order Summary Card -->
    <div class="bg-white border border-gray-100 rounded-3xl shadow-[0_2px_12px_rgba(0,0,0,0.02)] overflow-hidden">
        <div class="px-5 py-4 bg-gray-50/50 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-xs font-black text-gray-800 uppercase tracking-wider">Ringkasan Pesanan</h2>
            <span class="text-[10px] text-gray-400 font-bold">{{ count($items) }} Menu</span>
        </div>
        <div class="p-5 space-y-4">
            @foreach($items as $index => $item)
                <div class="flex justify-between items-start text-xs border-b border-gray-50 pb-3 last:border-0 last:pb-0">
                    <div class="flex gap-2.5 text-gray-850">
                        <span class="font-extrabold text-orange-500 bg-orange-50 rounded-lg w-7 h-7 flex items-center justify-center shrink-0">
                            {{ $item['quantity'] }}x
                        </span>
                        <div>
                            <span class="font-bold text-gray-800">{{ $item['name'] }}</span>
                            
                            <!-- Spiciness & Toppings details -->
                            <div class="text-[10px] text-gray-500 mt-1 flex flex-wrap gap-1 items-center">
                                <span class="text-[9px] font-extrabold text-red-600 bg-red-50 px-1.5 py-0.5 rounded-md border border-red-100/50">
                                    🔥 {{ $item['spiciness']['name'] }}
                                </span>
                                @foreach($item['toppings'] as $topping)
                                    <span class="text-[9px] font-semibold text-gray-400 bg-gray-50 px-1.5 py-0.5 rounded-md border border-gray-100">
                                        + {{ $topping['name'] }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    
                    @php
                        $itemUnitPrice = (float) $item['price'];
                        foreach ($item['toppings'] as $topping) {
                            $itemUnitPrice += (float) $topping['price'];
                        }
                        $itemSubtotal = $itemUnitPrice * $item['quantity'];
                    @endphp
                    <span class="font-black text-gray-800 whitespace-nowrap ml-2">
                        Rp {{ number_format($itemSubtotal, 0, ',', '.') }}
                    </span>
                </div>
            @endforeach
            
            <!-- Price Summary -->
            <div class="pt-4 mt-2 border-t border-dashed border-gray-250 space-y-2">
                <div class="flex justify-between text-xs text-gray-400">
                    <span>Subtotal</span>
                    <span class="font-bold text-gray-700">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-xs text-gray-400">
                    <span>Pajak (0%)</span>
                    <span class="font-bold text-gray-700">Rp {{ number_format($tax, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center pt-2 border-t border-gray-50">
                    <span class="text-xs font-black text-gray-850 uppercase tracking-wider">Total Pembayaran</span>
                    <span class="text-base font-black text-orange-500 tracking-tight">
                        Rp {{ number_format($total, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Information Form -->
    <div class="bg-white border border-gray-100 rounded-3xl p-5 shadow-[0_2px_12px_rgba(0,0,0,0.02)] space-y-4">
        <h2 class="text-xs font-black text-gray-800 uppercase tracking-wider border-b border-gray-50 pb-2">Informasi Pemesan</h2>
        
        <div class="space-y-4">
            <!-- Customer Name Input -->
            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Nama Pemesan <span class="text-red-500">*</span></label>
                <input type="text" 
                       wire:model="customerName" 
                       placeholder="Masukkan nama Anda (min. 3 karakter)" 
                       class="w-full text-base py-3 px-4 rounded-2xl border-gray-200/80 shadow-2xs focus:border-orange-500 focus:ring-orange-500/20 transition-all placeholder:text-gray-300" />
                @error('customerName')
                    <span class="text-[10px] text-red-500 font-semibold mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Customer Phone Input -->
            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Nomor Telepon / WhatsApp <span class="text-red-500">*</span></label>
                <input type="tel" 
                       wire:model="customerPhone" 
                       placeholder="Contoh: 08123456789" 
                       class="w-full text-base py-3 px-4 rounded-2xl border-gray-200/80 shadow-2xs focus:border-orange-500 focus:ring-orange-500/20 transition-all placeholder:text-gray-300" />
                @error('customerPhone')
                    <span class="text-[10px] text-red-500 font-semibold mt-1 block">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>

    <!-- Bottom Fixed Pay Button with 44px+ height -->
    <div class="fixed bottom-0 left-1/2 -translate-x-1/2 w-full max-w-md p-4 bg-white/90 backdrop-blur-md border-t border-gray-100 shadow-[0_-8px_20px_rgba(0,0,0,0.03)] z-20 rounded-t-2xl">
        <button wire:click="processPayment" 
                wire:loading.attr="disabled"
                class="w-full min-h-[48px] flex items-center justify-center bg-orange-500 hover:bg-orange-600 disabled:bg-orange-300 text-white text-xs font-black uppercase tracking-wider rounded-2xl shadow-lg shadow-orange-500/10 hover:shadow-orange-500/20 active:scale-[0.98] transition-all duration-200 cursor-pointer">
            <span wire:loading.remove wire:target="processPayment">Konfirmasi & Bayar Sekarang</span>
            <span wire:loading wire:target="processPayment" class="inline-flex items-center justify-center">
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Memproses Pembayaran...
            </span>
        </button>
    </div>
</div>
