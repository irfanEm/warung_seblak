@extends('layouts.customer')

@section('title', 'Menu - Warung Seblak Digital')
@section('meta_description', 'Pesan Seblak Lezat Favorit Anda Secara Online dengan Mudah & Cepat di Warung Seblak Digital.')

@section('content')
    <!-- Daftar Menu Makanan & Topping -->
    <livewire:customer.menu-list />

    <!-- Bottom Sheet / Panel Keranjang Belanja -->
    <livewire:customer.cart />
@endsection
