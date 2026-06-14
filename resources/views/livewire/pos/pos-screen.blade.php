<div class="h-full flex flex-col bg-gray-50">
    
    <!-- Top Mobile Tab Bar (Only visible on small screens) -->
    <div class="md:hidden flex shrink-0 border-b border-gray-200 bg-white">
        <button 
            wire:click="$set('activeTab', 'menu')" 
            class="flex-1 py-3 text-center text-sm font-bold border-b-2 transition {{ $activeTab === 'menu' ? 'border-amber-600 text-amber-600 bg-amber-50/20' : 'border-transparent text-gray-500 hover:text-gray-700' }}"
        >
            🍔 Daftar Menu
        </button>
        <button 
            wire:click="$set('activeTab', 'cart')" 
            class="flex-1 py-3 text-center text-sm font-bold border-b-2 transition relative {{ $activeTab === 'cart' ? 'border-amber-600 text-amber-600 bg-amber-50/20' : 'border-transparent text-gray-500 hover:text-gray-700' }}"
        >
            🛒 Keranjang
            @if(count($cart) > 0)
                <span class="absolute top-2.5 right-6 bg-red-500 text-white rounded-full text-[10px] w-5 h-5 flex items-center justify-center font-bold">
                    {{ collect($cart)->sum('quantity') }}
                </span>
            @endif
        </button>
    </div>

    <!-- Main Workspace Grid -->
    <div class="flex-1 grid grid-cols-1 md:grid-cols-10 overflow-hidden">
        
        <!-- Left Panel: Menu Items (70% Grid on desktop, hidden on mobile unless menu tab is active) -->
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
                        @if ($this->lastOrderId)
                            <a href="{{ route('pos.receipt', $this->lastOrderId) }}" target="_blank" class="ml-4 inline-flex items-center px-3 py-1.5 bg-emerald-600 text-white text-xs font-medium rounded-lg hover:bg-emerald-700 transition shrink-0">
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
                                class="group bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition-all duration-200 cursor-pointer flex flex-col relative active:scale-95 transform"
                            >
                                <!-- Thumbnail Thumbnail -->
                                <div class="aspect-square bg-gray-50 relative shrink-0 overflow-hidden">
                                    @if($menu->image)
                                        <img 
                                            src="{{ $menu->image ? '/storage/' . $menu->image : '' }}" 
                                            alt="{{ $menu->name }}" 
                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                        >
                                    @else
                                        <!-- Placeholder SVG -->
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
        </div>

        <!-- Right Panel: POS Order Cart & Checkout (30% on desktop, full-width on mobile unless cart tab active) -->
        <div class="md:col-span-3 flex flex-col min-w-0 bg-white border-l border-gray-200 overflow-hidden {{ $activeTab === 'cart' ? 'flex' : 'hidden md:flex' }}">
            
            <!-- Header Option: Dine-in vs Takeaway Toggle -->
            <div class="p-4 border-b border-gray-100 shrink-0">
                <div class="flex bg-gray-100 rounded-xl p-1">
                    <button 
                        wire:click="$set('orderType', 'dine_in')" 
                        class="flex-1 py-2 text-center text-xs font-bold rounded-lg transition-all {{ $orderType === 'dine_in' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}"
                    >
                        🍽️ Dine-in (Makan Sini)
                    </button>
                    <button 
                        wire:click="$set('orderType', 'takeaway')" 
                        class="flex-1 py-2 text-center text-xs font-bold rounded-lg transition-all {{ $orderType === 'takeaway' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}"
                    >
                        🛍️ Takeaway (Bungkus)
                    </button>
                </div>

                <!-- Active Meja Table Dropdown (Only visible if Dine-in) -->
                @if($orderType === 'dine_in')
                    <div class="mt-3">
                        <label class="block text-[10px] uppercase font-black tracking-wider text-gray-400 mb-1">Pilih Nomor Meja *</label>
                        <select 
                            wire:model="tableId" 
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 text-sm font-semibold text-gray-700 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500"
                        >
                            <option value="">-- Pilih Meja --</option>
                            @foreach($tables as $table)
                                <option value="{{ $table->id }}">Meja #{{ $table->table_number }} ({{ $table->status }})</option>
                            @endforeach
                        </select>
                        @error('tableId')
                            <span class="text-rose-600 text-[10px] font-bold mt-1 block">⚠️ {{ $message }}</span>
                        @enderror
                    </div>
                @endif
            </div>

            <!-- Cart Items List: Scrollable -->
            <div class="flex-1 overflow-y-auto p-4 space-y-3.5 bg-gray-50/50">
                @if(empty($cart))
                    <div class="h-full flex flex-col items-center justify-center text-center p-6 text-gray-400">
                        <span class="text-3xl mb-2">🛒</span>
                        <p class="text-xs font-bold text-gray-500">Keranjang Kosong</p>
                        <p class="text-[10px] text-gray-400 mt-0.5">Pilih menu di panel kiri untuk memulai transaksi.</p>
                    </div>
                @else
                    @foreach($cart as $key => $item)
                        <!-- Cart Item Card -->
                        <div class="bg-white border border-gray-200 rounded-xl p-3.5 shadow-xs relative flex flex-col gap-2">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="font-bold text-xs text-gray-800 leading-snug">{{ $item['name'] }}</h4>
                                    
                                    <!-- Spiciness & Topping tags details -->
                                    @if($item['spiciness_level'])
                                        <span class="text-[10px] font-medium text-rose-500 mt-1 block">🌶️ Pedas: {{ $item['spiciness_level']['name'] }}</span>
                                    @endif

                                    @if(!empty($item['toppings']))
                                        <div class="mt-1 flex flex-wrap gap-1 text-[9px] text-gray-500">
                                            @foreach($item['toppings'] as $top)
                                                <span class="bg-gray-100 text-gray-600 px-1.5 py-0.5 rounded">
                                                    + {{ $top['name'] }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Delete Item -->
                                <button 
                                    wire:click="removeFromCart('{{ $key }}')" 
                                    class="text-gray-300 hover:text-rose-500 transition duration-150 p-0.5 shrink-0"
                                    title="Hapus"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>

                            <!-- Cart Math and Increments -->
                            <div class="flex items-center justify-between border-t border-gray-100 pt-2 shrink-0">
                                <!-- Price text -->
                                <span class="text-xs font-black text-amber-600">
                                    {{ formatRupiah($item['subtotal']) }}
                                </span>

                                <!-- Inc/Dec Controls -->
                                <div class="flex items-center space-x-2 bg-gray-100 rounded-lg p-0.5">
                                    <button 
                                        wire:click="decrementQuantity('{{ $key }}')" 
                                        class="w-6 h-6 flex items-center justify-center bg-white rounded shadow-xs text-gray-600 hover:text-amber-600 font-black text-xs"
                                    >
                                        -
                                    </button>
                                    <span class="text-[11px] font-bold w-5 text-center text-gray-700">{{ $item['quantity'] }}</span>
                                    <button 
                                        wire:click="incrementQuantity('{{ $key }}')" 
                                        class="w-6 h-6 flex items-center justify-center bg-white rounded shadow-xs text-gray-600 hover:text-amber-600 font-black text-xs"
                                    >
                                        +
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <!-- Checkout inputs & Total calculations: Sticky Footer -->
            <div class="border-t border-gray-100 p-4 shrink-0 shadow-lg bg-white space-y-3.5">
                <!-- Customer Details Inputs -->
                <div class="grid grid-cols-2 gap-2 text-xs">
                    <div>
                        <label class="block text-[9px] uppercase font-bold text-gray-400 mb-0.5">Nama Cust (Opsional)</label>
                        <input 
                            type="text" 
                            wire:model="customerName" 
                            placeholder="Nama..." 
                            class="w-full bg-gray-50 border border-gray-200 rounded-lg px-2.5 py-1.5 focus:outline-none focus:border-amber-500"
                        >
                    </div>
                    <div>
                        <label class="block text-[9px] uppercase font-bold text-gray-400 mb-0.5">No. Telp (Opsional)</label>
                        <input 
                            type="text" 
                            wire:model="customerPhone" 
                            placeholder="08..." 
                            class="w-full bg-gray-50 border border-gray-200 rounded-lg px-2.5 py-1.5 focus:outline-none focus:border-amber-500"
                        >
                    </div>
                </div>

                <div>
                    <label class="block text-[9px] uppercase font-bold text-gray-400 mb-0.5">Catatan Dapur (Opsional)</label>
                    <input 
                        type="text" 
                        wire:model="notes" 
                        placeholder="Kurang pedas, tanpa seledri, dll..." 
                        class="w-full bg-gray-50 border border-gray-200 rounded-lg px-2.5 py-1.5 text-xs focus:outline-none focus:border-amber-500"
                    >
                </div>

                <!-- Math calculations summary -->
                <div class="space-y-1.5 border-t border-gray-100 pt-3">
                    <div class="flex justify-between text-xs text-gray-500 font-semibold">
                        <span>Jumlah Item</span>
                        <span>{{ collect($cart)->sum('quantity') }}x</span>
                    </div>
                    @if ($this->getTax() > 0)
                        <div class="flex justify-between text-xs text-gray-500 font-semibold">
                            <span>Subtotal</span>
                            <span>{{ formatRupiah($this->getCartTotal()) }}</span>
                        </div>
                        <div class="flex justify-between text-xs text-gray-500 font-semibold">
                            <span>Pajak</span>
                            <span>{{ formatRupiah($this->getTax()) }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between text-base font-black text-gray-800">
                        <span>Total Tagihan</span>
                        <span class="text-amber-600">{{ formatRupiah($this->getGrandTotal()) }}</span>
                    </div>
                </div>

                <!-- Bayar CTA Button -->
                <button 
                    wire:click="openPaymentModal" 
                    class="w-full bg-emerald-600 hover:bg-emerald-500 text-white py-3 px-4 rounded-xl font-bold text-sm tracking-wide shadow-md shadow-emerald-600/10 hover:scale-[1.01] active:scale-95 transform transition duration-150 flex items-center justify-center space-x-2"
                >
                    <!-- Dollar Icon -->
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span>Bayar & Proses (F10)</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Modal 1: Product Additions & Toppings Customizer -->
    @if($showModal && $selectedMenu)
        <div class="relative z-50 flex items-center justify-center">
            <!-- Overlay Backdrop -->
            <div 
                wire:click="closeModal" 
                class="fixed inset-0 bg-black/60 backdrop-blur-xs transition-opacity"
            ></div>

            <!-- Modal Panel Window -->
            <div class="fixed inset-0 overflow-y-auto flex items-center justify-center p-4">
                <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden relative flex flex-col max-h-[90vh]">
                    
                    <!-- Header -->
                    <div class="p-5 border-b border-gray-150 bg-gray-50 flex items-center justify-between shrink-0">
                        <div>
                            <h2 class="text-base font-extrabold text-gray-800 leading-snug">{{ $selectedMenu->name }}</h2>
                            <p class="text-xs text-amber-600 font-bold mt-0.5">Base Price: {{ formatRupiah($selectedMenu->price) }}</p>
                        </div>
                        <button 
                            wire:click="closeModal" 
                            class="text-gray-400 hover:text-gray-600 p-1.5 hover:bg-gray-150 rounded-full shrink-0 transition"
                        >
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
                                                <input 
                                                    type="checkbox" 
                                                    wire:model="selectedToppings" 
                                                    value="{{ $topping->id }}" 
                                                    class="h-4 w-4 text-amber-600 focus:ring-amber-500/20 border-gray-300 rounded"
                                                >
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
                                            <input 
                                                type="radio" 
                                                wire:model="selectedSpiciness" 
                                                value="{{ $spiciness->id }}" 
                                                class="sr-only"
                                            >
                                            <span class="text-xs font-extrabold {{ $selectedSpiciness == $spiciness->id ? 'text-amber-600' : 'text-gray-700' }}">
                                                {{ $spiciness->name }}
                                            </span>
                                            
                                            <!-- Border selection glow visual -->
                                            <div class="absolute inset-0 border-2 rounded-xl pointer-events-none {{ $selectedSpiciness == $spiciness->id ? 'border-amber-500 bg-amber-50/10' : 'border-transparent' }}"></div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Sticky Footer (Quantity Selector and Submit) -->
                    <div class="p-5 border-t border-gray-150 bg-gray-50 shrink-0 space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-gray-700">Jumlah Porsi</span>
                            <div class="flex items-center space-x-3.5 bg-white border border-gray-200 rounded-xl p-1 shrink-0 shadow-xs">
                                <button 
                                    type="button" 
                                    wire:click="$set('quantity', {{ max(1, $quantity - 1) }})" 
                                    class="w-8 h-8 flex items-center justify-center bg-gray-50 hover:bg-gray-100 rounded-lg text-gray-600 font-black text-sm"
                                >
                                    -
                                </button>
                                <span class="text-xs font-black w-6 text-center text-gray-800">{{ $quantity }}</span>
                                <button 
                                    type="button" 
                                    wire:click="$set('quantity', {{ $quantity + 1 }})" 
                                    class="w-8 h-8 flex items-center justify-center bg-gray-50 hover:bg-gray-100 rounded-lg text-gray-600 font-black text-sm"
                                >
                                    +
                                </button>
                            </div>
                        </div>

                        <!-- Add Button -->
                        <button 
                            wire:click="addToCart" 
                            class="w-full bg-amber-600 hover:bg-amber-500 text-white font-extrabold py-3.5 px-4 rounded-xl shadow-md shadow-amber-600/10 active:scale-95 transform transition flex justify-center items-center text-xs tracking-wider uppercase"
                        >
                            <span wire:loading.remove wire:target="addToCart">Tambah ke Keranjang</span>
                            <span wire:loading wire:target="addToCart">Memproses...</span>
                        </button>
                    </div>

                </div>
            </div>
        </div>
    @endif

    <!-- Modal 2: Cashier Checkout Payment Screen -->
    @if($showPaymentModal)
        <div class="relative z-50 flex items-center justify-center">
            <!-- Overlay Backdrop -->
            <div 
                wire:click="closePaymentModal" 
                class="fixed inset-0 bg-black/60 backdrop-blur-xs transition-opacity"
            ></div>

            <!-- Modal Panel Window -->
            <div class="fixed inset-0 overflow-y-auto flex items-center justify-center p-4">
                <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden relative flex flex-col">
                    
                    <!-- Header -->
                    <div class="p-5 border-b border-gray-150 bg-gray-50 flex items-center justify-between shrink-0">
                        <div class="flex items-center space-x-2">
                            <span class="text-lg">💰</span>
                            <h2 class="text-base font-extrabold text-gray-800 leading-snug">Metode Pembayaran & Proses</h2>
                        </div>
                        <button 
                            wire:click="closePaymentModal" 
                            class="text-gray-400 hover:text-gray-600 p-1.5 hover:bg-gray-150 rounded-full shrink-0 transition"
                        >
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>

                    <!-- Modal Body content -->
                    <div class="p-6 space-y-6 overflow-y-auto">
                        <!-- Order bill summary -->
                        <div class="bg-amber-50/40 border border-amber-500/15 rounded-xl p-4 space-y-2">
                            <div class="flex justify-between items-center">
                                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Pembayaran</span>
                                <span class="text-xl font-black text-amber-600">
                                    {{ formatRupiah($this->getGrandTotal()) }}
                                </span>
                            </div>
                            @if ($this->getTax() > 0)
                                <div class="flex justify-between text-xs text-gray-500">
                                    <span>Termasuk Pajak</span>
                                    <span>{{ formatRupiah($this->getTax()) }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Method Radio Checklist selection -->
                        <div>
                            <h3 class="text-xs uppercase font-black tracking-wider text-gray-400 mb-2.5">Metode Pembayaran</h3>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="relative border border-gray-200 rounded-xl p-4 flex flex-col items-center justify-center cursor-pointer hover:bg-gray-50 hover:border-gray-300 transition active:scale-[0.98]">
                                    <input 
                                        type="radio" 
                                        wire:model="paymentMethod" 
                                        value="tunai" 
                                        class="sr-only"
                                    >
                                    <span class="text-xl mb-1">💵</span>
                                    <span class="text-xs font-bold {{ $paymentMethod === 'tunai' ? 'text-emerald-600' : 'text-gray-700' }}">Tunai / Cash</span>
                                    <div class="absolute inset-0 border-2 rounded-xl pointer-events-none {{ $paymentMethod === 'tunai' ? 'border-emerald-500 bg-emerald-50/10' : 'border-transparent' }}"></div>
                                </label>

                                <label class="relative border border-gray-200 rounded-xl p-4 flex flex-col items-center justify-center cursor-pointer hover:bg-gray-50 hover:border-gray-300 transition active:scale-[0.98]">
                                    <input 
                                        type="radio" 
                                        wire:model="paymentMethod" 
                                        value="non_tunai" 
                                        class="sr-only"
                                    >
                                    <span class="text-xl mb-1">💳</span>
                                    <span class="text-xs font-bold {{ $paymentMethod === 'non_tunai' ? 'text-indigo-600' : 'text-gray-700' }}">Debit/QRIS (Non-Tunai)</span>
                                    <div class="absolute inset-0 border-2 rounded-xl pointer-events-none {{ $paymentMethod === 'non_tunai' ? 'border-indigo-500 bg-indigo-50/10' : 'border-transparent' }}"></div>
                                </label>
                            </div>
                        </div>

                        <!-- Cash received input: Only shown if Tunai selected -->
                        @if($paymentMethod === 'tunai')
                            <div class="space-y-4 border-t border-gray-100 pt-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-600 mb-1.5">Uang Tunai Diterima (Rupiah)</label>
                                    <div class="relative rounded-xl shadow-xs">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-xs font-extrabold text-gray-400">Rp</span>
                                        </div>
                                        <input 
                                            type="number" 
                                            wire:model.live="cashAmount" 
                                            class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-9 pr-4 py-3 text-base font-black text-gray-800 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                                            placeholder="Masukkan jumlah uang..."
                                        >
                                    </div>
                                    @error('cashAmount')
                                        <span class="text-rose-600 text-[10px] font-bold mt-1.5 block">⚠️ {{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Change calculation math -->
                                <div class="bg-gray-50 rounded-xl p-4 border border-gray-150 flex justify-between items-center">
                                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Kembalian</span>
                                    <span class="text-lg font-black text-emerald-600">
                                        {{ formatRupiah($this->calculateChange()) }}
                                    </span>
                                </div>
                            </div>
                        @else
                            <div class="bg-blue-50 border border-blue-200 text-blue-800 rounded-xl p-4 text-xs font-medium space-y-1">
                                <p class="font-bold">💡 Informasi Non-Tunai</p>
                                <p class="text-gray-600 leading-normal">Status pembayaran pesanan ini akan diset sebagai <strong class="text-blue-900">Payment Pending</strong>. Silakan sediakan terminal EDC EDC atau tunjukkan kode QRIS Outlet ke pelanggan terlebih dahulu.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Modal Actions Footer -->
                    <div class="p-5 border-t border-gray-150 bg-gray-50 shrink-0">
                        <button 
                            wire:click="placeOrder" 
                            class="w-full bg-emerald-600 hover:bg-emerald-500 text-white font-extrabold py-3.5 px-4 rounded-xl shadow-md shadow-emerald-600/10 active:scale-95 transform transition flex justify-center items-center text-xs tracking-wider uppercase"
                        >
                            <span wire:loading.remove wire:target="placeOrder">Konfirmasi & Simpan Pesanan</span>
                            <span wire:loading wire:target="placeOrder">Menyimpan...</span>
                        </button>
                    </div>

                </div>
            </div>
        </div>
    @endif

</div>
