<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Label - {{ $table['table_number'] }}</title>
    <style>
        @page {
            size: 100mm 100mm;
            margin: 0;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100mm;
            width: 100mm;
            background-color: #fff;
            color: #333;
        }
        .label-container {
            width: 90%;
            height: 90%;
            border: 2px solid #ea580c; /* Orange border */
            border-radius: 12px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 10px;
            box-sizing: border-box;
            background-color: #fffaf5; /* Sangat muda orange */
        }
        .logo-placeholder {
            font-size: 24px;
            margin-bottom: 5px;
        }
        .brand-name {
            font-size: 16px;
            font-weight: bold;
            color: #ea580c;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .table-number {
            font-size: 28px;
            font-weight: 900;
            margin-bottom: 10px;
            color: #111;
        }
        .qr-container {
            width: 150px;
            height: 150px;
            margin: 0 auto 10px;
            background: #fff;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .qr-container img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        .no-qr-text {
            display: flex;
            height: 100%;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            color: #999;
            font-style: italic;
        }
        .instruction-text {
            font-size: 14px;
            font-weight: bold;
            color: #555;
            background: #ffe4e6; /* light rose */
            padding: 4px 12px;
            border-radius: 12px;
        }
    </style>
</head>
<body>

    <div class="label-container">
        <div class="logo-placeholder">🌶️</div>
        <div class="brand-name">Warung Seblak Digital</div>
        
        <div class="table-number">{{ $table['table_number'] }}</div>
        
        <div class="qr-container">
            @if($table['qr_code_path'])
                <img src="{{ Storage::url($table['qr_code_path']) }}" alt="QR Code Meja">
            @else
                <div class="no-qr-text">QR belum digenerate</div>
            @endif
        </div>
        
        <div class="instruction-text">
            SCAN UNTUK PESAN
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
            window.onafterprint = function() {
                window.close();
            };
        };
    </script>
</body>
</html>
