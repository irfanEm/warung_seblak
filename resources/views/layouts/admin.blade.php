<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - Warung Seblak Digital</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-100 antialiased font-sans" x-data="{ sidebarOpen: false }">

    @include('partials._flash-message')

    <!-- Navbar Mobile / Topbar -->
    <header class="bg-white shadow-sm border-b border-gray-200 fixed w-full z-30 top-0 lg:pl-64">
        <div class="px-4 py-3 flex items-center justify-between lg:justify-end">
            <!-- Mobile Menu Toggle -->
            <button @click="sidebarOpen = true" class="text-gray-500 hover:text-gray-700 lg:hidden focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>
            <div class="font-semibold text-gray-800 text-lg lg:hidden">Warung Seblak</div>
            
            <!-- User Dropdown (Simulasi) -->
            <div class="flex items-center space-x-4">
                <span class="text-sm font-medium text-gray-700">{{ auth()->user()->name ?? 'Admin' }}</span>
            </div>
        </div>
    </header>

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
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
           class="fixed inset-y-0 left-0 z-50 w-64 bg-gray-900 text-white transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 lg:fixed">
        <div class="flex items-center justify-center h-16 bg-gray-900 border-b border-gray-800">
            <span class="text-white font-bold uppercase text-xl">Seblak Admin</span>
        </div>
        <nav class="mt-5 px-4 space-y-2">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-gray-800 hover:text-white {{ request()->routeIs('admin.dashboard') ? 'bg-gray-800 text-white' : 'text-gray-400' }}">Dashboard</a>
            <a href="{{ route('admin.menu.index') }}" class="flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-gray-800 hover:text-white {{ request()->routeIs('admin.menu.*') ? 'bg-gray-800 text-white' : 'text-gray-400' }}">Menu</a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="pt-16 lg:pl-64 min-h-screen">
        <div class="p-4 sm:p-6">
            {{ $slot }}
        </div>
    </main>

    @livewireScripts
</body>
</html>
