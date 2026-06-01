@php
    $message = null;
    $type = 'success';
    
    if (session()->has('success')) {
        $message = session('success');
        $type = 'success';
    } elseif (session()->has('error')) {
        $message = session('error');
        $type = 'error';
    } elseif (session()->has('message')) {
        $message = session('message');
        $type = session('message_type', 'success');
    }
@endphp

@if ($message)
    <div x-data="{ show: true }" 
         x-show="show" 
         x-init="setTimeout(() => show = false, 3000)"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-[-20px]"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-[-20px]"
         class="fixed top-4 right-4 z-50 rounded-md p-4 {{ $type === 'success' ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-red-100 text-red-800 border border-red-200' }}">
        <div class="flex items-center space-x-3">
            <span class="font-medium text-sm">{{ $message }}</span>
            <button @click="show = false" class="text-gray-500 hover:text-gray-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
    </div>
@endif
