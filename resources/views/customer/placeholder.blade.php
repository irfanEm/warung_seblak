<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Warung Seblak - Meja {{ $table->table_number }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen">
    
    <div class="max-w-md w-full bg-white rounded-xl shadow-lg p-8 text-center m-4">
        <div class="w-20 h-20 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Selamat Datang!</h1>
        <p class="text-gray-600 mb-6">
            Anda terhubung ke <span class="font-bold text-gray-900">Meja {{ $table->table_number }}</span>.<br>
            Halaman Menu interaktif akan segera hadir di sini. Silakan tunggu atau hubungi pelayan kami.
        </p>
        
        <div class="bg-gray-50 rounded-lg p-4 border border-gray-100 inline-block">
            <p class="text-xs text-gray-400 font-mono">Session Token: {{ session('table_id') }} - {{ $table->token }}</p>
        </div>
    </div>

</body>
</html>
