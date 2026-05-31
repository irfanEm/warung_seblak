<div class="h-full overflow-y-auto p-4 sm:p-6 lg:p-8" wire:poll.5s>
    
    <x-slot:header_actions>
        <!-- Jam Digital (AlpineJS) -->
        <div x-data="{ time: new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }) }" 
             x-init="setInterval(() => time = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }), 1000)" 
             class="bg-gray-800 px-4 py-1.5 rounded-lg border border-gray-700 shadow-inner mr-4">
            <span x-text="time" class="text-xl font-bold text-orange-400 font-mono tracking-widest"></span>
        </div>
        {{-- TODO: Integrasi Notifikasi Suara (Audio API) saat ada pesanan baru --}}
    </x-slot:header_actions>

    @if(empty($orders))
    <div class="h-full flex flex-col items-center justify-center text-gray-400 space-y-4 opacity-70">
        <div class="w-24 h-24 bg-gray-800 rounded-full flex items-center justify-center mb-2">
            <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        </div>
        <h2 class="text-2xl font-bold text-white tracking-wider">TIDAK ADA PESANAN</h2>
        <p class="text-gray-500">Dapur sedang santai. Tunggu pesanan berikutnya.</p>
    </div>
    @else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-4">
        @foreach($orders as $order)
            @php
                $minutesWaited = \Carbon\Carbon::parse($order['created_at'])->diffInMinutes(\Carbon\Carbon::now());
                $bgClass = 'bg-white';
                $borderClass = 'border-gray-200';
                $textClass = 'text-gray-900';
                
                if ($minutesWaited >= 15) {
                    $bgClass = 'bg-red-50';
                    $borderClass = 'border-red-200';
                } elseif ($minutesWaited >= 5) {
                    $bgClass = 'bg-yellow-50';
                    $borderClass = 'border-yellow-200';
                }
            @endphp
            
            <div class="{{ $bgClass }} border {{ $borderClass }} rounded-2xl shadow-sm overflow-hidden flex flex-col transition-colors">
                <!-- Card Header -->
                <div class="p-4 border-b {{ $borderClass }} bg-black/5">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="text-xl font-black {{ $textClass }} tracking-tight">
                            #{{ substr($order['order_number'], -4) }}
                        </h3>
                        <span class="px-2 py-1 bg-gray-900 text-white text-xs font-bold rounded-lg whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($order['created_at'])->format('H:i') }} 
                            <span class="text-orange-400 ml-1">({{ $minutesWaited }}m)</span>
                        </span>
                    </div>
                    
                    <div class="flex flex-col gap-1">
                        <div class="flex items-center gap-2">
                            @if($order['type'] === 'dine_in')
                                <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded text-xs font-bold uppercase tracking-wider">Dine-in</span>
                                <span class="text-sm font-bold text-gray-700">{{ $order['table_number'] }}</span>
                            @elseif($order['type'] === 'delivery')
                                <span class="bg-purple-100 text-purple-700 px-2 py-0.5 rounded text-xs font-bold uppercase tracking-wider">Delivery</span>
                            @else
                                <span class="bg-orange-100 text-orange-700 px-2 py-0.5 rounded text-xs font-bold uppercase tracking-wider">Takeaway</span>
                            @endif
                        </div>
                        <p class="text-xs font-semibold text-gray-500 uppercase mt-1">{{ $order['customer_name'] }}</p>
                    </div>
                </div>

                <!-- Card Body (Items) -->
                <div class="flex-1 p-4 bg-white/50 overflow-y-auto">
                    <ul class="space-y-3">
                        @foreach($order['items'] as $item)
                        <li class="flex gap-2">
                            <span class="font-bold text-gray-900">{{ $item['quantity'] }}x</span>
                            <div class="flex-1">
                                <span class="font-semibold text-gray-800">{{ $item['name'] }}</span>
                                @if($item['spiciness'] || !empty($item['toppings']))
                                <div class="text-xs text-gray-600 mt-0.5 space-y-0.5">
                                    @if($item['spiciness'])
                                        <p class="text-red-600 font-bold">🌶️ {{ $item['spiciness'] }}</p>
                                    @endif
                                    @if(!empty($item['toppings']))
                                        <p>+ {{ implode(', ', $item['toppings']) }}</p>
                                    @endif
                                </div>
                                @endif
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    
                    @if(!empty($order['notes']))
                    <div class="mt-4 p-2 bg-yellow-100/50 border border-yellow-200 rounded-lg">
                        <p class="text-xs font-bold text-yellow-800 flex items-start gap-1">
                            <span>📝</span>
                            {{ $order['notes'] }}
                        </p>
                    </div>
                    @endif
                </div>

                <!-- Card Footer (Actions) -->
                <div class="p-3 bg-black/5 border-t {{ $borderClass }} shrink-0">
                    @if(in_array($order['status'], ['paid', 'confirmed']))
                        <button wire:click="updateStatus('{{ $order['id'] }}', 'preparing')" 
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-sm transition-colors min-h-[44px] min-w-[44px] flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Mulai Siapkan (Terima)
                        </button>
                    @elseif($order['status'] === 'preparing')
                        <button wire:click="updateStatus('{{ $order['id'] }}', 'ready')" 
                                class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-xl shadow-sm transition-colors min-h-[44px] min-w-[44px] flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                            Pesanan Siap
                        </button>
                    @elseif($order['status'] === 'ready')
                        <button wire:click="updateStatus('{{ $order['id'] }}', 'completed')" 
                                class="w-full bg-green-500 hover:bg-green-600 text-white font-bold rounded-xl shadow-sm transition-colors min-h-[44px] min-w-[44px] flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Selesai & Antar
                        </button>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
    @endif
</div>
