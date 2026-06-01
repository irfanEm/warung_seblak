<div class="max-w-md mx-auto px-4 py-8">
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="p-6 bg-gradient-to-r from-orange-500 to-amber-500">
            <h2 class="text-2xl font-bold text-white text-center">Lacak Pesanan</h2>
            <p class="text-amber-100 text-center text-sm mt-1">Masukkan nomor pesanan Anda untuk melihat status</p>
        </div>
        
        <div class="p-6">
            <form wire:submit.prevent="track" class="space-y-4">
                <div>
                    <label for="orderNumber" class="block text-sm font-medium text-gray-700 mb-1">Nomor Pesanan</label>
                    <input type="text" id="orderNumber" wire:model="orderNumber" 
                           class="w-full rounded-xl border-gray-300 focus:border-amber-500 focus:ring focus:ring-amber-200 transition-colors bg-gray-50 px-4 py-3 text-lg font-mono placeholder-gray-400"
                           placeholder="Contoh: ORD-123456" required>
                    @error('orderNumber') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                
                <button type="submit" 
                        class="w-full bg-amber-600 hover:bg-amber-700 text-white font-bold py-3 px-4 rounded-xl shadow-md shadow-amber-500/30 transition duration-200 flex justify-center items-center">
                    <span>Lacak Pesanan</span>
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</div>
