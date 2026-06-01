<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Warung Seblak Digital</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans text-gray-900 antialiased bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="w-full sm:max-w-md px-6 py-8 bg-white shadow-md overflow-hidden sm:rounded-lg">
        <div class="mb-6 text-center">
            <h1 class="text-3xl font-extrabold text-amber-600">Warung Seblak</h1>
            <p class="text-sm text-gray-500 mt-2">Silakan login untuk melanjutkan</p>
        </div>
        
        {{ $slot }}
    </div>

    @livewireScripts
</body>
</html>
