<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0">
        <h1 class="text-2xl font-bold text-gray-900">Daftar Menu</h1>
        <a href="{{ route('admin.menu.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
            + Tambah Menu
        </a>
    </div>

    <!-- Filters -->
    <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4 bg-white p-4 rounded-lg shadow-sm border border-gray-200">
        <div class="flex-1">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama menu..." class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
        </div>
        <div class="w-full sm:w-64">
            <select wire:model.live="categoryFilter" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                <option value="">Semua Kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($menus as $menu)
            <div wire:key="menu-{{ $menu->id }}" class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden flex flex-col">
                <!-- Image -->
                <div class="h-48 bg-gray-200 flex-shrink-0">
                    @if($menu->image)
                        <img src="{{ Storage::url($menu->image) }}" alt="{{ $menu->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                            No Image
                        </div>
                    @endif
                </div>
                
                <!-- Content -->
                <div class="p-4 flex-1 flex flex-col">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="text-lg font-semibold text-gray-900 line-clamp-1">{{ $menu->name }}</h3>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $menu->is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $menu->is_available ? 'Tersedia' : 'Habis' }}
                        </span>
                    </div>
                    
                    <p class="text-sm text-gray-500 mb-4">{{ $menu->category->name ?? 'Uncategorized' }}</p>
                    
                    <div class="mt-auto flex items-center justify-between">
                        <span class="text-lg font-bold text-gray-900">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
                        
                        <div class="flex space-x-2" x-data="{ showModal: false }">
                            <a href="{{ route('admin.menu.edit', $menu->id) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-full transition" title="Edit">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            
                            <!-- Hapus Button -->
                            <button @click="showModal = true" class="p-2 text-red-600 hover:bg-red-50 rounded-full transition" title="Hapus">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>

                            <!-- Confirm Modal Alpine -->
                            <div x-show="showModal" 
                                 class="fixed inset-0 z-50 overflow-y-auto" 
                                 aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">
                                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                    <div x-show="showModal" x-transition.opacity class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showModal = false"></div>

                                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                                    <div x-show="showModal" 
                                         x-transition:enter="ease-out duration-300" 
                                         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                                         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                                         x-transition:leave="ease-in duration-200" 
                                         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                                         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                                         class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                            <div class="sm:flex sm:items-start">
                                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                                    <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                    </svg>
                                                </div>
                                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Hapus Menu</h3>
                                                    <div class="mt-2">
                                                        <p class="text-sm text-gray-500">Apakah Anda yakin ingin menghapus menu "{{ $menu->name }}"? Tindakan ini tidak dapat dibatalkan dan akan menghapus data terkait beserta gambarnya.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="bg-gray-50 px-4 py-3 sm:px-6 flex flex-row-reverse">
                                            <button @click="showModal = false; $wire.delete({{ $menu->id }})" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                                                Hapus
                                            </button>
                                            <button @click="showModal = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                                Batal
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Modal -->
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center bg-white rounded-lg shadow-sm border border-gray-200">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada menu</h3>
                <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan menu baru.</p>
                <div class="mt-6">
                    <a href="{{ route('admin.menu.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        + Tambah Menu
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $menus->links() }}
    </div>
</div>
