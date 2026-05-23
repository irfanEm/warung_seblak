<div class="h-full flex flex-col" wire:poll.5s>
    <!-- Slot layout for the header actions (Sound Toggle) -->
    @slot('header_actions')
        <button wire:click="toggleSound" class="flex items-center space-x-2 bg-gray-800 hover:bg-gray-700 text-gray-300 hover:text-white px-4 py-2 rounded-lg transition duration-200 text-sm font-medium border border-gray-700/50">
            @if($soundEnabled)
                <!-- Speaker Active Icon -->
                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072M18.364 5.636a9 9 0 010 12.728M12 18.75V5.25L7.75 9.5H4.5v5h3.25L12 18.75z"></path>
                </svg>
                <span class="hidden sm:inline">Suara: On</span>
            @else
                <!-- Speaker Muted Icon -->
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2"></path>
                </svg>
                <span class="hidden sm:inline">Suara: Off</span>
            @endif
        </button>
    @endslot

    <!-- Top Summary Metrics Bar -->
    <div class="bg-gray-900 border-b border-gray-800 px-6 py-4 flex flex-wrap gap-4 items-center justify-between shrink-0 shadow-sm">
        <div class="flex items-center space-x-2 text-sm text-gray-400">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
            <span>Real-time (Auto-refresh 5s)</span>
        </div>

        <!-- Badges for order counts per status -->
        <div class="flex items-center space-x-3 text-xs sm:text-sm font-semibold">
            <div class="flex items-center space-x-1.5 bg-yellow-500/10 text-yellow-400 border border-yellow-500/20 px-3 py-1.5 rounded-lg">
                <span class="w-2.5 h-2.5 rounded-full bg-yellow-500"></span>
                <span>Baru: {{ $metrics['pending'] }}</span>
            </div>
            <div class="flex items-center space-x-1.5 bg-blue-500/10 text-blue-400 border border-blue-500/20 px-3 py-1.5 rounded-lg">
                <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                <span>Diterima: {{ $metrics['confirmed'] }}</span>
            </div>
            <div class="flex items-center space-x-1.5 bg-orange-500/10 text-orange-400 border border-orange-500/20 px-3 py-1.5 rounded-lg">
                <span class="w-2.5 h-2.5 rounded-full bg-orange-500"></span>
                <span>Disiapkan: {{ $metrics['preparing'] }}</span>
            </div>
            <div class="flex items-center space-x-1.5 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 px-3 py-1.5 rounded-lg">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                <span>Selesai: {{ $metrics['ready'] }}</span>
            </div>
        </div>
    </div>

    <!-- Scrollable Main Content Container -->
    <div class="flex-1 overflow-y-auto bg-gray-950 p-6">
        
        <!-- Flash Messages -->
        @if (session()->has('success'))
            <div class="mb-6 bg-emerald-950/40 border border-emerald-500/30 text-emerald-400 px-4 py-3 rounded-lg text-sm flex items-center space-x-2" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-6 bg-rose-950/40 border border-rose-500/30 text-rose-400 px-4 py-3 rounded-lg text-sm flex items-center space-x-2" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if($orders->isEmpty())
            <!-- Empty State View -->
            <div class="h-full min-h-[400px] flex flex-col items-center justify-center text-center p-8 bg-gray-900/30 border border-gray-800/50 rounded-2xl">
                <div class="w-24 h-24 rounded-full bg-emerald-500/10 border border-emerald-500/25 flex items-center justify-center mb-6 animate-pulse">
                    <svg class="w-12 h-12 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">Tidak Ada Pesanan Aktif</h3>
                <p class="text-gray-400 max-w-sm">Dapur sedang santai. Semua pesanan pelanggan sudah diselesaikan!</p>
            </div>
        @else
            <!-- Grid of active orders -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($orders as $order)
                    @php
                        $style = $this->getStatusColor($order->status);
                        // Determine next status and target transition action
                        $nextStatus = match($order->status) {
                            'pending' => 'confirmed',
                            'confirmed' => 'preparing',
                            'preparing' => 'ready',
                            'ready' => 'completed',
                            default => null,
                        };
                    @endphp

                    <!-- Order Card -->
                    <div class="flex flex-col bg-gray-900 border border-gray-800 rounded-xl overflow-hidden shadow-lg transition duration-200 {{ $style['border'] }} {{ $style['bg'] }}">
                        
                        <!-- Card Header -->
                        <div class="p-4 border-b border-gray-800/80 bg-gray-900/50 flex flex-col gap-2 shrink-0">
                            <div class="flex items-center justify-between">
                                <span class="font-extrabold text-sm tracking-wide text-gray-300">
                                    {{ $order->order_number }}
                                </span>
                                <!-- Status Badge -->
                                <span class="text-[10px] uppercase font-bold tracking-wider px-2 py-0.5 rounded-full {{ $style['badge'] }}">
                                    {{ $order->status }}
                                </span>
                            </div>
                            
                            <!-- Delivery / Table Type Info -->
                            <div class="flex items-center justify-between text-xs text-gray-400">
                                <div class="flex items-center space-x-1.5">
                                    @if($order->type === 'dine_in')
                                        <span class="bg-indigo-500/20 text-indigo-300 px-2 py-0.5 rounded text-[10px] font-bold uppercase border border-indigo-500/25">
                                            Dine In
                                        </span>
                                        @if($order->table)
                                            <span class="font-bold text-white text-xs">Meja #{{ $order->table->table_number }}</span>
                                        @endif
                                    @else
                                        <span class="bg-amber-500/20 text-amber-300 px-2 py-0.5 rounded text-[10px] font-bold uppercase border border-amber-500/25">
                                            Delivery
                                        </span>
                                    @endif
                                </div>
                                <span class="text-[11px] font-light">{{ $order->created_at->diffForHumans() }}</span>
                            </div>

                            @if($order->customer_name)
                                <div class="text-xs text-gray-300 font-semibold mt-1">
                                    👤 Pelanggan: <span class="text-white">{{ $order->customer_name }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Card Body (Items List) -->
                        <div class="flex-1 p-4 overflow-y-auto space-y-4 max-h-[350px]">
                            @foreach($order->orderItems as $item)
                                <div class="border-b border-gray-800/40 last:border-b-0 pb-3 last:pb-0">
                                    <div class="flex items-start justify-between">
                                        <div class="text-sm font-semibold text-white">
                                            <span class="text-orange-500 font-bold mr-1">{{ $item->quantity }}x</span>
                                            {{ $item->item_name_snapshot }}
                                        </div>
                                    </div>

                                    <!-- Spiciness Level Detail -->
                                    @if($item->spicinessLevel)
                                        <div class="text-xs text-rose-400 mt-1 flex items-center space-x-1 font-medium">
                                            <span>🌶️</span>
                                            <span>Level Pedas: {{ $item->spicinessLevel->name }}</span>
                                        </div>
                                    @endif

                                    <!-- Toppings Detail -->
                                    @if($item->toppings->isNotEmpty())
                                        <div class="mt-1 text-xs text-gray-400 pl-4 space-y-0.5">
                                            <span class="font-semibold text-gray-500 text-[10px] uppercase">Toppings:</span>
                                            @foreach($item->toppings as $topping)
                                                <div class="flex items-center justify-between text-[11px]">
                                                    <span>+ {{ $topping->topping_name }}</span>
                                                    <span class="text-gray-500">Rp{{ number_format($topping->price, 0, ',', '.') }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                    <!-- Item-specific Notes -->
                                    @if($item->notes)
                                        <div class="mt-1 text-xs bg-gray-950/40 text-amber-300/90 italic px-2 py-1 rounded border border-amber-500/10">
                                            📝 Catatan: {{ $item->notes }}
                                        </div>
                                    @endif
                                </div>
                            @endforeach

                            <!-- Overall Order Notes -->
                            @if($order->notes)
                                <div class="mt-4 pt-3 border-t border-gray-800/60">
                                    <span class="text-[10px] text-gray-500 uppercase font-bold block mb-1">Catatan Pesanan Utama:</span>
                                    <div class="text-xs bg-orange-950/20 border border-orange-500/15 text-orange-300 px-3 py-2 rounded-lg italic">
                                        {{ $order->notes }}
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Card Action Button Footer -->
                        @if($nextStatus)
                            <div class="p-4 border-t border-gray-800/80 bg-gray-900/30 shrink-0">
                                <button 
                                    wire:click="changeStatus({{ $order->id }}, '{{ $nextStatus }}')" 
                                    class="w-full py-2.5 px-4 rounded-lg font-bold text-sm tracking-wide shadow-md transition duration-200 {{ $style['btn'] }}"
                                >
                                    {{ $style['btn_text'] }}
                                </button>
                            </div>
                        @endif

                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- HTML5 Audio Notification Element -->
    <!-- Place a clean sound file path; user can update public/sounds/notification.mp3 as desired -->
    <audio id="orderSound" src="{{ asset('sounds/notification.mp3') }}" preload="auto"></audio>

    <!-- Alpine.js Event Listener to play the audio when 'orderAdded' event is dispatched -->
    <div x-data @order-added.window="if ($wire.soundEnabled) { 
        let audio = document.getElementById('orderSound');
        if (audio) {
            audio.currentTime = 0;
            audio.play().catch(err => console.log('Audio playback blocked or failed:', err));
        }
    }"></div>
</div>
