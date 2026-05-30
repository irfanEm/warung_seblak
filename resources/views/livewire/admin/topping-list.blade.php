<div class="space-y-6">
    <!-- Top Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl border border-gray-200/80 shadow-[0_1px_3px_rgba(0,0,0,0.01)]">
        <div>
            <div class="flex items-center space-x-3">
                <div class="p-2 bg-orange-50 text-orange-600 rounded-xl">
                    <x-icons.sparkles class="w-6 h-6" />
                </div>
                <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Topping Tambahan</h1>
            </div>
            <p class="text-sm text-gray-500 mt-1">Kelola topping pelengkap seblak seperti bakso, sosis, keju, telur, dan lainnya.</p>
        </div>
        <div>
            <button 
                wire:click="create" 
                class="inline-flex items-center justify-center px-5 py-2.5 bg-orange-600 hover:bg-orange-700 text-white font-bold text-sm rounded-xl shadow-lg shadow-orange-600/20 hover:shadow-orange-600/30 active:scale-[0.98] transition-all min-h-[44px] min-w-[44px] focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2"
            >
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Topping
            </button>
        </div>
    </div>

    <!-- Stats Summary Row -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-gray-200/80 shadow-xs flex items-center space-x-4">
            <div class="p-3 bg-orange-50 text-orange-600 rounded-xl">
                <x-icons.sparkles class="w-6 h-6" />
            </div>
            <div>
                <span class="text-xs text-gray-400 font-bold uppercase tracking-wider">Total Topping</span>
                <h3 class="text-xl font-extrabold text-gray-900 mt-0.5">{{ count($toppings) }} Varian</h3>
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-gray-200/80 shadow-xs flex items-center space-x-4">
            <div class="p-3 bg-green-50 text-green-600 rounded-xl">
                <span class="text-xl">💰</span>
            </div>
            <div>
                <span class="text-xs text-gray-400 font-bold uppercase tracking-wider">Harga Rata-Rata</span>
                <h3 class="text-xl font-extrabold text-gray-900 mt-0.5">
                    Rp {{ count($toppings) > 0 ? number_format(collect($toppings)->avg('price'), 0, ',', '.') : '0' }}
                </h3>
            </div>
        </div>
    </div>

    <!-- Main List Container -->
    <div class="bg-white rounded-2xl border border-gray-200/80 shadow-xs overflow-hidden">
        @if(count($toppings) > 0)
            <!-- Desktop Table View -->
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-150 text-left text-sm">
                    <thead class="bg-gray-50/70 text-gray-500 font-bold uppercase tracking-wider text-xs">
                        <tr>
                            <th class="px-6 py-4">ID</th>
                            <th class="px-6 py-4">Nama Topping</th>
                            <th class="px-6 py-4">Harga Tambahan</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-gray-700">
                        @foreach($toppings as $topping)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 font-mono font-bold text-gray-400 text-xs">#{{ $topping['id'] }}</td>
                                <td class="px-6 py-4 font-semibold text-gray-900">{{ $topping['name'] }}</td>
                                <td class="px-6 py-4 font-bold text-orange-600">Rp {{ number_format($topping['price'], 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <button 
                                            wire:click="edit({{ $topping['id'] }})"
                                            class="inline-flex items-center justify-center p-2 text-blue-600 hover:bg-blue-50 rounded-xl transition-all min-h-[44px] min-w-[44px] active:scale-95"
                                            title="Edit Topping"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <button 
                                            x-on:click.prevent="if(confirm('Apakah Anda yakin ingin menghapus topping &quot;{{ $topping['name'] }}&quot;?')) $wire.delete({{ $topping['id'] }})"
                                            class="inline-flex items-center justify-center p-2 text-red-600 hover:bg-red-50 rounded-xl transition-all min-h-[44px] min-w-[44px] active:scale-95"
                                            title="Hapus Topping"
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

            <!-- Mobile Card Stack View -->
            <div class="grid grid-cols-1 gap-4 p-4 md:hidden">
                @foreach($toppings as $topping)
                    <div class="bg-white border border-gray-150 p-4 rounded-xl shadow-xs space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-mono font-bold text-gray-400">#{{ $topping['id'] }}</span>
                            <span class="text-sm font-bold text-orange-600">Rp {{ number_format($topping['price'], 0, ',', '.') }}</span>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 text-base">{{ $topping['name'] }}</h4>
                        </div>
                        <div class="flex items-center justify-end space-x-2 pt-2 border-t border-gray-100">
                            <button 
                                wire:click="edit({{ $topping['id'] }})"
                                class="inline-flex items-center justify-center px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg text-xs font-bold transition-all min-h-[44px] min-w-[44px]"
                            >
                                Edit
                            </button>
                            <button 
                                x-on:click.prevent="if(confirm('Apakah Anda yakin ingin menghapus topping &quot;{{ $topping['name'] }}&quot;?')) $wire.delete({{ $topping['id'] }})"
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
                    <x-icons.sparkles class="w-8 h-8" />
                </div>
                <h3 class="font-bold text-gray-900 text-lg">Belum ada Topping</h3>
                <p class="text-sm text-gray-500 mt-1 max-w-sm mx-auto">Tambahkan topping tambahan untuk memberikan variasi pilihan kustomisasi kepada pembeli seblak.</p>
                <button 
                    wire:click="create" 
                    class="mt-4 inline-flex items-center justify-center px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white font-bold text-sm rounded-xl min-h-[44px]"
                >
                    Tambah Sekarang
                </button>
            </div>
        @endif
    </div>

    <!-- Alpine Modal Form -->
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
                class="relative bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden transform transition-all border border-gray-100"
            >
                <!-- Modal Header -->
                <div class="bg-gray-50/80 px-6 py-4 border-b border-gray-150 flex items-center justify-between">
                    <h3 class="text-lg font-extrabold text-gray-900">
                        {{ $editingToppingId ? 'Edit Topping Menu' : 'Tambah Topping Baru' }}
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
                <form wire:submit.prevent="save" class="p-6 space-y-4">
                    <!-- Nama Topping -->
                    <div class="space-y-1">
                        <label for="name" class="block text-sm font-bold text-gray-700">Nama Topping <span class="text-red-500">*</span></label>
                        <input 
                            type="text" 
                            id="name" 
                            wire:model.live="name"
                            placeholder="Contoh: Ceker, Dumpling Keju, Pilus"
                            class="w-full px-4 py-2.5 rounded-xl border @error('name') border-red-300 bg-red-50/30 focus:ring-red-500 focus:border-red-500 @else border-gray-300 focus:ring-orange-500 focus:border-orange-500 @enderror focus:outline-none focus:ring-2 text-sm transition-all"
                            required
                        >
                        @error('name')
                            <p class="text-xs font-semibold text-red-600 mt-1 flex items-center">
                                <svg class="w-4 h-4 mr-1 text-red-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Harga Topping -->
                    <div class="space-y-1">
                        <label for="price" class="block text-sm font-bold text-gray-700">Harga Tambahan (Rupiah) <span class="text-red-500">*</span></label>
                        <div class="relative rounded-xl shadow-xs">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                <span class="text-gray-400 font-bold text-sm">Rp</span>
                            </div>
                            <input 
                                type="number" 
                                id="price" 
                                wire:model.live="price"
                                placeholder="Contoh: 3000"
                                min="0"
                                class="w-full pl-10 pr-4 py-2.5 rounded-xl border @error('price') border-red-300 bg-red-50/30 focus:ring-red-500 focus:border-red-500 @else border-gray-300 focus:ring-orange-500 focus:border-orange-500 @enderror focus:outline-none focus:ring-2 text-sm transition-all"
                                required
                            >
                        </div>
                        @error('price')
                            <p class="text-xs font-semibold text-red-600 mt-1 flex items-center">
                                <svg class="w-4 h-4 mr-1 text-red-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Modal Actions -->
                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-100 mt-6">
                        <button 
                            type="button" 
                            @click="open = false" 
                            class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-sm rounded-xl transition-colors min-h-[44px] min-w-[44px]"
                        >
                            Batal
                        </button>
                        <button 
                            type="submit" 
                            class="px-5 py-2.5 bg-orange-600 hover:bg-orange-700 text-white font-bold text-sm rounded-xl shadow-lg shadow-orange-600/10 hover:shadow-orange-600/20 active:scale-95 transition-all min-h-[44px] min-w-[44px]"
                        >
                            Simpan Topping
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
