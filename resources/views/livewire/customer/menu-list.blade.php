<div class="space-y-6 pb-6">
    <!-- Category Bar (Horizontal Scrollable) -->
    <div class="sticky top-[73px] z-10 -mx-4 bg-white/95 backdrop-blur-md py-3 px-4 border-b border-gray-100 flex items-center space-x-2 overflow-x-auto scrollbar-none">
        <!-- Button Semua -->
        <button wire:click="selectCategory(null)"
                class="shrink-0 px-4 py-2 text-xs font-bold rounded-full transition-all duration-200 {{ is_null($selectedCategory) ? 'bg-orange-500 text-white shadow-md shadow-orange-500/20' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
            Semua Menu
        </button>

        @foreach($categories as $category)
            <button wire:click="selectCategory('{{ $category }}')"
                    class="shrink-0 px-4 py-2 text-xs font-bold rounded-full transition-all duration-200 {{ $selectedCategory === $category ? 'bg-orange-500 text-white shadow-md shadow-orange-500/20' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                {{ $category }}
            </button>
        @endforeach
    </div>

    <!-- Menus Grid (2 Columns Mobile-First) -->
    <div class="grid grid-cols-2 gap-4">
        @forelse($filteredMenus as $menu)
            <div wire:key="menu-item-{{ $menu['id'] }}" 
                 class="bg-white rounded-3xl border border-gray-100 p-3 shadow-xs hover:shadow-md transition-all duration-300 flex flex-col justify-between">
                
                <!-- Menu Image Area -->
                <div class="relative w-full aspect-square rounded-2xl overflow-hidden mb-3 bg-gray-50 group">
                    <img src="{{ $menu['image'] }}" 
                         alt="{{ $menu['name'] }}" 
                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                         onerror="this.onerror=null; this.src='https://placehold.co/400x300/FFF3E0/FF5722?text=Menu';"
                         loading="lazy" />
                    
                    <!-- Floating Spicy Indicator if Hot -->
                    @if(str_contains(strtolower($menu['name']), 'seblak'))
                        <span class="absolute top-2 right-2 bg-red-500 text-white text-[9px] font-black uppercase tracking-wider px-2 py-0.5 rounded-full shadow-xs">
                            🌶️ Hot
                        </span>
                    @endif
                </div>

                <!-- Menu Details -->
                <div class="flex-grow flex flex-col justify-between">
                    <div>
                        <h3 class="font-bold text-gray-800 text-sm leading-snug line-clamp-1 hover:text-orange-500 transition-colors">
                            {{ $menu['name'] }}
                        </h3>
                        <p class="text-[10px] text-gray-400 mt-1 line-clamp-2 leading-relaxed">
                            {{ $menu['description'] }}
                        </p>
                    </div>

                    <!-- Price and Action Button -->
                    <div class="flex items-center justify-between mt-3 pt-2 border-t border-gray-50">
                        <span class="text-sm font-extrabold text-orange-500 tracking-tight">
                            Rp {{ number_format($menu['price'], 0, ',', '.') }}
                        </span>
                        
                        <!-- Tombol Tambah: Memicu event untuk memuat data menu detail & menampilkan modal -->
                        <button wire:click="$dispatch('open-menu-detail', { menu: {{ json_encode($menu) }} })"
                                class="inline-flex items-center justify-center p-1.5 bg-orange-500 hover:bg-orange-600 text-white rounded-xl shadow-md shadow-orange-500/10 hover:shadow-orange-500/20 active:scale-95 transition-all duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                            </svg>
                        </button>
                    </div>
                </div>

            </div>
        @empty
            <div class="col-span-2 py-12 text-center">
                <span class="text-3xl">🍲</span>
                <p class="text-sm font-semibold text-gray-400 mt-3">Tidak ada menu dalam kategori ini.</p>
            </div>
        @endforelse
    </div>

    <!-- Render MenuDetail Livewire Component (Sub-component) -->
    <livewire:customer.menu-detail />
</div>
