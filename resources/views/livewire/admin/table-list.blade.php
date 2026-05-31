<div>
    <!-- Header Section -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Kelola Meja</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola data meja, generate token, dan cetak QR code untuk pemesanan.</p>
        </div>
        <button wire:click="create" class="inline-flex items-center justify-center px-4 py-2 bg-orange-600 border border-transparent rounded-xl font-semibold text-white text-sm hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition-all min-h-[44px] min-w-[44px]">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Meja
        </button>
    </div>

    <!-- TODO: Migrasi data session ini ke database saat struktur tabel sudah final -->

    <!-- Desktop Table View -->
    <div class="hidden lg:block bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nomor Meja</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Token</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">QR Code</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Status</th>
                    <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($tables as $index => $table)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $index + 1 }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $table['table_number'] }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-xs font-mono text-gray-500 bg-gray-100 px-2 py-1 rounded-md" title="{{ $table['token'] }}">
                            {{ substr($table['token'], 0, 8) }}...
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        @if($table['qr_code_path'])
                            <img src="{{ Storage::url($table['qr_code_path']) }}" alt="QR Meja" class="h-10 w-10 mx-auto object-contain bg-white p-0.5 border rounded-md shadow-sm">
                        @else
                            <span class="text-xs text-gray-400 italic">Belum digenerate</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        @if($table['is_active'])
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                Nonaktif
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex items-center justify-end space-x-2">
                            <!-- Tombol Generate QR -->
                            <button wire:click="generateQr({{ $table['id'] }})" class="p-2 text-blue-600 hover:text-blue-900 hover:bg-blue-50 rounded-lg transition-colors focus:outline-none min-h-[44px] min-w-[44px] flex items-center justify-center" title="Generate QR Code">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                            </button>
                            
                            <!-- Tombol Cetak Label -->
                            <a href="{{ route('admin.tables.print', ['id' => $table['id']]) }}" target="_blank" class="p-2 text-indigo-600 hover:text-indigo-900 hover:bg-indigo-50 rounded-lg transition-colors focus:outline-none min-h-[44px] min-w-[44px] flex items-center justify-center" title="Cetak Label QR">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            </a>

                            <!-- Tombol Edit -->
                            <button wire:click="edit({{ $table['id'] }})" class="p-2 text-orange-600 hover:text-orange-900 hover:bg-orange-50 rounded-lg transition-colors focus:outline-none min-h-[44px] min-w-[44px] flex items-center justify-center" title="Edit Meja">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </button>

                            <!-- Tombol Hapus -->
                            <button wire:click="delete({{ $table['id'] }})" wire:confirm="Apakah Anda yakin ingin menghapus meja ini?" class="p-2 text-red-600 hover:text-red-900 hover:bg-red-50 rounded-lg transition-colors focus:outline-none min-h-[44px] min-w-[44px] flex items-center justify-center" title="Hapus Meja">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
                @if(count($tables) === 0)
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                        Tidak ada data meja.
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

    <!-- Mobile Card View -->
    <div class="lg:hidden space-y-4">
        @foreach($tables as $table)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">{{ $table['table_number'] }}</h3>
                    <div class="mt-1 flex items-center gap-2">
                        @if($table['is_active'])
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-green-100 text-green-800">Aktif</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-red-100 text-red-800">Nonaktif</span>
                        @endif
                        <span class="text-xs text-gray-500 font-mono">TKN: {{ substr($table['token'], 0, 6) }}..</span>
                    </div>
                </div>
                <div>
                    @if($table['qr_code_path'])
                        <img src="{{ Storage::url($table['qr_code_path']) }}" alt="QR Meja" class="h-12 w-12 object-contain bg-white p-1 border rounded shadow-sm">
                    @else
                        <div class="h-12 w-12 bg-gray-100 rounded border border-dashed border-gray-300 flex items-center justify-center">
                            <span class="text-[9px] text-gray-400 text-center px-1">No QR</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Mobile Actions -->
            <div class="grid grid-cols-4 gap-2 border-t border-gray-100 pt-3 mt-3">
                <button wire:click="generateQr({{ $table['id'] }})" class="flex flex-col items-center justify-center p-2 text-blue-600 hover:bg-blue-50 rounded-xl min-h-[44px] transition-colors">
                    <svg class="w-5 h-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                    <span class="text-[10px] font-medium">Buat QR</span>
                </button>
                <a href="{{ route('admin.tables.print', ['id' => $table['id']]) }}" target="_blank" class="flex flex-col items-center justify-center p-2 text-indigo-600 hover:bg-indigo-50 rounded-xl min-h-[44px] transition-colors">
                    <svg class="w-5 h-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    <span class="text-[10px] font-medium">Cetak</span>
                </a>
                <button wire:click="edit({{ $table['id'] }})" class="flex flex-col items-center justify-center p-2 text-orange-600 hover:bg-orange-50 rounded-xl min-h-[44px] transition-colors">
                    <svg class="w-5 h-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    <span class="text-[10px] font-medium">Edit</span>
                </button>
                <button wire:click="delete({{ $table['id'] }})" wire:confirm="Yakin ingin menghapus meja ini?" class="flex flex-col items-center justify-center p-2 text-red-600 hover:bg-red-50 rounded-xl min-h-[44px] transition-colors">
                    <svg class="w-5 h-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    <span class="text-[10px] font-medium">Hapus</span>
                </button>
            </div>
        </div>
        @endforeach
        
        @if(count($tables) === 0)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8 text-center text-gray-500">
            Tidak ada data meja.
        </div>
        @endif
    </div>

    <!-- Modal Form Tambah/Edit (AlpineJS terikat dengan Livewire showForm) -->
    <div x-data="{ show: @entangle('showForm') }" 
         x-show="show" 
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;"
         x-transition.opacity>
         
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 transition-opacity bg-gray-900/50 backdrop-blur-sm" @click="show = false"></div>

            <!-- Modal panel -->
            <div class="relative inline-block w-full max-w-md p-6 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl z-10"
                 x-show="show"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                
                <div class="flex justify-between items-center mb-5">
                    <h3 class="text-xl font-bold text-gray-900">
                        {{ $editingTableId ? 'Edit Meja' : 'Tambah Meja Baru' }}
                    </h3>
                    <button @click="show = false" type="button" class="text-gray-400 hover:text-gray-500 hover:bg-gray-100 p-2 rounded-xl transition-colors focus:outline-none min-h-[44px] min-w-[44px] flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form wire:submit.prevent="save">
                    <div class="space-y-5">
                        <!-- Input Nomor Meja -->
                        <div>
                            <label for="table_number" class="block text-sm font-semibold text-gray-700 mb-1">Nomor Meja</label>
                            <input type="text" id="table_number" wire:model="form.table_number" class="block w-full px-4 py-3 border @error('form.table_number') border-red-500 focus:ring-red-500 focus:border-red-500 @else border-gray-300 focus:ring-orange-500 focus:border-orange-500 @enderror rounded-xl shadow-sm text-base transition-colors focus:outline-none focus:ring-2 focus:ring-offset-0" placeholder="Contoh: Meja 1, VIP A" required>
                            @error('form.table_number') <span class="mt-1 text-sm text-red-600 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Toggle Status -->
                        <div>
                            <label class="flex items-center cursor-pointer p-1">
                                <div class="relative">
                                    <input type="checkbox" wire:model="form.is_active" class="sr-only">
                                    <div class="block w-14 h-8 {{ $form['is_active'] ? 'bg-orange-500' : 'bg-gray-300' }} rounded-full transition-colors duration-300 ease-in-out"></div>
                                    <div class="dot absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition-transform duration-300 ease-in-out shadow-sm {{ $form['is_active'] ? 'transform translate-x-6' : '' }}"></div>
                                </div>
                                <div class="ml-3 text-sm font-semibold text-gray-700">
                                    Meja Aktif
                                </div>
                            </label>
                            @error('form.is_active') <span class="mt-1 text-sm text-red-600 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end gap-3">
                        <button type="button" @click="show = false" class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-xl text-sm font-semibold hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-200 transition-colors min-h-[44px]">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2.5 bg-orange-600 border border-transparent rounded-xl shadow-sm text-white text-sm font-semibold hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors min-h-[44px]">
                            Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
