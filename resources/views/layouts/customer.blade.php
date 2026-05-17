<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Warung Seblak Digital - Pesan Menu</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-50 min-h-screen font-sans text-gray-900 antialiased" x-data="{}">

    @include('partials._flash-message')

    <!-- Konten Utama -->
    <main class="pb-20">
        {{ $slot }}
    </main>

    <!-- Bottom Navigation -->
    <nav class="fixed bottom-0 w-full bg-white border-t border-gray-200 flex justify-around py-3 z-30 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
        <!-- Menu -->
        <a href="{{ route('customer.menu') }}" class="flex flex-col items-center {{ request()->routeIs('customer.menu') ? 'text-amber-600' : 'text-gray-500 hover:text-amber-500' }}">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
            </svg>
            <span class="text-xs font-medium mt-1">Menu</span>
        </a>

        <!-- Keranjang -->
        <a href="{{ route('customer.cart') }}" class="relative flex flex-col items-center {{ request()->routeIs('customer.cart') || request()->routeIs('customer.checkout') ? 'text-amber-600' : 'text-gray-500 hover:text-amber-500' }}">
            <div class="relative">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <livewire:customer.cart-badge />
            </div>
            <span class="text-xs font-medium mt-1">Keranjang</span>
        </a>

        <!-- Pesanan -->
        <a href="#" class="flex flex-col items-center text-gray-500 hover:text-amber-500">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="text-xs font-medium mt-1">Pesanan</span>
        </a>
    </nav>

    @livewireScripts
</body>
</html>
