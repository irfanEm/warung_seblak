<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Label Meja {{ $table->table_number }}</title>
    <style>
        /* 
         * KUNCI 1: Mengatur ukuran dan margin halaman secara eksplisit.
         * Ukuran 283.46pt = 10cm. Margin 0 menghindari halaman kosong ekstra.
         */
        @page {
            size: 283.46pt 283.46pt;
            margin: 0;
        }
        
        /* 
         * KUNCI 2: Hilangkan semua height: 100%, min-height, atau padding berlebih 
         * pada body yang sering memicu DomPDF membuat halaman kedua.
         */
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 20px; /* Padding dipindahkan ke body agar konten tidak mepet tepi */
            color: #1f2937;
            background-color: #ffffff;
            text-align: center;
        }

        /* 
         * KUNCI 3: Container tanpa width/height 100% agar fit-content.
         * Display block sederhana.
         */
        .container {
            display: block;
            margin: 0 auto;
        }

        .brand-name {
            margin: 0 0 10px 0; /* Margin wajar, jangan terlalu besar */
            font-size: 16px;
            font-weight: 600;
            color: #4b5563;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .table-identity {
            margin: 0 0 15px 0;
            font-size: 36px; /* Disesuaikan agar tebal tapi tidak terlalu memakan tempat */
            font-weight: bold;
            color: #111827;
        }

        /* 
         * Box putih tempat QR Code diletakkan.
         * Margin auto untuk center horizontal, ukuran kotak dikurangi sedikit 
         * agar lebih aman dari overflow vertikal (180px -> 170px)
         */
        .qr-box {
            margin: 0 auto;
            padding: 10px;
            background: white;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            width: 150px; 
            height: 150px;
        }

        /* 
         * Gambar tidak memakai height/width % agar DomPDF tidak bingung
         */
        .qr-image {
            width: 150px;
            height: 150px;
        }

        .instruction {
            margin: 15px 0 0 0;
            font-size: 11px;
            color: #6b7280;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="container">
        
        <!-- 1. Header Brand (Logo Fallback) -->
        @if(file_exists(public_path('images/logo.png')))
            <img src="{{ public_path('images/logo.png') }}" style="max-width: 45px; margin-bottom: 5px;" alt="Logo">
        @else
            <!-- Nama sesuai dengan yang di-edit user (Ibun) -->
            <h2 class="brand-name">Warung Seblak Ibun</h2>
        @endif

        <!-- 2. Identitas Meja -->
        <h1 class="table-identity">MEJA {{ $table->table_number }}</h1>

        <!-- 3. QR Code (Dominan & Proporsional) -->
        <div class="qr-box">
            @if($table->qr_code_image_path && file_exists(public_path('storage/' . $table->qr_code_image_path)))
                <img src="{{ public_path('storage/' . $table->qr_code_image_path) }}" class="qr-image" alt="QR Code">
            @else
                <p style="margin-top: 60px; font-size: 12px; color: red; margin-bottom: 0;">QR Belum Digenerate</p>
            @endif
        </div>

        <!-- 4. Instruksi Scan -->
        <p class="instruction">Scan untuk melihat menu & memesan</p>

    </div>
</body>
</html>
