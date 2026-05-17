<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Label Meja {{ $table->table_number }}</title>
    <style>
        @page {
            margin: 0;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
            text-align: center;
            width: 100%;
            height: 100%;
            display: block;
        }
        .container {
            width: 283.46pt; /* 10cm */
            height: 283.46pt; /* 10cm */
            box-sizing: border-box;
            padding: 20pt;
            border: 2px solid #000;
        }
        h1 {
            font-size: 24pt;
            margin: 10pt 0 5pt 0;
            color: #333;
            text-transform: uppercase;
        }
        .subtitle {
            font-size: 10pt;
            color: #666;
            margin-bottom: 15pt;
        }
        .qr-wrapper {
            margin: 0 auto;
            width: 150pt;
            height: 150pt;
            padding: 5pt;
            border: 1px solid #ccc;
            border-radius: 8pt;
        }
        .qr-image {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        .footer {
            margin-top: 10pt;
            font-size: 8pt;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>MEJA {{ $table->table_number }}</h1>
        <div class="subtitle">Warung Seblak Digital</div>
        
        <div class="qr-wrapper">
            @if($table->qr_code_image_path)
                <!-- DOMPDF memerlukan path absolut untuk gambar lokal jika menggunakan disk public -->
                <img src="{{ public_path('storage/' . $table->qr_code_image_path) }}" class="qr-image" alt="QR Code">
            @else
                <p style="margin-top: 60pt; color: red;">QR Belum Digenerate</p>
            @endif
        </div>
        
        <div class="footer">Scan untuk melihat menu & pesan</div>
    </div>
</body>
</html>
