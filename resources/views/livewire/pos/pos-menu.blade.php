<div class="md:col-span-7 flex flex-col min-w-0 bg-gray-50 overflow-hidden {{ $activeTab === 'menu' ? 'flex' : 'hidden md:flex' }}">
    
    <!-- Category Chip Selection Bar -->
    <div class="bg-white border-b border-gray-200 px-6 py-3.5 shrink-0 flex items-center space-x-3 overflow-x-auto snap-x hide-scrollbar scroll-smooth">
        <button 
            wire:click="selectCategory(null)" 
            class="snap-start shrink-0 px-4.5 py-2 rounded-full text-xs font-bold uppercase tracking-wider transition-all {{ is_null($selectedCategory) ? 'bg-amber-600 text-white shadow-md shadow-amber-600/10' : 'bg-gray-100 text-gray-600 border border-gray-200 hover:bg-gray-200' }}"
        >
            Semua
        </button>
        @foreach($categories as $category)
            <button 
                wire:click="selectCategory({{ $category->id }})" 
                class="snap-start shrink-0 px-4.5 py-2 rounded-full text-xs font-bold uppercase tracking-wider transition-all {{ $selectedCategory === $category->id ? 'bg-amber-600 text-white shadow-md shadow-amber-600/10' : 'bg-gray-100 text-gray-600 border border-gray-200 hover:bg-gray-200' }}"
            >
                {{ $category->name }}
            </button>
        @endforeach
    </div>

    <!-- Scrollable Product List Grid -->
    <div class="flex-1 overflow-y-auto p-6">
        <!-- Success & Error Alert Messages -->
        @if (session()->has('success'))
            <div class="mb-4 bg-emerald-50 border border-emerald-300 text-emerald-800 px-4 py-3 rounded-xl text-sm flex items-center justify-between shadow-sm" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 8000)">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-semibold">{{ session('success') }}</span>
                </div>
                @if ($lastOrderId)
                    <a href="{{ route('pos.receipt', $lastOrderId) }}" target="_blank" class="ml-4 inline-flex items-center px-3 py-1.5 bg-emerald-600 text-white text-xs font-medium rounded-lg hover:bg-emerald-700 transition shrink-0">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        Cetak Struk
                    </a>
                @endif
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-4 bg-rose-50 border border-rose-300 text-rose-800 px-4 py-3 rounded-xl text-sm flex items-center space-x-2 shadow-sm" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)">
                <svg class="w-5 h-5 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="font-semibold">{{ session('error') }}</span>
            </div>
        @endif

        @if($menus->isEmpty())
            <div class="flex flex-col items-center justify-center text-center p-12 bg-white rounded-2xl border border-gray-200">
                <span class="text-4xl mb-4">🍽️</span>
                <h4 class="text-base font-bold text-gray-800">Menu Tidak Ditemukan</h4>
                <p class="text-xs text-gray-500 mt-1 max-w-xs">Belum ada makanan aktif di kategori ini.</p>
            </div>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($menus as $menu)
                    <!-- Menu Product Card -->
                    <div 
                        wire:click="openAddModal({{ $menu->id }})"
                        wire:key="menu-card-{{ $menu->id }}"
                        class="group bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition-all duration-200 cursor-pointer flex flex-col relative active:scale-95 transform"
                    >
                        <!-- Thumbnail -->
                        <div class="aspect-square bg-gray-50 relative shrink-0 overflow-hidden">
                            @if($menu->image)
                                <img 
                                    src="{{ $menu->image ? '/storage/' . $menu->image : '' }}" 
                                    alt="{{ $menu->name }}" 
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                >
                            @else
                                <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                                    <svg class="w-10 h-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                            
                            <!-- Price Tag Badge Overlay -->
                            <div class="absolute bottom-2 left-2 bg-gray-900/80 text-white rounded-lg px-2 py-0.5 text-xs font-black backdrop-blur-xs">
                                {{ formatRupiah($menu->price) }}
                            </div>
                        </div>
                        
                        <!-- Card Info Body -->
                        <div class="p-3 flex-1 flex flex-col justify-between">
                            <div>
                                <h3 class="font-extrabold text-xs text-gray-800 leading-snug group-hover:text-amber-600 transition-colors">
                                    {{ $menu->name }}
                                </h3>
                                <p class="text-[10px] text-gray-500 mt-1 line-clamp-2">{{ $menu->description }}</p>
                            </div>
                            
                            <!-- Card Action Footer -->
                            <div class="mt-2.5 flex items-center justify-between border-t border-gray-100 pt-2 shrink-0">
                                <span class="text-[10px] font-semibold text-gray-400">Stock: {{ $menu->stock_quantity ?? 'Unlim.' }}</span>
                                <span class="h-6 w-6 bg-amber-50 text-amber-600 group-hover:bg-amber-600 group-hover:text-white rounded-full flex items-center justify-center transition-all shadow-xs shrink-0 font-bold text-xs">
                                    +
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Product Customization Modal -->
    @if($showModal && $selectedMenu)
        <div class="relative z-50 flex items-center justify-center">
            <div wire:click="closeModal" class="fixed inset-0 bg-black/60 backdrop-blur-xs transition-opacity"></div>

            <div class="fixed inset-0 overflow-y-auto flex items-center justify-center p-4">
                <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden relative flex flex-col max-h-[90vh]">
                    
                    <!-- Header -->
                    <div class="p-5 border-b border-gray-150 bg-gray-50 flex items-center justify-between shrink-0">
                        <div>
                            <h2 class="text-base font-extrabold text-gray-800 leading-snug">{{ $selectedMenu->name }}</h2>
                            <p class="text-xs text-amber-600 font-bold mt-0.5">Base Price: {{ formatRupiah($selectedMenu->price) }}</p>
                        </div>
                        <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 p-1.5 hover:bg-gray-150 rounded-full shrink-0 transition">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>

                    <!-- Scrollable Modal Content -->
                    <div class="p-6 overflow-y-auto flex-1 space-y-6">
                        
                        <!-- Topping Choices -->
                        @if($selectedMenu->toppings->isNotEmpty())
                            <div>
                                <h3 class="text-xs uppercase font-black tracking-wider text-gray-400 mb-2.5">Pilihan Topping</h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                    @foreach($selectedMenu->toppings as $topping)
                                        <label class="flex items-center justify-between p-3 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 hover:border-gray-300 transition active:bg-gray-100/50">
                                            <div class="flex items-center">
                                                <input type="checkbox" wire:model="selectedToppings" value="{{ $topping->id }}" class="h-4 w-4 text-amber-600 focus:ring-amber-500/20 border-gray-300 rounded">
                                                <span class="ml-3 text-xs font-bold text-gray-700">{{ $topping->name }}</span>
                                            </div>
                                            <span class="text-xs font-medium text-gray-500">+ {{ formatRupiah($topping->price) }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Spiciness Choices -->
                        @if($selectedMenu->spicinessLevels->isNotEmpty())
                            <div>
                                <h3 class="text-xs uppercase font-black tracking-wider text-gray-400 mb-2.5">Tingkat Pedas (Pilih 1)</h3>
                                <div class="grid grid-cols-3 gap-2">
                                    @foreach($selectedMenu->spicinessLevels as $spiciness)
                                        <label class="relative border border-gray-200 rounded-xl p-3 flex items-center justify-center cursor-pointer hover:bg-gray-50 hover:border-gray-300 transition active:scale-[0.98] transform">
                                            <input type="radio" wire:model="selectedSpiciness" value="{{ $spiciness->id }}" class="sr-only">
                                            <span class="text-xs font-extrabold {{ $selectedSpiciness == $spiciness->id ? 'text-amber-600' : 'text-gray-700' }}">
                                                {{ $spiciness->name }}
                                            </span>
                                            <div class="absolute inset-0 border-2 rounded-xl pointer-events-none {{ $selectedSpiciness == $spiciness->id ? 'border-amber-500 bg-amber-50/10' : 'border-transparent' }}"></div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Sticky Footer (Quantity + Submit) -->
                    <div class="p-5 border-t border-gray-150 bg-gray-50 shrink-0 space-y-4">
                        @error('quantity')
                            <span class="text-rose-600 text-[10px] font-bold block">⚠️ {{ $message }}</span>
                        @enderror
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-gray-700">Jumlah Porsi</span>
                            <div class="flex items-center space-x-3.5 bg-white border border-gray-200 rounded-xl p-1 shrink-0 shadow-xs">
                                <button type="button" wire:click="$set('quantity', {{ max(1, $quantity - 1) }})" class="w-8 h-8 flex items-center justify-center bg-gray-50 hover:bg-gray-100 rounded-lg text-gray-600 font-black text-sm">-</button>
                                <span class="text-xs font-black w-6 text-center text-gray-800">{{ $quantity }}</span>
                                <button type="button" wire:click="$set('quantity', {{ $quantity + 1 }})" class="w-8 h-8 flex items-center justify-center bg-gray-50 hover:bg-gray-100 rounded-lg text-gray-600 font-black text-sm">+</button>
                            </div>
                        </div>

                        <button wire:click="addToCart" class="w-full bg-amber-600 hover:bg-amber-500 text-white font-extrabold py-3.5 px-4 rounded-xl shadow-md shadow-amber-600/10 active:scale-95 transform transition flex justify-center items-center text-xs tracking-wider uppercase">
                            <span wire:loading.remove wire:target="addToCart">Tambah ke Keranjang</span>
                            <span wire:loading wire:target="addToCart">Memproses...</span>
                        </button>
                    </div>

                </div>
            </div>
        </div>
    @endif
</div>
