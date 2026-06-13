<div class="px-4 py-6">
    <!-- Header/Greeting -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Mau makan apa hari ini?</h1>
        @if(session('table_id'))
            <p class="text-sm text-gray-500 mt-1">Meja {{ App\Domain\Table\Models\Table::find(session('table_id'))->table_number ?? '?' }}</p>
        @else
            <div class="mt-2 bg-yellow-50 p-2 rounded-md text-yellow-800 text-xs">
                Anda belum scan meja. Pesanan mungkin tidak dapat diproses.
            </div>
        @endif
    </div>

    <!-- Horizontal Category Filter -->
    <div class="flex space-x-3 overflow-x-auto pb-4 snap-x hide-scrollbar">
        <button wire:click="filterByCategory(null)" 
                class="snap-start shrink-0 px-4 py-2 rounded-full text-sm font-medium transition whitespace-nowrap 
                {{ is_null($selectedCategory) ? 'bg-amber-600 text-white shadow-md' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
            Semua
        </button>
        @foreach($categories as $category)
            <button wire:click="filterByCategory({{ $category->id }})" 
                    class="snap-start shrink-0 px-4 py-2 rounded-full text-sm font-medium transition whitespace-nowrap 
                    {{ $selectedCategory === $category->id ? 'bg-amber-600 text-white shadow-md' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
                {{ $category->name }}
            </button>
        @endforeach
    </div>

    <!-- Grid Menu -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mt-2">
        @forelse($menus as $menu)
            <div wire:key="menu-{{ $menu->id }}" class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden flex flex-col relative">
                <!-- Gambar -->
                <div class="aspect-square bg-gray-50 relative">
                    @if($menu->image)
                        <img src="{{ '/storage/' . $menu->image }}" alt="{{ $menu->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center text-gray-400">
                            <svg class="h-10 w-10 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="text-[10px] font-medium text-gray-400">No Image</span>
                        </div>
                    @endif
                </div>
                
                <!-- Info -->
                <div class="p-3 flex-1 flex flex-col justify-between">
                    <div>
                        <h3 class="font-semibold text-sm text-gray-900 leading-tight">{{ $menu->name }}</h3>
                        <p class="text-xs text-gray-500 mt-1 line-clamp-1">{{ $menu->description }}</p>
                    </div>
                    <div class="mt-3 flex items-end justify-between">
                        <span class="text-amber-600 font-bold text-sm">{{ formatRupiah($menu->price) }}</span>
                        <!-- Tombol Add -->
                        <button wire:click="openAddModal({{ $menu->id }})" class="h-8 w-8 bg-amber-600 hover:bg-amber-700 text-white rounded-full flex items-center justify-center shadow-sm transition transform active:scale-95">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-10 text-center">
                <p class="text-gray-500 text-sm">Belum ada menu di kategori ini.</p>
            </div>
        @endforelse
    </div>

    <!-- Alpine + Livewire Modal Add to Cart (Bottom Sheet) -->
    @if($selectedMenu)
    <div x-data="{}" x-show="$wire.showModal" class="relative z-40" style="display: none;">
        <!-- Overlay -->
        <div x-show="$wire.showModal" x-transition.opacity class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="$wire.closeModal()"></div>

        <!-- Panel -->
        <div x-show="$wire.showModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full" 
             class="fixed bottom-0 left-0 right-0 bg-white rounded-t-2xl shadow-xl p-5 pt-6 max-h-[85vh] overflow-y-auto z-50 flex flex-col">
            
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">{{ $selectedMenu->name }}</h2>
                    <p class="text-amber-600 font-bold mt-1">{{ formatRupiah($selectedMenu->price) }}</p>
                </div>
                <button @click="$wire.closeModal()" class="text-gray-400 hover:text-gray-600 p-1 bg-gray-100 rounded-full">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <!-- Toppings -->
            @if($selectedMenu->toppings->count() > 0)
            <div class="mb-5">
                <h3 class="text-sm font-bold text-gray-900 mb-2">Tambahan Topping</h3>
                <div class="space-y-2">
                    @foreach($selectedMenu->toppings as $topping)
                    <label class="flex items-center justify-between p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 active:bg-gray-100 transition">
                        <div class="flex items-center">
                            <input type="checkbox" wire:model="selectedToppings" value="{{ $topping->id }}" class="h-4 w-4 text-amber-600 focus:ring-amber-500 border-gray-300 rounded">
                            <span class="ml-3 text-sm text-gray-700">{{ $topping->name }}</span>
                        </div>
                        <span class="text-sm text-gray-500">+ {{ formatRupiah($topping->price) }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Spiciness -->
            @if($selectedMenu->spicinessLevels->count() > 0)
            <div class="mb-6">
                <h3 class="text-sm font-bold text-gray-900 mb-2">Tingkat Pedas</h3>
                <div class="grid grid-cols-3 gap-2">
                    @foreach($selectedMenu->spicinessLevels as $spiciness)
                    <label class="relative border border-gray-200 rounded-lg p-3 flex items-center justify-center cursor-pointer hover:bg-gray-50 transition">
                        <input type="radio" wire:model="selectedSpiciness" value="{{ $spiciness->id }}" class="sr-only">
                        <span class="text-sm font-medium {{ $selectedSpiciness == $spiciness->id ? 'text-amber-600' : 'text-gray-700' }}">
                            {{ $spiciness->name }}
                        </span>
                        <!-- Custom border for checked state using Alpine or plain blade conditional -->
                        <div class="absolute inset-0 border-2 rounded-lg pointer-events-none {{ $selectedSpiciness == $spiciness->id ? 'border-amber-500' : 'border-transparent' }}"></div>
                    </label>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Quantity & Add to Cart -->
            <div class="mt-auto border-t border-gray-100 pt-4 pb-2">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-sm font-medium text-gray-700">Jumlah</span>
                    <div class="flex items-center space-x-3 bg-gray-100 rounded-lg p-1">
                        <button type="button" wire:click="$set('quantity', {{ max(1, $quantity - 1) }})" class="w-8 h-8 flex items-center justify-center bg-white rounded shadow-sm text-gray-600 hover:text-amber-600 focus:outline-none">-</button>
                        <span class="text-sm font-bold w-6 text-center">{{ $quantity }}</span>
                        <button type="button" wire:click="$set('quantity', {{ $quantity + 1 }})" class="w-8 h-8 flex items-center justify-center bg-white rounded shadow-sm text-gray-600 hover:text-amber-600 focus:outline-none">+</button>
                    </div>
                </div>
                
                <button wire:click="addToCart" class="w-full bg-amber-600 hover:bg-amber-700 text-white font-bold py-3.5 px-4 rounded-xl shadow-md transition active:scale-[0.98] flex justify-center items-center">
                    <span wire:loading.remove wire:target="addToCart">Tambah ke Pesanan</span>
                    <span wire:loading wire:target="addToCart">Memproses...</span>
                </button>
            </div>
            
        </div>
    </div>
    @endif

</div>
<style>
/* Utilities statis untuk hide-scrollbar (digunakan tanpa tag style di body) */
.hide-scrollbar::-webkit-scrollbar { display: none; }
.hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
