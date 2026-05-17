<div>
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-4 sm:mb-0">Manajemen Meja</h1>
        <a href="{{ route('admin.table.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition shadow-sm font-medium">
            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Meja
        </a>
    </div>

    <!-- Alert Messages -->
    @if (session()->has('message'))
        <div class="mb-6 p-4 rounded-md {{ session('message_type') == 'success' ? 'bg-green-50 text-green-800' : 'bg-red-50 text-red-800' }}">
            {{ session('message') }}
        </div>
    @endif

    <!-- Toolbar / Search -->
    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 mb-6">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nomor meja..." class="w-full sm:w-1/2 lg:w-1/3 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
    </div>

    <!-- Grid Meja -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse ($tables as $table)
            <div wire:key="table-{{ $table->id }}" class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-visible relative flex flex-col p-5">
                
                <!-- Badge Status -->
                <div class="absolute top-4 right-4 flex space-x-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $table->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $table->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $table->status === 'available' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800' }}">
                        {{ $table->status === 'available' ? 'Tersedia' : 'Terisi' }}
                    </span>
                </div>

                <!-- Info Meja -->
                <div class="flex-1 mt-6">
                    <h3 class="text-3xl font-extrabold text-gray-900 mb-1">Meja {{ $table->table_number }}</h3>
                    <p class="text-xs text-gray-500 font-mono">Token: {{ $table->token ?? 'Belum ada' }}</p>
                </div>

                <!-- Area QR Code -->
                <div class="mt-4 bg-gray-50 rounded-md p-4 flex items-center justify-center min-h-[120px] border border-gray-100">
                    @if ($table->qr_code_image_path)
                        <a href="{{ Storage::url($table->qr_code_image_path) }}" target="_blank" title="Klik untuk lihat QR">
                            <img src="{{ Storage::url($table->qr_code_image_path) }}" alt="QR Meja {{ $table->table_number }}" class="w-24 h-24 object-contain shadow-sm border bg-white p-1 rounded hover:scale-105 transition-transform">
                        </a>
                    @else
                        <div class="text-center">
                            <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                            </svg>
                            <span class="block mt-1 text-xs text-gray-500">QR belum di-generate</span>
                        </div>
                    @endif
                </div>

                <!-- Alpine Dropdown Actions -->
                <div class="mt-4 flex justify-end" x-data="{ open: false }">
                    <div class="relative inline-block text-left">
                        <button @click="open = !open" @click.away="open = false" type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none">
                            Aksi
                            <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div x-show="open" x-transition class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 z-10" style="display: none;">
                            <div class="py-1">
                                <a href="{{ route('admin.table.edit', $table->id) }}" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                    Edit Meja
                                </a>
                            </div>
                            <div class="py-1">
                                @if (!$table->qr_code_image_path)
                                    <button wire:click="generateQr({{ $table->id }})" @click="open = false" class="w-full text-left group flex items-center px-4 py-2 text-sm text-indigo-700 hover:bg-indigo-50">
                                        Generate QR Code
                                    </button>
                                @else
                                    <button wire:click="regenerateToken({{ $table->id }})" @click="open = false" class="w-full text-left group flex items-center px-4 py-2 text-sm text-orange-600 hover:bg-orange-50" title="Mengganti token lama">
                                        Regenerate QR & Token
                                    </button>
                                    <a href="{{ route('admin.table.print', $table->id) }}" target="_blank" class="group flex items-center px-4 py-2 text-sm text-green-700 hover:bg-green-50">
                                        Cetak Label PDF
                                    </a>
                                @endif
                            </div>
                            <div class="py-1">
                                <button @click="open = false; $dispatch('open-delete-modal', { id: {{ $table->id }}, name: 'Meja {{ $table->table_number }}' })" class="w-full text-left group flex items-center px-4 py-2 text-sm text-red-700 hover:bg-red-50">
                                    Hapus Meja
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        @empty
            <div class="col-span-full py-12 text-center bg-white rounded-lg shadow-sm border border-gray-200">
                <p class="mt-1 text-sm text-gray-500">Belum ada data meja.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $tables->links() }}
    </div>

    <!-- GLOBAL DELETE MODAL ALPINE -->
    <div x-data="{ open: false, tableId: null, tableName: '' }" 
         @open-delete-modal.window="open = true; tableId = $event.detail.id; tableName = $event.detail.name"
         @table-deleted.window="open = false"
         x-show="open" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-0"
         style="display: none;">
        
        <div x-show="open" x-transition.opacity class="absolute inset-0 bg-black/50" @click="open = false"></div>

        <div x-show="open" x-transition class="relative bg-white rounded-lg shadow-xl max-w-sm w-full p-6 z-10">
            <h3 class="text-lg font-medium text-gray-900 mb-2">Hapus Meja</h3>
            <p class="text-sm text-gray-500 mb-6">Apakah Anda yakin ingin menghapus <span class="font-bold" x-text="tableName"></span>? Tindakan ini tidak dapat dibatalkan.</p>
            
            <div class="flex flex-row-reverse space-x-3 space-x-reverse">
                <button @click="$wire.delete(tableId)" class="w-full sm:w-auto inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-white font-medium hover:bg-red-700 focus:outline-none">
                    <span wire:loading.remove wire:target="delete">Hapus</span>
                    <span wire:loading wire:target="delete">Menghapus...</span>
                </button>
                <button @click="open = false" class="w-full sm:w-auto inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-gray-700 font-medium hover:bg-gray-50 focus:outline-none">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>
