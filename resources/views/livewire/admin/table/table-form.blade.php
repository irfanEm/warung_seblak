<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">{{ $tableId ? 'Edit Meja' : 'Tambah Meja Baru' }}</h1>
        <a href="{{ route('admin.table.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
            &larr; Kembali
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <form wire:submit.prevent="save" class="p-6 space-y-6">
            
            <!-- Table Number -->
            <div>
                <label for="table_number" class="block text-sm font-medium text-gray-700">Nomor / Nama Meja</label>
                <div class="mt-1">
                    <input type="text" id="table_number" wire:model="table_number" 
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" 
                           placeholder="Contoh: 1, 2A, VIP-1">
                </div>
                @error('table_number') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status Awal</label>
                <select id="status" wire:model="status" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="available">Tersedia (Available)</option>
                    <option value="occupied">Terisi (Occupied)</option>
                </select>
                @error('status') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <!-- Is Active Toggle -->
            <div class="flex items-center justify-between">
                <div>
                    <label class="text-sm font-medium text-gray-700">Meja Aktif?</label>
                    <p class="text-xs text-gray-500">Meja nonaktif tidak bisa digunakan untuk order via QR.</p>
                </div>
                <div x-data="{ isActive: @entangle('is_active') }">
                    <button type="button" @click="isActive = !isActive" 
                            :class="isActive ? 'bg-indigo-600' : 'bg-gray-200'" 
                            class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none">
                        <span aria-hidden="true" 
                              :class="isActive ? 'translate-x-5' : 'translate-x-0'" 
                              class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                    </button>
                </div>
            </div>

            <div class="pt-5 border-t border-gray-200 flex justify-end">
                <button type="button" onclick="window.history.back()" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none mr-3">
                    Batal
                </button>
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none">
                    <span wire:loading.remove wire:target="save">Simpan</span>
                    <span wire:loading wire:target="save">Menyimpan...</span>
                </button>
            </div>
        </form>
    </div>
</div>
