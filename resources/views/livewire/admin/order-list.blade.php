<div>
    <!-- Header Section -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Daftar Pesanan</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola dan pantau seluruh pesanan pelanggan.</p>
        </div>
        <button wire:click="refreshData" class="inline-flex items-center justify-center px-4 py-2 bg-white border border-gray-300 rounded-xl font-semibold text-gray-700 text-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition-all min-h-[44px]">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
            Refresh Data
        </button>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 mb-6 flex flex-col md:flex-row gap-4">
        <!-- Status Filter -->
        <div class="w-full md:w-64">
            <select wire:model.live="filterStatus" class="block w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-base focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors min-h-[44px]">
                <option value="all">Semua Status</option>
                <option value="pending">Pending (Menunggu)</option>
                <option value="paid">Dibayar</option>
                <option value="preparing">Disiapkan</option>
                <option value="ready">Siap Diambil/Antar</option>
                <option value="completed">Selesai</option>
                <option value="cancelled">Dibatalkan</option>
            </select>
        </div>
        
        <!-- Search -->
        <div class="flex-1 relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari no. pesanan atau nama pelanggan..." class="block w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-base focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors min-h-[44px]">
        </div>
    </div>

    @php
        $statusColors = [
            'pending' => 'bg-gray-100 text-gray-700',
            'payment_pending' => 'bg-gray-100 text-gray-700',
            'paid' => 'bg-blue-100 text-blue-700',
            'confirmed' => 'bg-blue-100 text-blue-700',
            'preparing' => 'bg-orange-100 text-orange-700',
            'ready' => 'bg-yellow-100 text-yellow-700',
            'completed' => 'bg-green-100 text-green-700',
            'cancelled' => 'bg-red-100 text-red-700',
        ];
        $statusLabels = [
            'pending' => 'Pending',
            'payment_pending' => 'Menunggu Pembayaran',
            'paid' => 'Dibayar',
            'confirmed' => 'Dikonfirmasi',
            'preparing' => 'Disiapkan',
            'ready' => 'Siap Diambil/Antar',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan'
        ];
    @endphp

    <!-- Desktop Table -->
    <div class="hidden lg:block bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Pesanan & Pelanggan</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Tipe & Meja</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Total</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($this->filteredOrders as $order)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-bold text-gray-900">{{ $order['order_number'] }}</div>
                        <div class="text-xs text-gray-500 mt-1">{{ $order['customer_name'] }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-gray-100 text-gray-800 uppercase">
                            {{ $order['type'] }}
                        </span>
                        <div class="text-xs text-gray-500 mt-1">
                            {{ $order['table_number'] ?? $order['delivery_address'] ?? 'Takeaway' }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                        Rp {{ number_format($order['total'], 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <!-- Dropdown Status (Alpine) -->
                        <div x-data="{ open: false }" class="relative inline-block text-left">
                            <button @click="open = !open" type="button" class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium border cursor-pointer hover:opacity-80 transition-opacity min-h-[32px] {{ $statusColors[$order['status']] }}">
                                {{ $statusLabels[$order['status']] }}
                                <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            
                            <!-- Dropdown menu -->
                            <div x-show="open" @click.away="open = false" style="display: none;" class="origin-top-right absolute left-0 mt-2 w-40 rounded-xl shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-10"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95">
                                <div class="py-1" role="menu" aria-orientation="vertical">
                                    @foreach($statusLabels as $key => $label)
                                        <button wire:click="updateStatus({{ $order['id'] }}, '{{ $key }}')" @click="open = false" class="text-gray-700 block w-full text-left px-4 py-2 text-sm hover:bg-gray-50 {{ $order['status'] === $key ? 'font-bold bg-orange-50 text-orange-600' : '' }}" role="menuitem">
                                            {{ $label }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ \Carbon\Carbon::parse($order['created_at'])->format('d M Y, H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex items-center justify-end space-x-2">
                            <button wire:click="viewOrder({{ $order['id'] }})" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors min-h-[44px] min-w-[44px] flex items-center justify-center" title="Lihat Detail">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </button>
                            <button wire:click="deleteOrder({{ $order['id'] }})" wire:confirm="Yakin ingin menghapus pesanan ini?" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors min-h-[44px] min-w-[44px] flex items-center justify-center" title="Hapus">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">Tidak ada pesanan ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile Card Stack -->
    <div class="lg:hidden space-y-4">
        @forelse($this->filteredOrders as $order)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4">
            <div class="flex justify-between items-start mb-3">
                <div>
                    <h3 class="text-sm font-bold text-gray-900">{{ $order['order_number'] }}</h3>
                    <p class="text-xs text-gray-500 mt-1">{{ $order['customer_name'] }}</p>
                </div>
                
                <!-- Dropdown Status (Mobile) -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" type="button" class="inline-flex items-center px-2 py-1 rounded text-[10px] font-medium border min-h-[32px] {{ $statusColors[$order['status']] }}">
                        {{ $statusLabels[$order['status']] }}
                        <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    
                    <div x-show="open" @click.away="open = false" style="display: none;" class="origin-top-right absolute right-0 mt-1 w-40 rounded-xl shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-10" x-transition>
                        <div class="py-1">
                            @foreach($statusLabels as $key => $label)
                                <button wire:click="updateStatus({{ $order['id'] }}, '{{ $key }}')" @click="open = false" class="text-gray-700 block w-full text-left px-4 py-2 text-sm {{ $order['status'] === $key ? 'font-bold bg-orange-50 text-orange-600' : '' }}">
                                    {{ $label }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-2 text-xs mb-3">
                <div class="bg-gray-50 rounded-lg p-2">
                    <span class="text-gray-400 block text-[10px] uppercase">Tipe & Meja</span>
                    <span class="font-semibold text-gray-700 mt-0.5 block capitalize">{{ $order['type'] }} - {{ $order['table_number'] ?? 'N/A' }}</span>
                </div>
                <div class="bg-gray-50 rounded-lg p-2">
                    <span class="text-gray-400 block text-[10px] uppercase">Waktu</span>
                    <span class="font-semibold text-gray-700 mt-0.5 block">{{ \Carbon\Carbon::parse($order['created_at'])->format('d M H:i') }}</span>
                </div>
            </div>

            <div class="flex justify-between items-center border-t border-gray-100 pt-3">
                <span class="text-sm font-extrabold text-gray-900">Rp {{ number_format($order['total'], 0, ',', '.') }}</span>
                <div class="flex gap-2">
                    <button wire:click="viewOrder({{ $order['id'] }})" class="p-2 text-blue-600 bg-blue-50 rounded-lg min-h-[40px] min-w-[40px] flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    </button>
                    <button wire:click="deleteOrder({{ $order['id'] }})" wire:confirm="Yakin ingin menghapus pesanan ini?" class="p-2 text-red-600 bg-red-50 rounded-lg min-h-[40px] min-w-[40px] flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8 text-center text-gray-500">
            Tidak ada pesanan ditemukan.
        </div>
        @endforelse
    </div>

    <!-- Modal Detail Pesanan -->
    <div x-data="{ show: @entangle('showDetail') }" 
         x-show="show" 
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;"
         x-transition.opacity>
         
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 transition-opacity bg-gray-900/50 backdrop-blur-sm" @click="show = false"></div>

            <!-- Modal panel -->
            <div class="relative inline-block w-full max-w-2xl p-6 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl z-10"
                 x-show="show"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                
                @if($selectedOrder)
                <div class="flex justify-between items-start mb-5">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Detail Pesanan</h3>
                        <p class="text-sm text-gray-500 font-mono mt-1">{{ $selectedOrder['order_number'] }}</p>
                    </div>
                    <button @click="show = false" type="button" class="text-gray-400 hover:text-gray-500 hover:bg-gray-100 p-2 rounded-xl transition-colors min-h-[44px] min-w-[44px] flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Info Pelanggan -->
                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Informasi Pelanggan</h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Nama</span>
                                <span class="font-medium text-gray-900">{{ $selectedOrder['customer_name'] }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Telepon</span>
                                <span class="font-medium text-gray-900">{{ $selectedOrder['customer_phone'] }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Tipe Pesanan</span>
                                <span class="font-medium text-gray-900 capitalize">{{ $selectedOrder['type'] }}</span>
                            </div>
                            @if($selectedOrder['type'] === 'dine-in')
                            <div class="flex justify-between">
                                <span class="text-gray-500">Meja</span>
                                <span class="font-medium text-gray-900">{{ $selectedOrder['table_number'] }}</span>
                            </div>
                            @elseif($selectedOrder['type'] === 'delivery')
                            <div class="flex flex-col mt-2 pt-2 border-t border-gray-200">
                                <span class="text-gray-500 text-xs mb-1">Alamat Pengiriman:</span>
                                <span class="font-medium text-gray-900 text-sm">{{ $selectedOrder['delivery_address'] }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Info Pembayaran -->
                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Informasi Pembayaran</h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Tanggal</span>
                                <span class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($selectedOrder['created_at'])->format('d M Y, H:i') }}</span>
                            </div>
                            <div class="flex justify-between items-center mt-2 pt-2 border-t border-gray-200">
                                <span class="text-gray-500">Status</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $statusColors[$selectedOrder['status']] }}">
                                    {{ $statusLabels[$selectedOrder['status']] }}
                                </span>
                            </div>
                            @if($selectedOrder['notes'])
                            <div class="flex flex-col mt-2 pt-2 border-t border-gray-200">
                                <span class="text-gray-500 text-xs mb-1">Catatan:</span>
                                <span class="font-medium text-orange-600 italic text-sm">"{{ $selectedOrder['notes'] }}"</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Item List -->
                <h4 class="text-sm font-bold text-gray-900 mb-3 border-b border-gray-200 pb-2">Daftar Item</h4>
                <div class="space-y-3 mb-6 max-h-60 overflow-y-auto pr-2 scrollbar-thin scrollbar-thumb-gray-200">
                    @foreach($selectedOrder['items'] as $item)
                        <div class="flex justify-between py-2 border-b border-gray-100 last:border-0">
                            <div>
                                <h5 class="text-sm font-bold text-gray-900">{{ $item['name'] }} <span class="text-gray-500 font-normal">x{{ $item['quantity'] }}</span></h5>
                                <div class="text-xs text-gray-500 mt-1 space-y-0.5">
                                    @if(!empty($item['spiciness']))
                                        <p>Level: <span class="text-red-500 font-medium">{{ $item['spiciness'] }}</span></p>
                                    @endif
                                    @if(!empty($item['toppings']))
                                        <p>Topping: {{ implode(', ', $item['toppings']) }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="text-sm font-medium text-gray-900 text-right">
                                Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Totals -->
                <div class="border-t border-gray-200 pt-4 space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Subtotal</span>
                        <span class="font-medium text-gray-900">Rp {{ number_format($selectedOrder['subtotal'], 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Pajak (11%)</span>
                        <span class="font-medium text-gray-900">Rp {{ number_format($selectedOrder['tax'], 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center pt-2 mt-2 border-t border-gray-200">
                        <span class="text-base font-bold text-gray-900">Total</span>
                        <span class="text-lg font-extrabold text-orange-600">Rp {{ number_format($selectedOrder['total'], 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-3">
                    <button type="button" @click="show = false" class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-xl text-sm font-semibold hover:bg-gray-200 transition-colors min-h-[44px]">
                        Tutup
                    </button>
                    <!-- Tambahan aksi lain bisa ditaruh di sini -->
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
