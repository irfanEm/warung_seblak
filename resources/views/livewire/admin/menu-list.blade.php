<div class="space-y-6">
    <!-- Top Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl border border-gray-200/80 shadow-[0_1px_3px_rgba(0,0,0,0.01)]">
        <div>
            <div class="flex items-center space-x-3">
                <div class="p-2 bg-orange-50 text-orange-600 rounded-xl">
                    <x-icons.clipboard class="w-6 h-6" />
                </div>
                <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Manajemen Menu</h1>
            </div>
            <p class="text-sm text-gray-500 mt-1">Kelola detail menu makanan seblak, minuman segar, camilan tambahan, status ketersediaan, dan topping.</p>
        </div>
        <div>
            <button 
                wire:click="create" 
                class="inline-flex items-center justify-center px-5 py-2.5 bg-orange-600 hover:bg-orange-700 text-white font-bold text-sm rounded-xl shadow-lg shadow-orange-600/20 hover:shadow-orange-600/30 active:scale-[0.98] transition-all min-h-[44px] min-w-[44px] focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2"
            >
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Menu Baru
            </button>
        </div>
    </div>

    <!-- Filter & Search Panel -->
    <div class="bg-white p-4 rounded-2xl border border-gray-200/80 shadow-xs flex flex-col md:flex-row items-stretch md:items-center gap-4 justify-between">
        <!-- Search Input -->
        <div class="relative flex-grow max-w-md">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input 
                type="text" 
                wire:model.live.debounce.300ms="search" 
                placeholder="Cari nama menu seblak..." 
                class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-300 focus:ring-orange-500 focus:border-orange-500 focus:outline-none focus:ring-2 text-base transition-all bg-gray-50/30"
            >
        </div>

        <!-- Filter Category Dropdown -->
        <div class="flex items-center gap-2">
            <span class="text-xs font-bold text-gray-400 uppercase hidden sm:block shrink-0">Filter Kategori:</span>
            <select 
                wire:model.live="selectedCategory" 
                class="px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-orange-500 focus:border-orange-500 focus:outline-none focus:ring-2 text-base transition-all bg-white"
            >
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat['id'] }}">{{ $cat['name'] }}</option>
                @endforeach
            </select>

            <!-- Reset Filter if Selected -->
            @if($selectedCategory || !empty($search))
                <button 
                    wire:click="$set('selectedCategory', null); $set('search', '')" 
                    class="p-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 hover:text-gray-800 rounded-xl transition-colors font-bold text-xs shrink-0 flex items-center min-h-[44px]"
                >
                    Reset
                </button>
            @endif
        </div>
    </div>

    <!-- Main List Container -->
    <div class="bg-white rounded-2xl border border-gray-200/80 shadow-xs overflow-hidden">
        @if(count($filteredMenus) > 0)
            <!-- Desktop Table View -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-150 text-left text-sm">
                    <thead class="bg-gray-50/70 text-gray-500 font-bold uppercase tracking-wider text-xs">
                        <tr>
                            <th class="px-6 py-4">Menu</th>
                            <th class="px-6 py-4">Kategori & Kepedasan</th>
                            <th class="px-6 py-4">Harga</th>
                            <th class="px-6 py-4">Toppings</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-gray-700">
                        @foreach($filteredMenus as $menu)
                            @php
                                $categoryName = collect($categories)->firstWhere('id', $menu['category_id'])['name'] ?? 'Lainnya';
                                $spicinessLevelName = collect($spicinessLevels)->firstWhere('id', $menu['spiciness_level_id'])['name'] ?? 'Tidak Pedas';
                            @endphp
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3.5">
                                        <img 
                                            src="{{ $menu['image'] }}" 
                                            alt="{{ $menu['name'] }}" 
                                            class="w-14 h-14 object-cover rounded-xl border border-gray-100 shadow-xs"
                                        >
                                        <div class="max-w-xs">
                                            <h4 class="font-bold text-gray-900 leading-tight">{{ $menu['name'] }}</h4>
                                            <p class="text-xs text-gray-400 line-clamp-1 mt-0.5" title="{{ $menu['description'] }}">
                                                {{ $menu['description'] ?: 'Tidak ada deskripsi.' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 space-y-1">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-bold bg-orange-50 text-orange-600 border border-orange-100/50">
                                        {{ $categoryName }}
                                    </span>
                                    <div class="flex items-center text-xs font-bold text-red-600">
                                        <x-icons.fire class="w-3.5 h-3.5 mr-0.5 inline shrink-0" />
                                        {{ $spicinessLevelName }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-bold text-gray-900">
                                    Rp {{ number_format($menu['price'], 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 max-w-xs">
                                    <div class="flex flex-wrap gap-1">
                                        @if(!empty($menu['toppings']))
                                            @foreach($menu['toppings'] as $topId)
                                                @php 
                                                    $topName = collect($toppingsList)->firstWhere('id', $topId)['name'] ?? '';
                                                @endphp
                                                @if($topName)
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-gray-100 text-gray-600">
                                                        {{ $topName }}
                                                    </span>
                                                @endif
                                            @endforeach
                                        @else
                                            <span class="text-xs text-gray-400 font-semibold italic">Polos</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $menu['is_available'] ? 'bg-green-50 text-green-700 border border-green-150' : 'bg-red-50 text-red-700 border border-red-150' }}">
                                        <span class="w-1.5 h-1.5 {{ $menu['is_available'] ? 'bg-green-500' : 'bg-red-500' }} rounded-full mr-1.5"></span>
                                        {{ $menu['is_available'] ? 'Tersedia' : 'Habis' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <button 
                                            wire:click="edit({{ $menu['id'] }})"
                                            class="inline-flex items-center justify-center p-2 text-blue-600 hover:bg-blue-50 rounded-xl transition-all min-h-[44px] min-w-[44px] active:scale-95"
                                            title="Edit Menu"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <button 
                                            x-on:click.prevent="if(confirm('Apakah Anda yakin ingin menghapus menu &quot;{{ $menu['name'] }}&quot;?')) $wire.delete({{ $menu['id'] }})"
                                            class="inline-flex items-center justify-center p-2 text-red-600 hover:bg-red-50 rounded-xl transition-all min-h-[44px] min-w-[44px] active:scale-95"
                                            title="Hapus Menu"
                                        >
                                            <x-icons.trash class="w-5 h-5" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile & Tablet Card Grid View -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 lg:hidden">
                @foreach($filteredMenus as $menu)
                    @php
                        $categoryName = collect($categories)->firstWhere('id', $menu['category_id'])['name'] ?? 'Lainnya';
                        $spicinessLevelName = collect($spicinessLevels)->firstWhere('id', $menu['spiciness_level_id'])['name'] ?? 'Tidak Pedas';
                    @endphp
                    <div class="bg-white border border-gray-150 p-4 rounded-xl shadow-xs space-y-3.5">
                        <div class="flex items-start space-x-3">
                            <img 
                                src="{{ $menu['image'] }}" 
                                alt="{{ $menu['name'] }}" 
                                class="w-16 h-16 object-cover rounded-lg border border-gray-100 shrink-0"
                            >
                            <div class="space-y-1">
                                <h4 class="font-bold text-gray-900 text-sm leading-tight">{{ $menu['name'] }}</h4>
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-orange-50 text-orange-600">
                                    {{ $categoryName }}
                                </span>
                                <div class="text-[10px] font-bold text-red-600 flex items-center">
                                    <x-icons.fire class="w-3 h-3 mr-0.5 shrink-0" />
                                    {{ $spicinessLevelName }}
                                </div>
                            </div>
                        </div>
                        
                        <p class="text-xs text-gray-400 line-clamp-2">{{ $menu['description'] ?: 'Tidak ada deskripsi.' }}</p>

                        <div class="flex items-center justify-between pt-2 border-t border-gray-100">
                            <div>
                                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider block">Harga Menu</span>
                                <span class="text-sm font-extrabold text-orange-600">Rp {{ number_format($menu['price'], 0, ',', '.') }}</span>
                            </div>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold {{ $menu['is_available'] ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }}">
                                {{ $menu['is_available'] ? 'Tersedia' : 'Habis' }}
                            </span>
                        </div>

                        <!-- Card Actions -->
                        <div class="flex items-center justify-end space-x-2 pt-2 border-t border-gray-150">
                            <button 
                                wire:click="edit({{ $menu['id'] }})"
                                class="inline-flex items-center justify-center px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg text-xs font-bold transition-all min-h-[44px] min-w-[44px]"
                            >
                                Edit
                            </button>
                            <button 
                                x-on:click.prevent="if(confirm('Apakah Anda yakin ingin menghapus menu &quot;{{ $menu['name'] }}&quot;?')) $wire.delete({{ $menu['id'] }})"
                                class="inline-flex items-center justify-center px-3 py-1.5 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg text-xs font-bold transition-all min-h-[44px] min-w-[44px]"
                            >
                                Hapus
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="p-12 text-center">
                <div class="mx-auto w-16 h-16 bg-gray-50 text-gray-400 rounded-2xl flex items-center justify-center mb-4">
                    <x-icons.clipboard class="w-8 h-8" />
                </div>
                <h3 class="font-bold text-gray-900 text-lg">Menu tidak ditemukan</h3>
                <p class="text-sm text-gray-500 mt-1 max-w-sm mx-auto">Kami tidak dapat menemukan menu dengan kriteria pencarian atau filter saat ini.</p>
                <button 
                    wire:click="$set('selectedCategory', null); $set('search', '')" 
                    class="mt-4 inline-flex items-center justify-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-sm rounded-xl min-h-[44px]"
                >
                    Reset Filter
                </button>
            </div>
        @endif
    </div>

    <!-- Alpine Modal Form (Large) -->
    <div 
        x-data="{ open: @entangle('showForm') }" 
        x-show="open" 
        class="fixed inset-0 z-50 overflow-y-auto" 
        style="display: none;"
    >
        <!-- Modal Backdrop -->
        <div 
            x-show="open" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-gray-900/60 backdrop-blur-xs transition-opacity"
            @click="open = false"
        ></div>

        <!-- Modal Contents -->
        <div class="flex min-h-screen items-center justify-center p-4">
            <div 
                x-show="open" 
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative bg-white rounded-2xl shadow-xl w-full max-w-2xl overflow-hidden transform transition-all border border-gray-100"
            >
                <!-- Modal Header -->
                <div class="bg-gray-50/80 px-6 py-4 border-b border-gray-150 flex items-center justify-between">
                    <h3 class="text-lg font-extrabold text-gray-900">
                        {{ $editingMenuId ? 'Edit Item Menu' : 'Tambah Item Menu Baru' }}
                    </h3>
                    <button 
                        @click="open = false" 
                        class="p-1 rounded-lg text-gray-400 hover:bg-gray-150 hover:text-gray-600 transition-colors min-h-[44px] min-w-[44px] flex items-center justify-center"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Modal Form Body -->
                <form wire:submit.prevent="save" class="p-6 space-y-4 max-h-[70vh] overflow-y-auto">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Nama Menu -->
                        <div class="space-y-1 sm:col-span-2">
                            <label for="menuName" class="block text-sm font-bold text-gray-700">Nama Menu <span class="text-red-500">*</span></label>
                            <input 
                                type="text" 
                                id="menuName" 
                                wire:model="form.name"
                                placeholder="Contoh: Seblak Seafood Spesial, Es Teh Jumbo"
                                class="w-full px-4 py-2.5 rounded-xl border @error('form.name') border-red-300 bg-red-50/30 focus:ring-red-500 focus:border-red-500 @else border-gray-300 focus:ring-orange-500 focus:border-orange-500 @enderror focus:outline-none focus:ring-2 text-base transition-all"
                                required
                            >
                            @error('form.name')
                                <p class="text-xs font-semibold text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kategori Dropdown -->
                        <div class="space-y-1">
                            <label for="formCategory" class="block text-sm font-bold text-gray-700">Kategori Menu <span class="text-red-500">*</span></label>
                            <select 
                                id="formCategory" 
                                wire:model="form.category_id"
                                class="w-full px-4 py-2.5 rounded-xl border @error('form.category_id') border-red-300 focus:ring-red-500 focus:border-red-500 @else border-gray-300 focus:ring-orange-500 focus:border-orange-500 @enderror focus:outline-none focus:ring-2 text-base transition-all"
                                required
                            >
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat['id'] }}">{{ $cat['name'] }}</option>
                                @endforeach
                            </select>
                            @error('form.category_id')
                                <p class="text-xs font-semibold text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Level Pedas Default -->
                        <div class="space-y-1">
                            <label for="formSpiciness" class="block text-sm font-bold text-gray-700">Tingkat Pedas Default <span class="text-red-500">*</span></label>
                            <select 
                                id="formSpiciness" 
                                wire:model="form.selectedSpicinessLevelId"
                                class="w-full px-4 py-2.5 rounded-xl border @error('form.selectedSpicinessLevelId') border-red-300 focus:ring-red-500 focus:border-red-500 @else border-gray-300 focus:ring-orange-500 focus:border-orange-500 @enderror focus:outline-none focus:ring-2 text-base transition-all"
                                required
                            >
                                <option value="">-- Pilih Level Pedas --</option>
                                @foreach($spicinessLevels as $lvl)
                                    <option value="{{ $lvl['id'] }}">{{ $lvl['name'] }} (LVL {{ $lvl['level'] }})</option>
                                @endforeach
                            </select>
                            @error('form.selectedSpicinessLevelId')
                                <p class="text-xs font-semibold text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Harga Menu -->
                        <div class="space-y-1">
                            <label for="formPrice" class="block text-sm font-bold text-gray-700">Harga Jual Menu <span class="text-red-500">*</span></label>
                            <div class="relative rounded-xl shadow-xs">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                    <span class="text-gray-400 font-bold text-sm">Rp</span>
                                </div>
                                <input 
                                    type="number" 
                                    id="formPrice" 
                                    wire:model="form.price"
                                    placeholder="Contoh: 15000"
                                    min="0"
                                    class="w-full pl-10 pr-4 py-2.5 rounded-xl border @error('form.price') border-red-300 focus:ring-red-500 focus:border-red-500 @else border-gray-300 focus:ring-orange-500 focus:border-orange-500 @enderror focus:outline-none focus:ring-2 text-base transition-all"
                                    required
                                >
                            </div>
                            @error('form.price')
                                <p class="text-xs font-semibold text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Link Gambar Placeholder -->
                        <div class="space-y-1">
                            <label for="formImage" class="block text-sm font-bold text-gray-700">Link URL Gambar (Placeholder)</label>
                            <input 
                                type="text" 
                                id="formImage" 
                                wire:model="form.image"
                                placeholder="https://images.unsplash.com/..."
                                class="w-full px-4 py-2.5 rounded-xl border @error('form.image') border-red-300 focus:ring-red-500 focus:border-red-500 @else border-gray-300 focus:ring-orange-500 focus:border-orange-500 @enderror focus:outline-none focus:ring-2 text-base transition-all"
                            >
                            @error('form.image')
                                <p class="text-xs font-semibold text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Deskripsi Menu -->
                        <div class="space-y-1 sm:col-span-2">
                            <label for="formDescription" class="block text-sm font-bold text-gray-700">Deskripsi Detail Menu</label>
                            <textarea 
                                id="formDescription" 
                                wire:model="form.description"
                                rows="3"
                                placeholder="Jelaskan bahan-bahan dan rasa menu seblak ini..."
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-orange-500 focus:border-orange-500 focus:outline-none focus:ring-2 text-base transition-all"
                            ></textarea>
                            @error('form.description')
                                <p class="text-xs font-semibold text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status Tersedia Toggle -->
                        <div class="sm:col-span-2 flex items-center justify-between bg-gray-50/50 p-4 rounded-xl border border-gray-150">
                            <div class="space-y-0.5">
                                <span class="block text-sm font-bold text-gray-900">Status Ketersediaan Menu</span>
                                <span class="block text-xs text-gray-400 font-semibold">Tentukan apakah menu ini aktif dan dapat dipesan pelanggan.</span>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer select-none">
                                <input 
                                    type="checkbox" 
                                    wire:model="form.is_available" 
                                    class="sr-only peer"
                                >
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-600"></div>
                            </label>
                        </div>

                        <!-- Checkboxes Pilihan Toppings Multi-select -->
                        <div class="sm:col-span-2 space-y-2">
                            <label class="block text-sm font-bold text-gray-700">Topping Tambahan Tersedia (Opsional)</label>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 bg-gray-50/30 p-4 rounded-xl border border-gray-150 max-h-48 overflow-y-auto">
                                @foreach($toppingsList as $topping)
                                    <label class="relative flex items-start space-x-2.5 p-1 rounded hover:bg-gray-50 select-none cursor-pointer">
                                        <div class="flex items-center h-5">
                                            <input 
                                                type="checkbox" 
                                                id="topping-chk-{{ $topping['id'] }}"
                                                value="{{ $topping['id'] }}" 
                                                wire:model="form.selectedToppings"
                                                class="w-4.5 h-4.5 text-orange-600 border-gray-300 rounded focus:ring-orange-500"
                                            >
                                        </div>
                                        <div class="text-xs">
                                            <span class="font-bold text-gray-800">{{ $topping['name'] }}</span>
                                            <span class="text-gray-400 block font-medium">+Rp {{ number_format($topping['price'], 0, ',', '.') }}</span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Modal Actions -->
                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-100 mt-6">
                        <button 
                            type="button" 
                            @click="open = false" 
                            class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-sm rounded-xl transition-colors min-h-[44px]"
                        >
                            Batal
                        </button>
                        <button 
                            type="submit" 
                            class="px-5 py-2.5 bg-orange-600 hover:bg-orange-700 text-white font-bold text-sm rounded-xl shadow-lg shadow-orange-600/10 hover:shadow-orange-600/20 active:scale-95 transition-all min-h-[44px]"
                        >
                            Simpan Menu
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
