<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Warung Seblak Digital')</title>

    <!-- Meta SEO -->
    <meta name="description" content="@yield('meta_description', 'Nikmati seblak terlezat dengan pemesanan digital cepat dan praktis di Warung Seblak Digital.')">

    <!-- CSS & Livewire Styles -->
    @vite('resources/css/app.css')
    @livewireStyles
</head>
<body class="bg-gray-50 text-gray-800 antialiased min-h-screen flex justify-center selection:bg-orange-500 selection:text-white overflow-x-hidden">

    <!-- Main Mobile-First Container -->
    <div class="w-full max-w-md bg-white min-h-screen shadow-xl flex flex-col relative pb-24 border-x border-gray-100/50 overflow-x-hidden">
        
        <!-- Header / Brand -->
        <header class="sticky top-0 z-20 bg-white/95 backdrop-blur-md border-b border-gray-100 px-4 py-3.5 flex items-center justify-between shadow-[0_1px_3px_rgba(0,0,0,0.02)]">
            <div class="flex items-center space-x-2.5">
                <span class="text-2xl animate-bounce">🌶️</span>
                <div>
                    <h1 class="text-base font-extrabold text-gray-900 tracking-tight">Seblak Digital</h1>
                    <p class="text-[10px] text-gray-400 font-medium">
                        @if(session('table_number'))
                            Meja {{ session('table_number') }}
                        @else
                            Pemesanan Mandiri & Cepat
                        @endif
                    </p>
                </div>
            </div>
            
            <div class="flex items-center">
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-orange-50 text-orange-600 border border-orange-100/50">
                    <span class="w-1.5 h-1.5 bg-orange-500 rounded-full mr-1 animate-pulse"></span>
                    Dine-in
                </span>
            </div>
        </header>

        <!-- Flash Message Notification -->
        @if (session()->has('message') || session()->has('error') || session()->has('success') || session()->has('status'))
            <div class="px-4 pt-4">
                @include('partials._flash-message')
            </div>
        @endif

        <!-- Main Content Area -->
        <main class="flex-grow px-4 py-4 max-w-full">
            @yield('content')
        </main>

        <!-- Bottom Navigation Bar (Fixed in Mobile-First Container) -->
        <nav class="fixed bottom-0 left-1/2 -translate-x-1/2 w-full max-w-md bg-gray-50/95 backdrop-blur-md border-t border-gray-200/80 px-3 py-2 z-50 shadow-[0_-4px_12px_rgba(0,0,0,0.03)] flex justify-between items-center rounded-t-2xl">
            
            <!-- Home -->
            @php $isHomeActive = request()->routeIs('customer.home*'); @endphp
            <a href="{{ Route::has('customer.home') ? route('customer.home') : '#' }}" 
               class="flex flex-col items-center justify-center flex-1 py-1 transition-all duration-200 group min-h-[44px] min-w-[44px] {{ $isHomeActive ? 'text-orange-500 font-semibold' : 'text-gray-500 hover:text-orange-400' }}">
                <div class="p-1 rounded-xl transition-all duration-200 group-hover:bg-orange-50/60 {{ $isHomeActive ? 'bg-orange-50 text-orange-500' : '' }}">
                    <x-icons.home class="w-5 h-5" />
                </div>
                <span class="text-[10px] mt-0.5 tracking-wide font-medium">Home</span>
            </a>

            <!-- Menu -->
            @php $isMenuActive = request()->routeIs('customer.menu*') || request()->routeIs('customer.scan*'); @endphp
            <a href="{{ Route::has('customer.menu') ? route('customer.menu') : '#' }}" 
               class="flex flex-col items-center justify-center flex-1 py-1 transition-all duration-200 group min-h-[44px] min-w-[44px] {{ $isMenuActive ? 'text-orange-500 font-semibold' : 'text-gray-500 hover:text-orange-400' }}">
                <div class="p-1 rounded-xl transition-all duration-200 group-hover:bg-orange-50/60 {{ $isMenuActive ? 'bg-orange-50 text-orange-500' : '' }}">
                    <x-icons.clipboard class="w-5 h-5" />
                </div>
                <span class="text-[10px] mt-0.5 tracking-wide font-medium">Menu</span>
            </a>

            <!-- Keranjang -->
            @php $isCartActive = request()->routeIs('customer.cart*') || request()->routeIs('customer.checkout*'); @endphp
            <a href="{{ Route::has('customer.cart') ? route('customer.cart') : '#' }}" 
               @click.prevent="if (window.location.pathname.endsWith('/menu')) { $dispatch('toggle-cart') } else { window.location.href = '{{ Route::has('customer.cart') ? route('customer.cart') : '#' }}' }"
               class="flex flex-col items-center justify-center flex-1 py-1 transition-all duration-200 group relative min-h-[44px] min-w-[44px] {{ $isCartActive ? 'text-orange-500 font-semibold' : 'text-gray-500 hover:text-orange-400' }}">
                <div class="p-1 rounded-xl transition-all duration-200 group-hover:bg-orange-50/60 {{ $isCartActive ? 'bg-orange-50 text-orange-500' : '' }}">
                    <x-icons.shopping-cart class="w-5 h-5" />
                    <!-- Badge Keranjang: Dinamis dengan Alpine.js listening to browser events -->
                    <!-- TODO: Ganti session default di bawah jika menggunakan sinkronisasi database kustom -->
                    <span x-data="{ count: {{ collect(session('cart.items', []))->sum('quantity') }} }"
                          @cart-count-updated.window="count = $event.detail"
                          x-show="count > 0"
                          class="absolute top-0.5 right-4.5 inline-flex items-center justify-center px-1.5 py-0.5 text-[8px] font-bold leading-none text-white bg-orange-500 rounded-full border border-white"
                          x-text="count"
                          style="display: none;">
                    </span>
                </div>
                <span class="text-[10px] mt-0.5 tracking-wide font-medium">Keranjang</span>
            </a>

            <!-- Pesanan Saya -->
            @php $isOrdersActive = request()->routeIs('customer.orders*') || request()->routeIs('customer.payment*'); @endphp
            <a href="{{ Route::has('customer.orders') ? route('customer.orders') : '#' }}" 
               class="flex flex-col items-center justify-center flex-1 py-1 transition-all duration-200 group min-h-[44px] min-w-[44px] {{ $isOrdersActive ? 'text-orange-500 font-semibold' : 'text-gray-500 hover:text-orange-400' }}">
                <div class="p-1 rounded-xl transition-all duration-200 group-hover:bg-orange-50/60 {{ $isOrdersActive ? 'bg-orange-50 text-orange-500' : '' }}">
                    <x-icons.document-text class="w-5 h-5" />
                </div>
                <span class="text-[10px] mt-0.5 tracking-wide font-medium">Pesanan Saya</span>
            </a>
            
        </nav>

    </div>

    <!-- JS & Livewire Scripts -->
    @livewireScripts
    @vite('resources/js/app.js')
</body>
</html>
