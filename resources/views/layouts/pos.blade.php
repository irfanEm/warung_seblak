<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>POS Kasir - Warung Seblak Digital</title>
    <!-- Tailwind CSS & JS Assets via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full flex flex-col overflow-hidden text-gray-900 antialiased font-sans bg-gray-50">

    <!-- POS Header -->
    <header class="bg-white border-b border-gray-200 h-16 flex items-center justify-between px-6 shrink-0 shadow-sm z-30">
        <!-- Left: Logo & Navigation back to POS Index -->
        <div class="flex items-center space-x-4">
            <a href="{{ route('pos.index') }}" class="flex items-center space-x-2.5 hover:opacity-90">
                <span class="text-2xl">🏪</span>
                <span class="text-lg font-bold tracking-tight text-gray-800">
                    Kasir <span class="text-amber-600 font-extrabold">Seblak Digital</span>
                </span>
            </a>
        </div>

        <!-- Right: Cashier Profile, Navigation and Log Out -->
        <div class="flex items-center space-x-4">
            <!-- Active Cashier Name -->
            <div class="hidden sm:flex flex-col text-right">
                <span class="text-xs text-gray-500 font-medium">Kasir Aktif:</span>
                <span class="text-sm font-bold text-gray-700">{{ auth()->user()->name ?? 'Kasir' }}</span>
            </div>

            <!-- Vertical Separator -->
            <div class="hidden sm:block h-6 w-px bg-gray-200"></div>

            <!-- Transaction History Toggle -->
            @if(request()->routeIs('pos.history'))
                <a href="{{ route('pos.index') }}" class="flex items-center space-x-2 bg-amber-50 hover:bg-amber-100 text-amber-700 px-4 py-2 rounded-lg transition duration-150 text-sm font-semibold border border-amber-200/50 shadow-sm">
                    <span>🛒 Buka POS</span>
                </a>
            @else
                <a href="{{ route('pos.history') }}" class="flex items-center space-x-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition duration-150 text-sm font-semibold border border-gray-200/60 shadow-sm">
                    <!-- History Icon -->
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 00-2 2h10a2 2 0 002-2v-1M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    <span>Riwayat POS</span>
                </a>
            @endif

            <!-- Logout Button -->
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="flex items-center space-x-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 px-3.5 py-2 rounded-lg transition duration-150 text-sm font-medium border border-rose-200/40">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    <span class="hidden md:inline">Keluar</span>
                </button>
            </form>
        </div>
    </header>

    <!-- Main POS Body: Full screen flex child -->
    <main class="flex-1 overflow-hidden">
        {{ $slot }}
    </main>

    @livewireScripts
</body>
</html>
