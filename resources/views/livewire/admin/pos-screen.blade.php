<div class="h-[calc(100vh-8rem)] flex flex-col md:flex-row gap-6 -m-4 sm:-m-6 lg:-m-8 p-4 sm:p-6 lg:p-8 overflow-hidden relative" 
     x-data="{ showNotification: false, notificationMessage: '', mobileCartOpen: false }" 
     @notify.window="showNotification = true; notificationMessage = $event.detail[0].message; setTimeout(() => showNotification = false, 3000)">
     
    <!-- Notifikasi Sukses -->
    <div x-show="showNotification" x-transition 
         class="fixed top-20 right-8 z-50 bg-green-500 text-white px-6 py-3 rounded-xl shadow-lg font-semibold flex items-center gap-2" style="display: none;">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        <span x-text="notificationMessage"></span>
    </div>

    <!-- Floating Cart Button (Mobile Only) -->
    <button @click="mobileCartOpen = !mobileCartOpen" class="md:hidden fixed bottom-6 right-6 z-40 bg-orange-600 text-white p-4 rounded-full shadow-xl flex items-center justify-center min-h-[56px] min-w-[56px]">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
        @if(count($cart) > 0)
        <span class="absolute top-0 right-0 -mt-1 -mr-1 flex items-center justify-center w-6 h-6 bg-red-500 text-white text-[10px] font-bold rounded-full border-2 border-white">{{ count($cart) }}</span>
        @endif
    </button>

    <!-- Left: Menu Area -->
    <div class="flex-1 flex flex-col min-w-0 bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden h-full">
        <!-- Header & Search -->
        <div class="p-4 border-b border-gray-100 flex items-center justify-between gap-4 shrink-0 bg-gray-50">
            <h2 class="text-xl font-extrabold text-gray-900 hidden sm:block">Menu POS</h2>
            <div class="relative flex-1 max-w-md">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input wire:model.live.debounce.300ms="seaVerifikasi dan perbaiki detail-detail berikut pada halaman POS Kasir (PosScreen) untuk Warung Seblak Digital. Pastikan semua aspek sudah terpenuhi. Jika belum, lakukan perbaikan.

**Komponen yang harus diperiksa:**
- `app/Livewire/Admin/PosScreen.php`
- `resources/views/livewire/admin/pos-screen.blade.php`

**Daftar verifikasi:**

1. **Konfirmasi Hapus Item di Keranjang**  
   - Tombol hapus pada setiap item di keranjang harus memiliki konfirmasi `wire:confirm="Yakin ingin menghapus item ini?"` agar tidak terhapus tanpa sengaja.

2. **Validasi Uang Diterima**  
   - Di modal checkout, pastikan ada pesan error yang jelas jika uang diterima kurang dari total belanja. Bisa menggunakan validasi Livewire dengan `@error('amount_received')` atau setidaknya tombol "Konfirmasi & Cetak Struk" tidak bisa diklik jika uang kurang.

3. **Responsivitas Mobile**  
   - Pada layar kecil (mobile), pastikan keranjang tidak selalu mengambil setengah layar. Idealnya, keranjang berubah menjadi bottom sheet yang bisa di-toggle dengan tombol "Lihat Keranjang" atau ikon keranjang melayang. Jika belum, tambahkan tombol toggle yang muncul hanya di mobile (`lg:hidden`).

4. **Tap Target 44px**  
   - Periksa semua tombol interaktif (tambah/menu item, +/- qty, hapus item, checkout, pilih tipe pesanan, konfirmasi) memiliki ukuran minimal `min-h-[44px] min-w-[44px]`. Tambahkan kelas tersebut jika ada yang kurang.

5. **Filter Kategori (Opsional)**  
   - Jika belum ada, tambahkan baris filter kategori di atas grid menu (horizontal scroll) untuk mempercepat pencarian menu oleh kasir. Ambil daftar kategori dari data menu yang ada di session. Jika tidak memungkinkan dalam satu prompt, minimal tambahkan komentar TODO.

6. **Tombol "Cetak Struk"**  
   - Pastikan tombol "Konfirmasi & Cetak Struk" memiliki komentar TODO bahwa fungsi cetak akan diintegrasikan nanti (misal: `{{-- TODO: Integrasikan dengan printer thermal atau generate PDF struk --}}`).

7. **Data Dummy yang Konsisten**  
   - Jika session `admin.menus` kosong, data dummy yang di-generate harus sama persis dengan yang digunakan di Admin MenuList (nama, harga, kategori yang sama). Verifikasi di method `mount()` atau method `getMenus()`.

