@extends('layouts.customer')

@section('title', 'Menu - Warung Seblak Digital')
@section('meta_description', 'Pesan Seblak Lezat Favorit Anda Secara Online dengan Mudah & Cepat di Warung Seblak Digital.')

@section('content')
    @if(session()->has('table_number'))
        <!-- Konfirmasi Meja Sticky / Top Indicator -->
        <div class="mb-4 bg-orange-50 border border-orange-150 rounded-2xl p-3.5 flex items-center justify-between shadow-[0_2px_4px_rgba(249,115,22,0.03)]">
            <div class="flex items-center space-x-2.5">
                <span class="text-lg">🍽️</span>
                <div>
                    <p class="text-[9px] text-gray-400 font-bold uppercase tracking-wider">Anda Memesan Dari</p>
                    <p class="text-xs font-extrabold text-gray-800 mt-0.5">{{ session('table_number') }}</p>
                </div>
            </div>
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-black bg-orange-100 text-orange-600 border border-orange-200/50">
                <span class="w-1.5 h-1.5 bg-orange-500 rounded-full mr-1.5 animate-pulse"></span>
                Terhubung
            </span>
        </div>
    @endif

    <livewire:customer.menu-list />
    <livewire:customer.cart />
@endsection
