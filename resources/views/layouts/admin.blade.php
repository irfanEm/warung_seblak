<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - Warung Seblak Digital</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-100 antialiased font-sans text-gray-900" x-data="{ sidebarOpen: false }">

    @include('partials._flash-message')

    <!-- Layout Wrapper: Flex & H-Screen -->
    <div class="flex h-screen overflow-hidden">

        <!-- Sidebar Overlay (Mobile) -->
        <div x-show="sidebarOpen" 
             class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75 lg:hidden"
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="sidebarOpen = false" style="display: none;"></div>

        <!-- Sidebar -->
        <!-- Fixed di mobile, relative/flex-shrink-0 di desktop. Tinggi harus full h-full -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
               class="fixed inset-y-0 left-0 z-50 w-64 bg-gray-900 text-white transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:flex-shrink-0 lg:h-full lg:flex lg:flex-col shadow-xl">
            <div class="flex items-center justify-center h-16 bg-gray-900 border-b border-gray-800 shrink-0">
                <span class="text-white font-bold uppercase text-xl tracking-wide">Seblak Admin</span>
            </div>
            
            <nav class="mt-5 px-4 space-y-2 flex-1 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center py-2.5 px-4 rounded-md transition duration-200 hover:bg-gray-800 hover:text-white {{ request()->routeIs('admin.dashboard') ? 'bg-gray-800 text-white font-medium' : 'text-gray-400' }}">Dashboard</a>
                <a href="{{ route('admin.menu.index') }}" class="flex items-center py-2.5 px-4 rounded-md transition duration-200 hover:bg-gray-800 hover:text-white {{ request()->routeIs('admin.menu.*') ? 'bg-gray-800 text-white font-medium' : 'text-gray-400' }}">Menu</a>
                <a href="{{ route('admin.table.index') }}" class="flex items-center py-2.5 px-4 rounded-md transition duration-200 hover:bg-gray-800 hover:text-white {{ request()->routeIs('admin.table.*') ? 'bg-gray-800 text-white font-medium' : 'text-gray-400' }}">Meja & QR Code</a>
                
                @role('Admin|Dapur')
                <a href="{{ route('kitchen.index') }}" target="_blank" class="flex items-center py-2.5 px-4 rounded-md transition duration-200 hover:bg-gray-800 hover:text-white {{ request()->routeIs('kitchen.index') ? 'bg-gray-800 text-white font-medium' : 'text-gray-400' }}">
                    <span class="mr-2">🔥</span> Dapur (KDS)
                </a>
                @endrole
            </nav>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <!-- Top Navbar -->
            <header class="bg-white shadow-sm border-b border-gray-200 h-16 shrink-0 flex items-center justify-between px-4 sm:px-6">
                <!-- Mobile Menu Toggle -->
                <button @click="sidebarOpen = true" class="text-gray-500 hover:text-gray-700 lg:hidden focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <div class="font-semibold text-gray-800 text-lg lg:hidden">Warung Seblak</div>
                
                <!-- Spacer (Push dropdown to right on desktop) -->
                <div class="hidden lg:block flex-1"></div>
                
                <!-- User Dropdown (Simulasi) -->
                <div class="flex items-center space-x-4">
                    <span class="text-sm font-medium text-gray-700">{{ auth()->user()->name ?? 'Admin' }}</span>
                </div>
            </header>

            <!-- Main Content Scrollable -->
            <main class="flex-1 overflow-y-auto bg-gray-100 p-4 sm:p-6 lg:p-8">
                {{ $slot }}
            </main>
        </div>

    </div>

    @livewireScripts
</body>
</html>
