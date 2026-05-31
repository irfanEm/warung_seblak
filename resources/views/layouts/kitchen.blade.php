<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dapur KDS - Warung Seblak Digital</title>
    <!-- Tailwind CSS & JS Assets via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-950 text-gray-100 antialiased font-sans h-full flex flex-col overflow-hidden overflow-x-hidden">

    <!-- Topbar Dapur -->
    <header class="bg-gray-900 border-b border-gray-800 h-16 flex items-center justify-between px-6 shrink-0 shadow-md">
        <!-- Brand / Title -->
        <div class="flex items-center space-x-3">
            <span class="text-2xl">🔥</span>
            <h1 class="text-xl font-bold uppercase tracking-wider text-white">
                Dapur - <span class="text-orange-500">Warung Seblak</span>
            </h1>
        </div>

        <!-- Right Side Actions: Sound Toggle & Logout -->
        <div class="flex items-center space-x-6">
            <!-- Audio Toggle & Control is handled inside the Livewire component but layout provides layout visual state if needed -->
            {{ $header_actions ?? '' }}

            <!-- Logout Button -->
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="flex items-center space-x-2 bg-red-600/20 hover:bg-red-600 hover:text-white text-red-400 px-4 py-2 rounded-lg transition duration-200 text-sm font-medium border border-red-500/30">
                    <!-- Logout Icon -->
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    <span>Keluar</span>
                </button>
            </form>
        </div>
    </header>

    <!-- Main Content Area: Scrollable grid -->
    <main class="flex-1 overflow-hidden">
        {{ $slot }}
    </main>

    @livewireScripts
</body>
</html>
