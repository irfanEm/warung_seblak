@extends('layouts.customer')

@section('title', 'Meja Tidak Valid - Warung Seblak Digital')

@section('content')
<div class="flex flex-col items-center justify-center py-12 px-4 text-center">
    <!-- Icon Warning Shield -->
    <div class="w-20 h-20 bg-red-50 rounded-full flex items-center justify-center text-red-500 mb-6 border border-red-100 shadow-sm animate-pulse">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-10 h-10">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
        </svg>
    </div>
    
    <h2 class="text-base font-black text-gray-800 tracking-tight leading-snug">Kode QR Meja Tidak Valid</h2>
    <p class="text-[11px] text-gray-400 mt-2.5 leading-relaxed max-w-xs">
        Maaf, kode QR yang Anda pindai tidak terdaftar, telah kedaluwarsa, atau meja sedang dinonaktifkan sementara oleh pihak kasir/admin.
    </p>
    
    <!-- Action Advice Box -->
    <div class="mt-8 bg-gray-50 border border-gray-100 rounded-2xl p-4 w-full">
        <h3 class="text-xs font-bold text-gray-700">Silakan Lakukan Hal Berikut:</h3>
        <ul class="text-[10px] text-gray-500 text-left list-disc list-inside mt-2.5 space-y-1.5 leading-relaxed">
            <li>Pastikan Anda memindai stiker QR yang terpasang resmi di atas meja.</li>
            <li>Mintalah bantuan pelayan atau datangi langsung meja kasir kami.</li>
        </ul>
    </div>
    
    <!-- Action Button -->
    <a href="{{ Route::has('customer.menu') ? route('customer.menu') : '#' }}" 
       class="mt-8 block w-full py-3.5 px-6 bg-orange-500 hover:bg-orange-600 text-white text-xs font-black uppercase tracking-wider rounded-2xl shadow-lg shadow-orange-500/10 hover:shadow-orange-500/20 text-center active:scale-[0.98] transition-all duration-200">
        Kembali ke Beranda Menu
    </a>
</div>
@endsection
