<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
            <p class="mt-1 text-sm text-gray-600">Selamat datang, {{ auth()->user()->name }}. Anda login sebagai Admin.</p>
        </div>
        <p class="text-sm text-gray-500">{{ now()->translatedFormat('l, d F Y') }}</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
        <!-- Total Orders Today -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 lg:p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 rounded-md p-3 bg-blue-100">
                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Pesanan Hari Ini</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalOrdersToday }}</p>
                </div>
            </div>
        </div>

        <!-- Revenue Today -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 lg:p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 rounded-md p-3 bg-green-100">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Pendapatan Hari Ini</p>
                    <p class="text-lg lg:text-xl font-bold text-gray-900">Rp{{ formatRupiah($revenueToday) }}</p>
                </div>
            </div>
        </div>

        <!-- Active Orders -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 lg:p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 rounded-md p-3 bg-amber-100">
                    <svg class="h-6 w-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Pesanan Aktif</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $activeOrders }}</p>
                </div>
            </div>
        </div>

        <!-- Available Menus -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 lg:p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 rounded-md p-3 bg-purple-100">
                    <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Menu Tersedia</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $availableMenus }}</p>
                </div>
            </div>
        </div>

        <!-- Active Tables -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 lg:p-6 col-span-2 lg:col-span-4">
            <div class="flex items-center">
                <div class="flex-shrink-0 rounded-md p-3 bg-indigo-100">
                    <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Meja Aktif</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $activeTables }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-4 lg:px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Pesanan Terbaru</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Order</th>
                        <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggan</th>
                        <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($recentOrders as $order)
                        <tr>
                            <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $order->order_number }}
                                <span class="block text-xs text-gray-500">{{ $order->tracking_code }}</span>
                            </td>
                            <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->customer_name ?? '-' }}</td>
                            <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp{{ formatRupiah($order->total) }}</td>
                            <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-sm">
                                <x-status-badge :status="$order->status" />
                            </td>
                            <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $order->created_at->diffForHumans() }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 lg:px-6 py-8 text-center text-sm text-gray-500">Belum ada pesanan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
