<div class="px-4 py-12 flex flex-col items-center justify-center text-center space-y-8">
    <!-- Success Animated Checkmark Icon -->
    <div class="relative flex items-center justify-center">
        <div class="w-20 h-20 bg-green-50 rounded-full flex items-center justify-center text-green-500 border border-green-100 shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-10 h-10 animate-bounce">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
            </svg>
        </div>
        <!-- Pulse effect waves -->
        <span class="absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-10 animate-ping"></span>
    </div>

    <!-- Success Header Texts -->
    <div class="space-y-2">
        <h1 class="text-lg font-black text-gray-800 tracking-tight">Pemesanan Sukses!</h1>
        <p class="text-xs text-gray-400 max-w-xs leading-relaxed">
            Hore! Pesanan Anda telah diterima sistem dan siap diproses oleh koki dapur kami.
        </p>
    </div>

    <!-- Order Summary Ticket -->
    <div class="w-full bg-gray-50 border border-gray-150 rounded-3xl p-5 space-y-4 shadow-[0_4px_12px_rgba(0,0,0,0.01)] text-left">
        <div class="flex justify-between border-b border-gray-100 pb-3">
            <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Nomor Pesanan</span>
            <span class="text-xs font-black text-gray-800">{{ $orderNumber }}</span>
        </div>
        
        <div class="flex justify-between border-b border-gray-100 pb-3">
            <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Status Pembayaran</span>
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-black bg-green-100 text-green-600 border border-green-200/50">
                Lunas (Paid)
            </span>
        </div>

        <div class="flex justify-between">
            <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Estimasi Saji</span>
            <span class="text-xs font-extrabold text-orange-500">{{ $estimatedTime }}</span>
        </div>
    </div>

    <!-- Action Buttons with 44px+ touch heights -->
    <div class="w-full space-y-3 pt-6 shrink-0">
        <!-- Lacak Pesanan Button -->
        <a href="{{ Route::has('customer.order.tracking') ? route('customer.order.tracking', ['order_number' => $orderNumber]) : '#' }}" 
           class="block w-full py-3.5 px-6 bg-orange-500 hover:bg-orange-600 text-white text-xs font-black uppercase tracking-wider rounded-2xl shadow-lg shadow-orange-500/10 hover:shadow-orange-500/20 text-center active:scale-[0.98] transition-all duration-200 min-h-[44px] flex items-center justify-center">
            Lacak Status Pesanan
        </a>
        
        <!-- Kembali ke Menu Button -->
        <a href="{{ Route::has('customer.menu') ? route('customer.menu') : '#' }}" 
           class="block w-full py-3.5 px-6 bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs font-black uppercase tracking-wider rounded-2xl text-center active:scale-[0.98] transition-all duration-200 min-h-[44px] flex items-center justify-center">
            Pesan Menu Lainnya
        </a>
    </div>
</div>
