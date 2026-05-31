<div>
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Dashboard Admin</h1>
        <p class="text-sm text-gray-500 mt-1">Ringkasan aktivitas dan pesanan hari ini.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <!-- Stat: Pesanan Hari Ini -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex flex-col">
            <div class="flex items-center gap-3 mb-3">
                <div class="p-2.5 bg-blue-50 text-blue-600 rounded-lg">
                    <x-icons.shopping-bag class="w-6 h-6" />
                </div>
                <h3 class="text-sm font-semibold text-gray-600">Pesanan Hari Ini</h3>
            </div>
            <p class="text-3xl font-extrabold text-gray-900 mt-auto">{{ $totalOrdersToday }}</p>
        </div>

        <!-- Stat: Pendapatan -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex flex-col">
            <div class="flex items-center gap-3 mb-3">
                <div class="p-2.5 bg-green-50 text-green-600 rounded-lg">
                    <x-icons.currency-dollar class="w-6 h-6" />
                </div>
                <h3 class="text-sm font-semibold text-gray-600">Pendapatan</h3>
            </div>
            <p class="text-2xl font-extrabold text-gray-900 mt-auto">Rp {{ number_format($revenueToday, 0, ',', '.') }}</p>
        </div>

        <!-- Stat: Pesanan Aktif -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex flex-col">
            <div class="flex items-center gap-3 mb-3">
                <div class="p-2.5 bg-orange-50 text-orange-600 rounded-lg">
                    <x-icons.fire class="w-6 h-6" />
                </div>
                <h3 class="text-sm font-semibold text-gray-600">Pesanan Aktif</h3>
            </div>
            <p class="text-3xl font-extrabold text-gray-900 mt-auto">{{ $activeOrders }}</p>
        </div>

        <!-- Stat: Menunggu Pembayaran -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex flex-col">
            <div class="flex items-center gap-3 mb-3">
                <div class="p-2.5 bg-red-50 text-red-600 rounded-lg">
                    <x-icons.clock class="w-6 h-6" />
                </div>
                <h3 class="text-sm font-semibold text-gray-600">Menunggu Bayar</h3>
            </div>
            <p class="text-3xl font-extrabold text-gray-900 mt-auto">{{ $pendingPayments }}</p>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-lg font-bold text-gray-900">5 Pesanan Terbaru</h2>
            <a href="{{ route('admin.orders.index') }}" class="text-sm font-semibold text-orange-600 hover:text-orange-800 transition-colors">
                Lihat Semua &rarr;
            </a>
        </div>
        
        <!-- Desktop Table -->
        <div class="hidden md:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">No. Pesanan</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Pelanggan</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Total</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase">Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($recentOrders as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $order['order_number'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $order['customer_name'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Rp {{ number_format($order['total'], 0, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
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
                                        'ready' => 'Siap',
                                        'completed' => 'Selesai',
                                        'cancelled' => 'Dibatalkan'
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border border-transparent {{ $statusColors[$order['status']] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ $statusLabels[$order['status']] ?? ucfirst($order['status']) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">
                                {{ \Carbon\Carbon::parse($order['created_at'])->diffForHumans() }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">Belum ada pesanan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card Stack -->
        <div class="md:hidden divide-y divide-gray-100">
            @forelse($recentOrders as $order)
                <div class="p-4">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <p class="text-sm font-bold text-gray-900">{{ $order['order_number'] }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $order['customer_name'] }}</p>
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
                        @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium border border-transparent {{ $statusColors[$order['status']] ?? 'bg-gray-100 text-gray-700' }}">
                            {{ ucfirst($order['status']) }}
                        </span>
                    </div>
                    <div class="flex justify-between items-end mt-3">
                        <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($order['created_at'])->format('d M H:i') }}</p>
                        <p class="text-sm font-bold text-gray-900">Rp {{ number_format($order['total'], 0, ',', '.') }}</p>
                    </div>
                </div>
            @empty
                <div class="p-6 text-center text-sm text-gray-500">Belum ada pesanan.</div>
            @endforelse
        </div>
    </div>
</div>
