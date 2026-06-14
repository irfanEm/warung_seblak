@props([
    'icon' => '📭',
    'title' => 'Tidak Ada Data',
    'description' => 'Belum ada data yang tersedia.',
])

<div class="flex flex-col items-center justify-center text-center p-12 bg-white rounded-2xl border border-gray-200 max-w-lg mx-auto shadow-sm" {{ $attributes }}>
    <span class="text-4xl mb-4">{{ $icon }}</span>
    <h4 class="text-base font-bold text-gray-800">{{ $title }}</h4>
    <p class="text-xs text-gray-500 mt-1 max-w-xs">{{ $description }}</p>
    {{ $slot }}
</div>
