<div class="px-4 py-10 flex flex-col items-center justify-center min-h-[60vh] text-center">
    
    @if($status === 'success')
        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mb-6">
            <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        </div>
    @elseif($status === 'pending')
        <div class="w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center mb-6">
            <svg class="w-10 h-10 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
    @else
        <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mb-6">
            <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </div>
    @endif

    <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $title }}</h1>
    <p class="text-gray-500 text-sm mb-2">{{ $message }}</p>
    
    @if($trackingCode)
    <p class="text-xs text-gray-400 mb-8 font-mono">Kode Pesanan: {{ $trackingCode }}</p>
    @endif

    <a href="{{ route('customer.menu') }}" class="w-full max-w-xs bg-amber-600 hover:bg-amber-700 text-white font-bold py-3.5 px-4 rounded-xl shadow-md transition active:scale-[0.98]">
        Kembali ke Menu
    </a>
</div>
