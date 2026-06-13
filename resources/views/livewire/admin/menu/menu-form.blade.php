<div class="max-w-4xl mx-auto py-6">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">{{ $menuId ? 'Edit Menu' : 'Tambah Menu Baru' }}</h1>
        <a href="{{ route('admin.menu.index') }}" class="text-gray-500 hover:text-gray-700">Kembali</a>
    </div>

    <form wire:submit="save" class="bg-white shadow-sm rounded-lg border border-gray-200 p-6 space-y-6">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Nama Menu -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Nama Menu <span class="text-red-500">*</span></label>
                <input type="text" wire:model="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Kategori -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Kategori <span class="text-red-500">*</span></label>
                <select wire:model="category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            
            <!-- Harga -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Harga (Rp) <span class="text-red-500">*</span></label>
                <input type="number" wire:model="price" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @error('price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Stok -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Stok (Biarkan kosong jika tidak terbatas)</label>
                <input type="number" wire:model="stock_quantity" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @error('stock_quantity') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Deskripsi -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
            <textarea wire:model="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
            @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <hr class="border-gray-200">

        <!-- Relasi Toppings & Spiciness -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Pilihan Topping</label>
                <div class="space-y-2 max-h-48 overflow-y-auto border border-gray-200 rounded-md p-3">
                    @forelse($allToppings as $topping)
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="toppings" value="{{ $topping->id }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-600">{{ $topping->name }} ({{ formatRupiah($topping->price) }})</span>
                        </label>
                    @empty
                        <p class="text-sm text-gray-500">Belum ada topping tersedia.</p>
                    @endforelse
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Pilihan Level Pedas</label>
                <div class="space-y-2 max-h-48 overflow-y-auto border border-gray-200 rounded-md p-3">
                    @forelse($allSpicinessLevels as $level)
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="spicinessLevels" value="{{ $level->id }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-600">{{ $level->name }}</span>
                        </label>
                    @empty
                        <p class="text-sm text-gray-500">Belum ada level pedas tersedia.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <hr class="border-gray-200">

        <!-- Gambar -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Gambar Menu</label>
            <div class="flex items-center space-x-6">
                <div class="flex-shrink-0 h-32 w-32 bg-gray-100 rounded-lg overflow-hidden border border-gray-300 flex items-center justify-center">
                    @if ($image)
                        <img src="{{ $image->temporaryUrl() }}" class="object-cover h-full w-full">
                    @elseif ($existingImage)
                        <img src="{{ Storage::url($existingImage) }}" class="object-cover h-full w-full">
                    @else
                        <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    @endif
                </div>
                <div>
                    <input type="file" wire:model="image" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    <p class="mt-2 text-xs text-gray-500">PNG, JPG, JPEG maks 2MB.</p>
                    @error('image') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>
            <!-- Loading indicator untuk file upload -->
            <div wire:loading wire:target="image" class="mt-2 text-sm text-indigo-600">
                Mengunggah gambar...
            </div>
        </div>

        <!-- Status & Submit -->
        <div class="flex items-center justify-between pt-4">
            <div class="flex items-center">
                <input type="checkbox" wire:model="is_available" id="is_available" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                <label for="is_available" class="ml-2 block text-sm text-gray-900">
                    Tersedia (Bisa dipesan pelanggan)
                </label>
            </div>
            
            <button type="submit" class="inline-flex items-center px-6 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                <span wire:loading.remove wire:target="save">Simpan Menu</span>
                <span wire:loading wire:target="save">Menyimpan...</span>
            </button>
        </div>

    </form>
</div>
