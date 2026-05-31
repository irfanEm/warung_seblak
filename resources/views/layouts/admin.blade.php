<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin - Warung Seblak Digital')</title>

    <!-- Meta SEO -->
    <meta name="description" content="@yield('meta_description', 'Panel Dashboard Admin Warung Seblak Digital - Kelola menu, meja, transaksi, dan laporan dengan mudah.')">

    <!-- CSS & Livewire Styles -->
    @vite('resources/css/app.css')
    @livewireStyles
</head>
<body class="bg-gray-100 text-gray-800 antialiased h-full overflow-hidden" x-data="{ sidebarOpen: window.innerWidth >= 1024 }">

    <!-- Flash Message Notification -->
    @if (session()->has('message') || session()->has('error') || session()->has('success') || session()->has('status'))
        <div class="fixed top-4 right-4 z-50 max-w-sm">
            @include('partials._flash-message')
        </div>
    @endif

    <!-- Layout Wrapper -->
    <div class="flex h-screen overflow-hidden">

        <!-- Sidebar Overlay (Mobile) -->
        <div x-show="sidebarOpen" 
             class="fixed inset-0 z-40 bg-gray-900/40 lg:hidden backdrop-blur-xs"
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="sidebarOpen = false" 
             style="display: none;"></div>

        <!-- Sidebar Panel -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
               class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200/80 transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:flex-shrink-0 lg:h-full lg:flex lg:flex-col shadow-lg lg:shadow-none">
            
            <!-- Sidebar Header / Brand -->
            <div class="flex items-center justify-between h-16 px-6 border-b border-gray-100 shrink-0 bg-white">
                <a href="#" class="flex items-center space-x-2">
                    <span class="text-2xl">🌶️</span>
                    <span class="font-extrabold text-lg text-gray-900 tracking-tight">Seblak Admin</span>
                </a>
                
                <!-- Close Button (Mobile Only) -->
                <button @click="sidebarOpen = false" class="lg:hidden p-1 rounded-lg text-gray-400 hover:bg-gray-50 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <!-- Sidebar Navigation Links -->
            <nav class="flex-grow p-4 space-y-1 overflow-y-auto bg-white scrollbar-thin scrollbar-thumb-gray-200">
                
                <!-- 1. Dashboard -->
                @php $isDashboardActive = request()->routeIs('admin.dashboard*'); @endphp
                <a href="{{ Route::has('admin.dashboard') ? route('admin.dashboard') : '#' }}" 
                   @click="if (window.innerWidth < 1024) sidebarOpen = false"
                   class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group {{ $isDashboardActive ? 'bg-orange-50 text-orange-600' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                    <x-icons.home class="mr-3 w-5 h-5 {{ $isDashboardActive ? 'text-orange-500' : 'text-gray-400 group-hover:text-gray-600' }}" />
                    <span>Dashboard</span>
                </a>

                <!-- 2. Menu -->
                @php $isMenuActive = request()->routeIs('admin.menus*') || request()->routeIs('admin.menu*'); @endphp
                <a href="{{ Route::has('admin.menus.index') ? route('admin.menus.index') : (Route::has('admin.menu.index') ? route('admin.menu.index') : '#') }}" 
                   @click="if (window.innerWidth < 1024) sidebarOpen = false"
                   class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group {{ $isMenuActive ? 'bg-orange-50 text-orange-600' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                    <x-icons.clipboard class="mr-3 w-5 h-5 {{ $isMenuActive ? 'text-orange-500' : 'text-gray-400 group-hover:text-gray-600' }}" />
                    <span>Menu</span>
                </a>

                <!-- 3. Kategori (Terpisah dari Promo) -->
                @php $isKategoriActive = request()->routeIs('admin.categories*') || request()->routeIs('admin.category*'); @endphp
                <a href="{{ Route::has('admin.categories.index') ? route('admin.categories.index') : (Route::has('admin.category.index') ? route('admin.category.index') : '#') }}" 
                   @click="if (window.innerWidth < 1024) sidebarOpen = false"
                   class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group {{ $isKategoriActive ? 'bg-orange-50 text-orange-600' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                    <x-icons.folder class="mr-3 w-5 h-5 {{ $isKategoriActive ? 'text-orange-500' : 'text-gray-400 group-hover:text-gray-600' }}" />
                    <span>Kategori</span>
                </a>

                <!-- 4. Topping -->
                @php $isToppingActive = request()->routeIs('admin.toppings*') || request()->routeIs('admin.topping*'); @endphp
                <a href="{{ Route::has('admin.toppings.index') ? route('admin.toppings.index') : (Route::has('admin.topping.index') ? route('admin.topping.index') : '#') }}" 
                   @click="if (window.innerWidth < 1024) sidebarOpen = false"
                   class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group {{ $isToppingActive ? 'bg-orange-50 text-orange-600' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                    <x-icons.sparkles class="mr-3 w-5 h-5 {{ $isToppingActive ? 'text-orange-500' : 'text-gray-400 group-hover:text-gray-600' }}" />
                    <span>Topping</span>
                </a>

                <!-- 5. Level Pedas -->
                @php $isPedasActive = request()->routeIs('admin.spiciness*') || request()->routeIs('admin.spiciness-level*'); @endphp
                <a href="{{ Route::has('admin.spiciness.index') ? route('admin.spiciness.index') : (Route::has('admin.spiciness-level.index') ? route('admin.spiciness-level.index') : '#') }}" 
                   @click="if (window.innerWidth < 1024) sidebarOpen = false"
                   class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group {{ $isPedasActive ? 'bg-orange-50 text-orange-600' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                    <x-icons.fire class="mr-3 w-5 h-5 {{ $isPedasActive ? 'text-orange-500' : 'text-gray-400 group-hover:text-gray-600' }}" />
                    <span>Level Pedas</span>
                </a>

                <!-- 6. Meja -->
                @php $isMejaActive = request()->routeIs('admin.tables*'); @endphp
                <a href="{{ route('admin.tables.index') }}" 
                   @click="if (window.innerWidth < 1024) sidebarOpen = false"
                   class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group {{ $isMejaActive ? 'bg-orange-50 text-orange-600' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                    <x-icons.table class="mr-3 w-5 h-5 {{ $isMejaActive ? 'text-orange-500' : 'text-gray-400 group-hover:text-gray-600' }}" />
                    <span>Meja</span>
                </a>

                <!-- 7. Pesanan -->
                @php $isPesananActive = request()->routeIs('admin.orders*'); @endphp
                <a href="{{ route('admin.orders.index') }}" 
                   @click="if (window.innerWidth < 1024) sidebarOpen = false"
                   class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group {{ $isPesananActive ? 'bg-orange-50 text-orange-600' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                    <x-icons.shopping-bag class="mr-3 w-5 h-5 {{ $isPesananActive ? 'text-orange-500' : 'text-gray-400 group-hover:text-gray-600' }}" />
                    <span>Pesanan</span>
                </a>

                <!-- POS -->
                @php $isPosActive = request()->routeIs('admin.pos*'); @endphp
                <a href="{{ route('admin.pos') }}" 
                   @click="if (window.innerWidth < 1024) sidebarOpen = false"
                   class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group {{ $isPosActive ? 'bg-orange-50 text-orange-600' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                    <x-icons.shopping-cart class="mr-3 w-5 h-5 {{ $isPosActive ? 'text-orange-500' : 'text-gray-400 group-hover:text-gray-600' }}" />
                    <span>POS Kasir</span>
                </a>

                <!-- 8. Promo -->
                @php $isPromoActive = request()->routeIs('admin.promos*') || request()->routeIs('admin.promo*'); @endphp
                <a href="{{ Route::has('admin.promos.index') ? route('admin.promos.index') : (Route::has('admin.promo.index') ? route('admin.promo.index') : '#') }}" 
                   @click="if (window.innerWidth < 1024) sidebarOpen = false"
                   class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group {{ $isPromoActive ? 'bg-orange-50 text-orange-600' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                    <x-icons.tag class="mr-3 w-5 h-5 {{ $isPromoActive ? 'text-orange-500' : 'text-gray-400 group-hover:text-gray-600' }}" />
                    <span>Promo</span>
                </a>

                <!-- 9. Driver -->
                @php $isDriverActive = request()->routeIs('admin.drivers*') || request()->routeIs('admin.driver*'); @endphp
                <a href="{{ Route::has('admin.drivers.index') ? route('admin.drivers.index') : (Route::has('admin.driver.index') ? route('admin.driver.index') : '#') }}" 
                   @click="if (window.innerWidth < 1024) sidebarOpen = false"
                   class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group {{ $isDriverActive ? 'bg-orange-50 text-orange-600' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                    <x-icons.truck class="mr-3 w-5 h-5 {{ $isDriverActive ? 'text-orange-500' : 'text-gray-400 group-hover:text-gray-600' }}" />
                    <span>Driver</span>
                </a>

                <!-- 10. Laporan -->
                @php $isLaporanActive = request()->routeIs('admin.reports*') || request()->routeIs('admin.report*'); @endphp
                <a href="{{ Route::has('admin.reports.index') ? route('admin.reports.index') : (Route::has('admin.report.index') ? route('admin.report.index') : '#') }}" 
                   @click="if (window.innerWidth < 1024) sidebarOpen = false"
                   class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group {{ $isLaporanActive ? 'bg-orange-50 text-orange-600' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                    <x-icons.chart-bar class="mr-3 w-5 h-5 {{ $isLaporanActive ? 'text-orange-500' : 'text-gray-400 group-hover:text-gray-600' }}" />
                    <span>Laporan</span>
                </a>

                <!-- 11. Pengaturan -->
                @php $isPengaturanActive = request()->routeIs('admin.settings*') || request()->routeIs('admin.setting*'); @endphp
                <a href="{{ Route::has('admin.settings') ? route('admin.settings') : '#' }}" 
                   @click="if (window.innerWidth < 1024) sidebarOpen = false"
                   class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group {{ $isPengaturanActive ? 'bg-orange-50 text-orange-600' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                    <x-icons.cog class="mr-3 w-5 h-5 {{ $isPengaturanActive ? 'text-orange-500' : 'text-gray-400 group-hover:text-gray-600' }}" />
                    <span>Pengaturan</span>
                </a>

            </nav>
            
            <!-- Sidebar Footer / Copyright -->
            <div class="p-4 border-t border-gray-100 bg-gray-50 shrink-0 text-center">
                <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-wider">Warung Seblak Digital v1.0</p>
                <p class="text-[8px] text-gray-400 mt-0.5">&copy; {{ date('Y') }} All Rights Reserved</p>
            </div>
            
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            
            <!-- Top Header Navbar -->
            <header class="bg-white border-b border-gray-200/80 h-16 shrink-0 flex items-center justify-between px-4 sm:px-6 shadow-[0_1px_3px_rgba(0,0,0,0.01)]">
                <div class="flex items-center space-x-3">
                    <!-- Hamburger Toggle Button -->
                    <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-gray-700 hover:bg-gray-50 p-2 rounded-xl focus:outline-none transition-colors">
                        <x-icons.menu class="w-6 h-6" />
                    </button>
                    
                    <h2 class="font-bold text-gray-800 text-lg hidden md:block">🌶️ Panel Administrasi</h2>
                    <h2 class="font-bold text-gray-800 text-base md:hidden">Seblak Admin</h2>
                </div>
                
                <!-- Right Side Info (User Profile info, etc) -->
                <div class="flex items-center space-x-4">
                    <div class="flex flex-col text-right hidden sm:flex">
                        <span class="text-sm font-semibold text-gray-800">{{ auth()->user()->name ?? 'Administrator' }}</span>
                        <span class="text-[10px] text-orange-500 font-bold uppercase tracking-wider">
                            @if(auth()->user() && auth()->user()->roles->isNotEmpty())
                                {{ auth()->user()->roles->first()->name }}
                            @else
                                Owner
                            @endif
                        </span>
                    </div>
                    
                    <!-- Avatar / Dropdown (Simulasi) -->
                    <div class="relative">
                        <button class="flex text-sm border-2 border-orange-500 rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-all">
                            <span class="sr-only">Open user menu</span>
                            <div class="h-9 w-9 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 font-extrabold text-sm">
                                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 2)) }}
                            </div>
                        </button>
                    </div>
                </div>
            </header>

            <!-- Main Dynamic Content Wrapper -->
            <main class="flex-grow overflow-y-auto bg-gray-50 p-4 sm:p-6 lg:p-8">
                <div class="max-w-7xl mx-auto">
                    @yield('content', $slot ?? '')
                </div>
            </main>
            
        </div>

    </div>

    <!-- JS & Livewire Scripts -->
    @livewireScripts
    @vite('resources/js/app.js')
</body>
</html>
