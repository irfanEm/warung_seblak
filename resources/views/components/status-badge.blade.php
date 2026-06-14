@props(['status'])

@php
    $statusColors = [
        'pending' => 'bg-gray-100 text-gray-800',
        'payment_pending' => 'bg-yellow-100 text-yellow-800',
        'paid' => 'bg-blue-100 text-blue-800',
        'confirmed' => 'bg-indigo-100 text-indigo-800',
        'preparing' => 'bg-orange-100 text-orange-800',
        'ready' => 'bg-green-100 text-green-800',
        'completed' => 'bg-emerald-100 text-emerald-800',
        'cancelled' => 'bg-red-100 text-red-800',
    ];
    $statusLabels = [
        'pending' => 'Pending',
        'payment_pending' => 'Menunggu Bayar',
        'paid' => 'Lunas',
        'confirmed' => 'Dikonfirmasi',
        'preparing' => 'Disiapkan',
        'ready' => 'Siap',
        'completed' => 'Selesai',
        'cancelled' => 'Dibatalkan',
    ];
    $colorClass = $statusColors[$status] ?? 'bg-gray-100 text-gray-800';
    $label = $statusLabels[$status] ?? ucfirst($status);
@endphp

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $colorClass }}" {{ $attributes }}>
    {{ $label }}
</span>
