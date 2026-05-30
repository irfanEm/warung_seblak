<div @if($status !== 'ready') wire:poll.10s="updateStatus" @endif class="px-4 py-6 pb-24 space-y-6">
    <!-- Header -->
    <div class="flex items-center">
        <a href="{{ Route::has('customer.menu') ? route('customer.menu') : '#' }}" 
           class="mr-3 text-gray-500 hover:text-gray-700 hover:bg-gray-50 p-2 rounded-xl transition-colors min-h-[44px] min-w-[44px] flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <h1 class="text-base font-extrabold text-gray-900 tracking-tight">Status Pesanan</h1>
    </div>

    <!-- Ticket Summary Card -->
    <div class="bg-white border border-gray-100 rounded-3xl p-5 shadow-[0_2px_12px_rgba(0,0,0,0.02)] space-y-3">
        <div class="flex justify-between items-center text-xs">
            <span class="text-gray-450 font-bold uppercase tracking-wider text-[10px]">Nomor Invoice</span>
            <span class="font-extrabold text-gray-800">{{ $orderNumber }}</span>
        </div>
        
        <div class="flex justify-between items-center text-xs">
            <span class="text-gray-450 font-bold uppercase tracking-wider text-[10px]">Tipe Pesanan</span>
            <span class="font-extrabold text-orange-500">
                @if(session('table_number'))
                    Dine-in ({{ session('table_number') }})
                @else
                    Pemesanan Mandiri
                @endif
            </span>
        </div>
        
        <!-- Live status badge -->
        <div class="pt-3 border-t border-gray-50 flex items-center justify-between">
            <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Status Terkini</span>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-black
                {{ $status === 'ready' ? 'bg-green-50 text-green-600 border border-green-200/50' : 'bg-orange-50 text-orange-600 border border-orange-200/50 animate-pulse' }}">
                <span class="w-1.5 h-1.5 rounded-full mr-2 {{ $status === 'ready' ? 'bg-green-500' : 'bg-orange-500' }}"></span>
                {{ $statusHistory[$status]['label'] }}
            </span>
        </div>

        <!-- Dynamic estimation time with clock icon -->
        <!-- TODO: Integrate with cooking queue metrics later -->
        <div class="pt-3 border-t border-gray-50 flex items-center justify-between">
            <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider flex items-center">
                <svg class="w-3.5 h-3.5 mr-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Estimasi Saji
            </span>
            <span class="text-xs font-extrabold text-orange-500">
                {{ $this->getEstimatedTime() }}
            </span>
        </div>
    </div>

    <!-- Vertical Status Timeline -->
    <div class="bg-white border border-gray-100 rounded-3xl p-6 shadow-[0_2px_12px_rgba(0,0,0,0.02)]">
        <div class="relative space-y-8 pl-8">
            
            <!-- Connector Line Background -->
            <div class="absolute left-[11px] top-2 bottom-2 w-0.5 bg-gray-100 z-0"></div>

            @foreach($statusHistory as $key => $stepInfo)
                @php
                    $isCompleted = $stepInfo['step'] < $currentStep;
                    $isActive = $stepInfo['step'] === $currentStep;
                    $isPending = $stepInfo['step'] > $currentStep;
                @endphp
                
                <!-- Timeline Step -->
                <div class="relative flex items-start z-10 transition-all duration-300">
                    
                    <!-- Circle Milestone Indicator -->
                    <div class="absolute -left-[29px] top-0.5 w-6 h-6 rounded-full flex items-center justify-center border-2 transition-all duration-300
                        {{ $isCompleted ? 'bg-green-500 border-green-500 text-white shadow-sm' : '' }}
                        {{ $isActive ? 'bg-orange-500 border-orange-500 text-white shadow-md shadow-orange-500/20 scale-110' : '' }}
                        {{ $isPending ? 'bg-white border-gray-200 text-gray-400' : '' }}">
                        
                        @if($isCompleted)
                            <!-- Check icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5">
                                <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                            </svg>
                        @elseif($isActive)
                            <!-- Dot or active pulsing icon -->
                            <span class="w-1.5 h-1.5 bg-white rounded-full animate-ping"></span>
                        @else
                            <!-- Pending step number -->
                            <span class="text-[9px] font-black">{{ $stepInfo['step'] }}</span>
                        @endif
                    </div>

                    <!-- Step Text Labels -->
                    <div class="ml-2">
                        <h3 class="text-xs font-black transition-colors duration-300
                            {{ $isCompleted ? 'text-green-600' : '' }}
                            {{ $isActive ? 'text-orange-500 font-extrabold text-sm' : '' }}
                            {{ $isPending ? 'text-gray-400' : 'text-gray-800' }}">
                            {{ $stepInfo['label'] }}
                        </h3>
                        <p class="text-[10px] leading-relaxed mt-1 transition-colors duration-300
                            {{ $isPending ? 'text-gray-300' : 'text-gray-400' }}">
                            {{ $stepInfo['desc'] }}
                        </p>
                    </div>

                </div>
            @endforeach

        </div>
    </div>

    <!-- Bottom Sticky Nav Buttons (Double action side-by-side) -->
    <div class="fixed bottom-0 left-1/2 -translate-x-1/2 w-full max-w-md p-4 bg-white/90 backdrop-blur-md border-t border-gray-100 shadow-[0_-8px_20px_rgba(0,0,0,0.03)] z-20 rounded-t-2xl flex gap-3">
        <!-- Tombol Pesan Lagi -->
        <a href="{{ Route::has('customer.menu') ? route('customer.menu') : '#' }}" 
           class="flex-1 min-h-[48px] flex items-center justify-center bg-orange-50 hover:bg-orange-100 text-orange-600 text-xs font-black uppercase tracking-wider rounded-2xl active:scale-[0.98] transition-all duration-200 border border-orange-100/50">
            Pesan Lagi
        </a>
        
        <!-- Tombol Kembali ke Beranda -->
        <a href="{{ Route::has('customer.menu') ? route('customer.menu') : '#' }}" 
           class="flex-1 min-h-[48px] flex items-center justify-center bg-gray-150 hover:bg-gray-200 text-gray-700 text-xs font-black uppercase tracking-wider rounded-2xl active:scale-[0.98] transition-all duration-200">
            Kembali ke Beranda
        </a>
    </div>
</div>
