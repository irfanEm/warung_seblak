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
                <div class="bg-white border border-gray-200 rounded-xl p-3.5 shadow-xs relative flex flex-col gap-2" wire:key="cart-item-{{ $key }}">
                    <div class="flex justify-between items-start">
                        <div>
                            <h4 class="font-bold text-xs text-gray-800 leading-snug">{{ $item['name'] }}</h4>
                            
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
                        <span class="text-xs font-black text-amber-600">
                            {{ formatRupiah($item['subtotal']) }}
                        </span>

                        <div class="flex items-center space-x-2 bg-gray-100 rounded-lg p-0.5">
                            <button wire:click="decrementQuantity('{{ $key }}')" class="w-6 h-6 flex items-center justify-center bg-white rounded shadow-xs text-gray-600 hover:text-amber-600 font-black text-xs">-</button>
                            <span class="text-[11px] font-bold w-5 text-center text-gray-700">{{ $item['quantity'] }}</span>
                            <button wire:click="incrementQuantity('{{ $key }}')" class="w-6 h-6 flex items-center justify-center bg-white rounded shadow-xs text-gray-600 hover:text-amber-600 font-black text-xs">+</button>
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
                <input type="text" wire:model="customerName" placeholder="Nama..." class="w-full bg-gray-50 border border-gray-200 rounded-lg px-2.5 py-1.5 focus:outline-none focus:border-amber-500">
            </div>
            <div>
                <label class="block text-[9px] uppercase font-bold text-gray-400 mb-0.5">No. Telp (Opsional)</label>
                <input type="text" wire:model="customerPhone" placeholder="08..." class="w-full bg-gray-50 border border-gray-200 rounded-lg px-2.5 py-1.5 focus:outline-none focus:border-amber-500">
            </div>
        </div>

        <div>
            <label class="block text-[9px] uppercase font-bold text-gray-400 mb-0.5">Catatan Dapur (Opsional)</label>
            <input type="text" wire:model="notes" placeholder="Kurang pedas, tanpa seledri, dll..." class="w-full bg-gray-50 border border-gray-200 rounded-lg px-2.5 py-1.5 text-xs focus:outline-none focus:border-amber-500">
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
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <span>Bayar & Proses (F10)</span>
        </button>
    </div>

    <!-- Payment Modal -->
    @if($showPaymentModal)
        <div class="relative z-50 flex items-center justify-center">
            <div wire:click="closePaymentModal" class="fixed inset-0 bg-black/60 backdrop-blur-xs transition-opacity"></div>

            <div class="fixed inset-0 overflow-y-auto flex items-center justify-center p-4">
                <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden relative flex flex-col">
                    
                    <!-- Header -->
                    <div class="p-5 border-b border-gray-150 bg-gray-50 flex items-center justify-between shrink-0">
                        <div class="flex items-center space-x-2">
                            <span class="text-lg">💰</span>
                            <h2 class="text-base font-extrabold text-gray-800 leading-snug">Metode Pembayaran & Proses</h2>
                        </div>
                        <button wire:click="closePaymentModal" class="text-gray-400 hover:text-gray-600 p-1.5 hover:bg-gray-150 rounded-full shrink-0 transition">
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

                        <!-- Method Radio Checklist -->
                        <div>
                            <h3 class="text-xs uppercase font-black tracking-wider text-gray-400 mb-2.5">Metode Pembayaran</h3>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="relative border border-gray-200 rounded-xl p-4 flex flex-col items-center justify-center cursor-pointer hover:bg-gray-50 hover:border-gray-300 transition active:scale-[0.98]">
                                    <input type="radio" wire:model="paymentMethod" value="tunai" class="sr-only">
                                    <span class="text-xl mb-1">💵</span>
                                    <span class="text-xs font-bold {{ $paymentMethod === 'tunai' ? 'text-emerald-600' : 'text-gray-700' }}">Tunai / Cash</span>
                                    <div class="absolute inset-0 border-2 rounded-xl pointer-events-none {{ $paymentMethod === 'tunai' ? 'border-emerald-500 bg-emerald-50/10' : 'border-transparent' }}"></div>
                                </label>

                                <label class="relative border border-gray-200 rounded-xl p-4 flex flex-col items-center justify-center cursor-pointer hover:bg-gray-50 hover:border-gray-300 transition active:scale-[0.98]">
                                    <input type="radio" wire:model="paymentMethod" value="non_tunai" class="sr-only">
                                    <span class="text-xl mb-1">💳</span>
                                    <span class="text-xs font-bold {{ $paymentMethod === 'non_tunai' ? 'text-indigo-600' : 'text-gray-700' }}">Debit/QRIS (Non-Tunai)</span>
                                    <div class="absolute inset-0 border-2 rounded-xl pointer-events-none {{ $paymentMethod === 'non_tunai' ? 'border-indigo-500 bg-indigo-50/10' : 'border-transparent' }}"></div>
                                </label>
                            </div>
                        </div>

                        <!-- Cash received input: Only if Tunai -->
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

                                <!-- Change calculation -->
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
                                <p class="text-gray-600 leading-normal">Status pembayaran pesanan ini akan diset sebagai <strong class="text-blue-900">Payment Pending</strong>. Silakan sediakan terminal EDC atau tunjukkan kode QRIS Outlet ke pelanggan terlebih dahulu.</p>
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
