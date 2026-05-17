<div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
    <div class="sm:flex sm:items-center sm:justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Daftar Menu</h1>
        <a href="{{ route('admin.menu.create') }}" class="mt-4 sm:mt-0 inline-flex items-center justify-center rounded-md border border-transparent bg-orange-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-orange-700">Tambah Menu</a>
    </div>

    @if (session()->has('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <ul role="list" class="divide-y divide-gray-200">
            @forelse ($menus as $menu)
                <li class="p-4 sm:px-6 flex justify-between items-center">
                    <div>
                        <p class="text-sm font-medium text-orange-600 truncate">{{ $menu->name }}</p>
                        <p class="text-sm text-gray-500">{{ $menu->category->name }} | Rp {{ number_format($menu->price, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $menu->is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $menu->is_available ? 'Tersedia' : 'Habis' }}
                        </span>
                    </div>
                </li>
            @empty
                <li class="p-4 text-center text-gray-500">Belum ada menu.</li>
            @endforelse
        </ul>
    </div>
</div>