**Tambahan:**
- Setelah perbaikan, pastikan tidak ada error sintaks.
- Jangan mengubah fungsionalitas inti yang sudah berjalan.
- Berikan ringkasan perubahan yang dilakukan.rch" type="text" placeholder="Cari menu..." class="block w-full pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm focus:ring-orange-500 focus:border-orange-500 min-h-[44px]">
            </div>
        </div>

        <!-- Filter Kategori (Horizontal Scroll) -->
        <div class="px-4 py-3 border-b border-gray-100 bg-white shrink-0 flex items-center space-x-2 overflow-x-auto hide-scrollbar">
            <button wire:click="selectCategory(null)" class="whitespace-nowrap px-4 py-2 rounded-xl text-sm font-semibold transition-colors min-h-[44px] {{ is_null($selectedCategory) ? 'bg-orange-600 text-white shadow-sm' : 'bg-gray-50 text-gray-600 hover:bg-gray-100' }}">
                Semua
            </button>
            @foreach($categories as $category)
            <button wire:click="selectCategory('{{ $category }}')" class="whitespace-nowrap px-4 py-2 rounded-xl text-sm font-semibold transition-colors min-h-[44px] {{ $selectedCategory === $category ? 'bg-orange-600 text-white shadow-sm' : 'bg-gray-50 text-gray-600 hover:bg-gray-100' }}">
                {{ $category }}
            </button>
            @endforeach
        </div>

        <!-- Menu Grid -->
        <div class="flex-1 overflow-y-auto p-4 bg-gray-50/50">
            <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4">
                @foreach($this->filteredMenus as $menu)
                <div wire:click="openItemModal({{ $menu['id'] }})" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden cursor-pointer hover:border-orange-500 hover:shadow-md transition-all group flex flex-col h-full min-h-[44px]">
                    <div class="aspect-[4/3] bg-gray-100 flex items-center justify-center text-4xl group-hover:scale-105 transition-transform duration-300">
                        🍲
                    </div>
                    <div class="p-3 flex flex-col flex-1">
                        <h3 class="text-sm font-bold text-gray-900 line-clamp-2">{{ $menu['name'] }}</h3>
                        <p class="text-orange-600 font-extrabold text-sm mt-auto pt-2">Rp {{ number_format($menu['price'], 0, ',', '.') }}</p>
                    </div>
                </div>
                @endforeach
                @if(count($this->filteredMenus) === 0)
                <div class="col-span-full py-12 text-center text-gray-500">
                    Menu tidak ditemukan.
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Right: Keranjang Area -->
    <div :class="mobileCartOpen ? 'translate-x-0' : 'translate-x-full md:translate-x-0'" 
         class="fixed md:static top-0 right-0 z-40 w-80 lg:w-96 h-full flex flex-col bg-white md:rounded-2xl shadow-2xl md:shadow-sm border-l md:border border-gray-200 shrink-0 transition-transform duration-300 ease-in-out md:h-full">
        
        <!-- Cart Header -->
        <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50 md:rounded-t-2xl shrink-0">
            <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                Pesanan Baru
            </h2>
            <div class="flex items-center gap-3">
                <span class="bg-orange-100 text-orange-600 px-2.5 py-0.5 rounded-full text-xs font-bold">{{ count($cart) }} Item</span>
                <button @click="mobileCartOpen = false" class="md:hidden text-gray-500 hover:text-gray-700 min-h-[44px] min-w-[44px] flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        </div>

        <!-- Cart Items -->
        <div class="flex-1 overflow-y-auto p-4 space-y-3 bg-white">
            @forelse($cart as $item)
            <div class="flex gap-3 border-b border-gray-50 pb-3 last:border-0">
                <div class="flex-1">
                    <h4 class="text-sm font-bold text-gray-900">{{ $item['name'] }}</h4>
                    <div class="text-[10px] text-gray-500 mt-0.5 space-y-0.5">
                        @if($item['spiciness'])
                            <p class="text-red-500 font-medium">🌶️ {{ $item['spiciness'] }}</p>
                        @endif
                        @if(!empty($item['toppings']))
                            <p>+ {{ implode(', ', $item['toppings']) }}</p>
                        @endif
                        <p>Rp {{ number_format($item['unit_price'], 0, ',', '.') }} / item</p>
                    </div>
                </div>
                <div class="flex flex-col items-end justify-between w-24 shrink-0">
                    <button wire:click="removeFromCart('{{ $item['id'] }}')" wire:confirm="Yakin ingin menghapus item ini?" class="text-red-400 hover:text-red-600 hover:bg-red-50 p-2 rounded-lg flex items-center justify-center min-h-[44px] min-w-[44px]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                    <div class="flex items-center gap-1 bg-gray-100 rounded-lg p-1 mt-1">
                        <button wire:click="updateCartQty('{{ $item['id'] }}', -1)" class="w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-white rounded shadow-sm focus:outline-none min-h-[32px] min-w-[32px] font-bold">-</button>
                        <span class="text-xs font-bold text-gray-900 w-4 text-center">{{ $item['qty'] }}</span>
                        <button wire:click="updateCartQty('{{ $item['id'] }}', 1)" class="w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-white rounded shadow-sm focus:outline-none min-h-[32px] min-w-[32px] font-bold">+</button>
                    </div>
                </div>
            </div>
            @empty
            <div class="h-full flex flex-col items-center justify-center text-gray-400 space-y-3 opacity-50">
                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                <p class="text-sm font-medium">Keranjang Kosong</p>
            </div>
            @endforelse
        </div>

        <!-- Cart Footer -->
        <div class="p-4 border-t border-gray-100 bg-gray-50 md:rounded-b-2xl shrink-0">
            <div class="flex justify-between items-center mb-4">
                <span class="text-sm font-semibold text-gray-600">Total Harga</span>
                <span class="text-xl font-extrabold text-orange-600">Rp {{ number_format($this->cartTotal, 0, ',', '.') }}</span>
            </div>
            <button wire:click="openCheckoutModal" @if(count($cart) === 0) disabled @endif class="w-full py-3.5 bg-orange-600 hover:bg-orange-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-bold rounded-xl shadow-sm transition-colors min-h-[44px]">
                Lanjut Pembayaran
            </button>
        </div>
    </div>

    <!-- Modal Item Detail (Quick Add) -->
    <div x-data="{ show: @entangle('showItemModal') }" 
         x-show="show" 
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;"
         x-transition.opacity>
         
        <div class="flex items-end sm:items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-900/50 backdrop-blur-sm" @click="show = false"></div>

            <div class="relative inline-block w-full max-w-md p-6 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl z-10 sm:my-8"
                 x-show="show"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                
                @if($selectedMenu)
                <div class="flex justify-between items-center mb-4 pb-3 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900">{{ $selectedMenu['name'] }}</h3>
                    <button @click="show = false" type="button" class="text-gray-400 hover:text-gray-500 hover:bg-gray-100 p-2 rounded-xl transition-colors min-h-[44px] min-w-[44px] flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="space-y-4 max-h-[60vh] overflow-y-auto pr-2">
                    <!-- Spiciness -->
                    @if(str_contains(strtolower($selectedMenu['name']), 'seblak'))
                    <div>
                        <h4 class="text-sm font-bold text-gray-900 mb-2">Level Pedas</h4>
                        <div class="space-y-2">
                            @foreach($spicinessLevels as $level)
                            <label class="flex items-center p-3 border border-gray-200 rounded-xl cursor-pointer hover:bg-orange-50 hover:border-orange-200 transition-colors">
                                <input type="radio" wire:model="selectedSpiciness" value="{{ $level['id'] }}" class="w-4 h-4 text-orange-600 focus:ring-orange-500 border-gray-300 min-h-[24px] min-w-[24px]">
                                <span class="ml-3 text-sm font-medium text-gray-700">{{ $level['name'] }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Toppings -->
                    <div>
                        <h4 class="text-sm font-bold text-gray-900 mb-2">Topping Tambahan</h4>
                        <div class="space-y-2">
                            @foreach($toppings as $topping)
                            <label class="flex items-center justify-between p-3 border border-gray-200 rounded-xl cursor-pointer hover:bg-orange-50 hover:border-orange-200 transition-colors">
                                <div class="flex items-center">
                                    <input type="checkbox" wire:model="selectedToppings" value="{{ $topping['id'] }}" class="w-4 h-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded min-h-[24px] min-w-[24px]">
                                    <span class="ml-3 text-sm font-medium text-gray-700">{{ $topping['name'] }}</span>
                                </div>
                                <span class="text-xs font-bold text-orange-600">+Rp {{ number_format($topping['price'], 0, ',', '.') }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between gap-4">
                    <div class="flex items-center bg-gray-100 rounded-xl p-1">
                        <button wire:click="decrementQty" class="w-10 h-10 flex items-center justify-center bg-white rounded-lg shadow-sm text-gray-700 font-bold hover:bg-gray-50 focus:outline-none min-h-[44px] min-w-[44px]">-</button>
                        <span class="w-12 text-center font-bold text-gray-900">{{ $qty }}</span>
                        <button wire:click="incrementQty" class="w-10 h-10 flex items-center justify-center bg-white rounded-lg shadow-sm text-gray-700 font-bold hover:bg-gray-50 focus:outline-none min-h-[44px] min-w-[44px]">+</button>
                    </div>
                    <button wire:click="addToCart" class="flex-1 py-3 px-4 bg-orange-600 hover:bg-orange-700 text-white font-bold rounded-xl shadow-sm transition-colors min-h-[44px]">
                        Tambah ke Keranjang
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Checkout -->
    <div x-data="{ show: @entangle('showCheckoutModal') }" 
         x-show="show" 
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;"
         x-transition.opacity>
         
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-900/50 backdrop-blur-sm" @click="show = false"></div>

            <div class="relative inline-block w-full max-w-md p-6 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl z-10"
                 x-show="show"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100">
                
                <div class="flex justify-between items-center mb-5">
                    <h3 class="text-xl font-bold text-gray-900">Pembayaran Tunai</h3>
                    <button @click="show = false" type="button" class="text-gray-400 hover:text-gray-500 hover:bg-gray-100 p-2 rounded-xl transition-colors min-h-[44px] min-w-[44px] flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="bg-orange-50 rounded-xl p-4 mb-5 border border-orange-100 text-center">
                    <p class="text-sm font-semibold text-orange-800 uppercase tracking-wide mb-1">Total Tagihan</p>
                    <p class="text-3xl font-black text-orange-600">Rp {{ number_format($this->cartTotal, 0, ',', '.') }}</p>
                </div>

                <div class="space-y-4">
                    <!-- Tipe Pesanan -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tipe Pesanan</label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="flex items-center justify-center p-3 border rounded-xl cursor-pointer transition-colors {{ $orderType === 'takeaway' ? 'bg-orange-50 border-orange-500 text-orange-700' : 'border-gray-200 text-gray-600 hover:bg-gray-50' }} min-h-[44px]">
                                <input type="radio" wire:model.live="orderType" value="takeaway" class="sr-only">
                                <span class="text-sm font-bold">Bungkus (Takeaway)</span>
                            </label>
                            <label class="flex items-center justify-center p-3 border rounded-xl cursor-pointer transition-colors {{ $orderType === 'dine_in' ? 'bg-orange-50 border-orange-500 text-orange-700' : 'border-gray-200 text-gray-600 hover:bg-gray-50' }} min-h-[44px]">
                                <input type="radio" wire:model.live="orderType" value="dine_in" class="sr-only">
                                <span class="text-sm font-bold">Makan di Tempat</span>
                            </label>
                        </div>
                        @error('orderType') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Pilih Meja (jika dine_in) -->
                    @if($orderType === 'dine_in')
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Meja</label>
                        <select wire:model="selectedTableId" class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-orange-500 focus:border-orange-500 min-h-[44px]">
                            <option value="">-- Pilih Meja --</option>
                            @foreach($tables as $table)
                                <option value="{{ $table['id'] }}">{{ $table['table_number'] }}</option>
                            @endforeach
                        </select>
                        @error('selectedTableId') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    @endif

                    <!-- Input Uang Diterima -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Uang Tunai Diterima</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-gray-500 font-bold">Rp</span>
                            </div>
                            <input wire:model.live.debounce.500ms="amountReceived" type="number" class="block w-full pl-12 pr-4 py-3 bg-white border border-gray-300 rounded-xl text-lg font-bold focus:ring-orange-500 focus:border-orange-500 min-h-[44px]" placeholder="0">
                        </div>
                        @error('amountReceived') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Kembalian -->
                    @if($amountReceived !== null && $amountReceived >= $this->cartTotal)
                    <div class="bg-green-50 border border-green-100 rounded-xl p-3 flex justify-between items-center">
                        <span class="text-sm font-semibold text-green-800">Kembalian:</span>
                        <span class="text-lg font-extrabold text-green-700">Rp {{ number_format($this->changeAmount, 0, ',', '.') }}</span>
                    </div>
                    @endif
                </div>

                <div class="mt-8 flex justify-end gap-3">
                    <button type="button" @click="show = false" class="px-5 py-3 bg-gray-100 text-gray-700 rounded-xl text-sm font-bold hover:bg-gray-200 transition-colors min-h-[44px] w-full sm:w-auto">
                        Batal
                    </button>
                    {{-- TODO: Integrasikan dengan printer thermal atau generate PDF struk --}}
                    <button wire:click="processCheckout" 
                            wire:loading.attr="disabled"
                            @if($amountReceived === null || $amountReceived < $this->cartTotal) disabled @endif 
                            class="px-5 py-3 bg-orange-600 hover:bg-orange-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white rounded-xl text-sm font-bold shadow-sm transition-colors min-h-[44px] w-full sm:w-auto flex-1">
                        Konfirmasi & Cetak Struk
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
