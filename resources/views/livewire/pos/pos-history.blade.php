<div class="h-full flex flex-col bg-gray-50 overflow-y-auto">
    
    <!-- Page Header & Metrics Section -->
    <div class="bg-white border-b border-gray-200 px-6 py-5 shrink-0 shadow-xs z-10">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-xl font-black text-gray-800 flex items-center space-x-2">
                    <span>📋</span>
                    <span>Riwayat Transaksi POS</span>
                </h1>
                <p class="text-xs text-gray-500 mt-1">Pantau dan kelola semua pesanan kasir yang diselesaikan hari ini atau tanggal pilihan Anda.</p>
            </div>
            
            <!-- Date Filter Selector -->
            <div class="flex items-center space-x-2.5 shrink-0">
                <span class="text-xs font-bold text-gray-500">Pilih Tanggal:</span>
                <input 
                    type="date" 
                    wire:model.live="filterDate" 
                    class="bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 text-xs font-bold text-gray-700 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 shadow-xs cursor-pointer"
                >
            </div>
        </div>

        <!-- Shift KPI Summary Tiles -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-6">
            <!-- Metric 1: Total Revenue -->
            <div class="bg-gradient-to-br from-emerald-50 to-emerald-100/50 border border-emerald-200 rounded-2xl p-4.5 flex items-center space-x-4 shadow-xs">
                <div class="h-10 w-10 bg-emerald-500 text-white rounded-xl flex items-center justify-center text-lg font-bold shadow-md shadow-emerald-500/10">
                    💰
                </div>
                <div>
                    <span class="block text-[10px] uppercase font-black tracking-wider text-emerald-600/80">Pendapatan Shift</span>
                    <span class="block text-lg font-black text-emerald-950 mt-0.5">
                        {{ formatRupiah($totalRevenue) }}
                    </span>
                </div>
            </div>

            <!-- Metric 2: Orders Count -->
            <div class="bg-gradient-to-br from-amber-50 to-amber-100/50 border border-amber-200 rounded-2xl p-4.5 flex items-center space-x-4 shadow-xs">
                <div class="h-10 w-10 bg-amber-500 text-white rounded-xl flex items-center justify-center text-lg font-bold shadow-md shadow-amber-500/10">
                    🧾
                </div>
                <div>
                    <span class="block text-[10px] uppercase font-black tracking-wider text-amber-600/80">Total Transaksi</span>
                    <span class="block text-lg font-black text-amber-950 mt-0.5">
                        {{ $totalOrdersCount }} Transaksi
                    </span>
                </div>
            </div>

            <!-- Metric 3: Active Date -->
            <div class="bg-white border border-gray-200 rounded-2xl p-4.5 flex items-center space-x-4 shadow-xs">
                <div class="h-10 w-10 bg-gray-100 text-gray-500 rounded-xl flex items-center justify-center text-lg font-bold">
                    📅
                </div>
                <div>
                    <span class="block text-[10px] uppercase font-black tracking-wider text-gray-400">Tanggal Terpilih</span>
                    <span class="block text-sm font-extrabold text-gray-700 mt-0.5">
                        {{ !empty($filterDate) ? \Carbon\Carbon::parse($filterDate)->translatedFormat('d F Y') : 'Semua Tanggal' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Scrollable Content Workspace -->
    <div class="flex-1 p-6">
        @if($orders->isEmpty())
            <div class="flex flex-col items-center justify-center text-center p-12 bg-white rounded-2xl border border-gray-200 max-w-lg mx-auto shadow-sm">
                <span class="text-4xl mb-4">📭</span>
                <h4 class="text-base font-bold text-gray-800">Tidak Ada Transaksi</h4>
                <p class="text-xs text-gray-500 mt-1 max-w-xs">Tidak ada catatan transaksi POS pada tanggal terpilih.</p>
                <button 
                    wire:click="$set('filterDate', '{{ now()->format('Y-m-d') }}')" 
                    class="mt-4 bg-amber-600 hover:bg-amber-500 text-white px-4 py-2 rounded-xl text-xs font-bold transition shadow-md shadow-amber-600/15"
                >
                    Lihat Hari Ini
                </button>
            </div>
        @else
            <!-- Responsive Table card container -->
            <div class="bg-white border border-gray-200 rounded-2xl shadow-xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200 uppercase font-black text-gray-500 tracking-wider">
                                <th class="py-4 px-6">No. Order</th>
                                <th class="py-4 px-6">Waktu</th>
                                <th class="py-4 px-6">Tipe Pesanan</th>
                                <th class="py-4 px-6">Meja</th>
                                <th class="py-4 px-6">Pelanggan</th>
                                <th class="py-4 px-6">Total Pembayaran</th>
                                <th class="py-4 px-6 text-center">Status</th>
                                <th class="py-4 px-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-150">
                            @foreach($orders as $order)
                                <!-- Row wrapper with Alpine expand logic -->
                                <tr 
                                    x-data="{ open: false }" 
                                    wire:key="history-row-{{ $order->id }}"
                                    class="hover:bg-gray-50/70 transition-colors duration-150"
                                >
                                    <!-- Main Row cells -->
                                    <td class="py-3.5 px-6 font-extrabold text-gray-800">
                                        {{ $order->order_number }}
                                    </td>
                                    <td class="py-3.5 px-6 text-gray-500 font-medium">
                                        {{ $order->created_at->format('H:i') }} <span class="text-[10px] text-gray-400">WIB</span>
                                    </td>
                                    <td class="py-3.5 px-6">
                                        @if($order->type === 'dine_in')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200/50">
                                                🍽️ Makan Sini
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-purple-50 text-purple-700 border border-purple-200/50">
                                                🛍️ Bungkus
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-6 font-bold text-gray-700">
                                        {{ $order->table ? 'Meja #' . $order->table->table_number : '-' }}
                                    </td>
                                    <td class="py-3.5 px-6 text-gray-600 font-semibold">
                                        {{ $order->customer_name ?: '-' }}
                                    </td>
                                    <td class="py-3.5 px-6 font-black text-gray-800">
                                        {{ formatRupiah($order->total) }}
                                    </td>
                                    <td class="py-3.5 px-6 text-center">
                                        @if($order->status === 'paid')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-100 text-emerald-800 border border-emerald-200/50">
                                                Lunas
                                            </span>
                                        @elseif($order->status === 'completed')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-blue-100 text-blue-800 border border-blue-200/50">
                                                Selesai
                                            </span>
                                        @elseif($order->status === 'payment_pending')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-yellow-100 text-yellow-800 border border-yellow-200/50">
                                                Menunggu Bayar
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-gray-100 text-gray-600 border border-gray-200">
                                                {{ $order->status }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-6 text-right">
                                        <button 
                                            @click="open = !open" 
                                            class="inline-flex items-center space-x-1 bg-gray-100 hover:bg-gray-200 hover:text-amber-600 text-gray-600 px-3 py-1.5 rounded-lg font-bold text-[10px] tracking-wide transition uppercase shrink-0"
                                        >
                                            <span x-text="open ? 'Tutup Detail' : 'Lihat Detail'"></span>
                                            <!-- Expand/Collapse Chevron -->
                                            <svg 
                                                class="w-3 h-3 transform transition-transform duration-200" 
                                                :class="open ? 'rotate-180' : 'rotate-0'"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            >
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                    </td>

                                    <!-- Expandable subtable block containing exact food breakdowns -->
                                    <template x-if="open">
                                        <tr class="bg-gray-50/50">
                                            <td colspan="8" class="p-6 border-t border-b border-gray-200">
                                                <div class="bg-white rounded-xl border border-gray-200 shadow-inner overflow-hidden max-w-3xl">
                                                    <div class="px-4.5 py-3.5 bg-gray-50 border-b border-gray-150 flex items-center justify-between">
                                                        <span class="text-[10px] font-black text-gray-500 uppercase tracking-wider">Rincian Item Makanan</span>
                                                        @if($order->notes)
                                                            <span class="text-[10px] text-amber-600 font-bold bg-amber-50 px-2.5 py-0.5 rounded-md border border-amber-200/30">
                                                                Catatan: {{ $order->notes }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                    
                                                    <ul class="divide-y divide-gray-100">
                                                        @foreach($order->orderItems as $item)
                                                            <li class="p-4 flex items-start justify-between text-xs gap-4">
                                                                <div class="space-y-1">
                                                                    <div class="flex items-center space-x-2">
                                                                        <span class="font-extrabold text-gray-800">{{ $item->item_name_snapshot ?? $item->menu?->name ?? 'Menu' }}</span>
                                                                        <span class="text-[10px] font-black text-amber-600 bg-amber-50 px-1.5 py-0.5 rounded">
                                                                            {{ $item->quantity }}x
                                                                        </span>
                                                                    </div>
                                                                    
                                                                    <!-- Item-level customization snapshots -->
                                                                    <div class="flex flex-wrap gap-2 text-[10px] mt-1 text-gray-500">
                                                                        @if($item->spicinessLevel)
                                                                            <span class="text-rose-500 font-bold">🌶️ Level: {{ $item->spicinessLevel?->name ?? '-' }}</span>
                                                                        @endif

                                                                        @if($item->toppings->isNotEmpty())
                                                                            <span class="text-gray-400">Topping:</span>
                                                                            @foreach($item->toppings as $topping)
                                                                                <span class="bg-gray-100 text-gray-600 px-1.5 py-0.5 rounded text-[9px] font-medium">
                                                                                    + {{ $topping->topping_name }} ({{ formatRupiah($topping->price) }})
                                                                                </span>
                                                                            @endforeach
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                
                                                                <!-- Computed subtotal -->
                                                                <span class="font-extrabold text-gray-700">
                                                                    {{ formatRupiah($item->subtotal) }}
                                                                </span>
                                                            </li>
                                                        @endforeach
                                                    </ul>

                                                    <!-- Invoice math summary inside detail card -->
                                                    <div class="p-4 bg-gray-50/60 border-t border-gray-100 flex justify-between items-center text-xs">
                                                        <div class="text-[10px] text-gray-500">
                                                            Pencatatan ID: <strong class="text-gray-700 font-bold">#{{ $order->id }}</strong>
                                                        </div>
                                                        <div class="font-black text-gray-800 space-x-1">
                                                            <span>Total Tagihan:</span>
                                                            <span class="text-amber-600">{{ formatRupiah($order->total) }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </template>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>

</div>
