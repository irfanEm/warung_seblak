<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk - {{ $order->order_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 11px;
            line-height: 1.4;
            color: #000;
            width: 80mm;
            padding: 5mm;
        }

        .header {
            text-align: center;
            margin-bottom: 4mm;
            border-bottom: 1px dashed #000;
            padding-bottom: 3mm;
        }

        .header h1 {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 2mm;
        }

        .header p {
            font-size: 10px;
        }

        .info {
            margin-bottom: 3mm;
            border-bottom: 1px dashed #000;
            padding-bottom: 3mm;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1mm;
        }

        .info-row .label {
            flex: 0 0 auto;
        }

        .info-row .value {
            flex: 1;
            text-align: right;
        }

        .items {
            margin-bottom: 3mm;
            border-bottom: 1px dashed #000;
            padding-bottom: 3mm;
        }

        .item {
            margin-bottom: 2mm;
        }

        .item-name {
            font-weight: bold;
        }

        .item-detail {
            font-size: 10px;
            color: #333;
        }

        .item-line {
            display: flex;
            justify-content: space-between;
        }

        .toppings {
            font-size: 10px;
            color: #555;
            margin-left: 3mm;
        }

        .totals {
            margin-bottom: 3mm;
            border-bottom: 1px dashed #000;
            padding-bottom: 3mm;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1mm;
        }

        .total-row.grand {
            font-weight: bold;
            font-size: 13px;
            margin-top: 2mm;
            padding-top: 2mm;
            border-top: 1px solid #000;
        }

        .footer {
            text-align: center;
            margin-top: 5mm;
            font-size: 10px;
        }

        @media print {
            body {
                width: 80mm;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $outlet->name ?? 'Warung Seblak' }}</h1>
        <p>{{ $outlet->address ?? '' }}</p>
        <p>Struk Pembelian</p>
    </div>

    <div class="info">
        <div class="info-row">
            <span class="label">No. Order</span>
            <span class="value">{{ $order->order_number }}</span>
        </div>
        <div class="info-row">
            <span class="label">Tanggal</span>
            <span class="value">{{ $order->created_at->format('d/m/Y H:i') }}</span>
        </div>
        <div class="info-row">
            <span class="label">Tipe</span>
            <span class="value">{{ $order->type === 'dine_in' ? 'Dine-in' : 'Takeaway' }}</span>
        </div>
        @if ($order->customer_name)
            <div class="info-row">
                <span class="label">Pelanggan</span>
                <span class="value">{{ $order->customer_name }}</span>
            </div>
        @endif
        <div class="info-row">
            <span class="label">Kasir</span>
            <span class="value">{{ auth()->user()->name ?? '-' }}</span>
        </div>
    </div>

    <div class="items">
        @foreach ($order->orderItems as $item)
            <div class="item">
                <div class="item-line">
                    <span class="item-name">{{ $item->item_name_snapshot }}</span>
                </div>
                <div class="item-line item-detail">
                    <span>{{ $item->quantity }} x Rp{{ formatRupiah($item->price) }}</span>
                    <span>Rp{{ formatRupiah($item->subtotal) }}</span>
                </div>
                @if ($item->toppings->isNotEmpty())
                    <div class="toppings">
                        + {{ $item->toppings->pluck('topping_name')->join(', ') }}
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    <div class="totals">
        <div class="total-row">
            <span>Subtotal</span>
            <span>Rp{{ formatRupiah($order->subtotal) }}</span>
        </div>
        @if ($order->tax > 0)
            <div class="total-row">
                <span>Pajak</span>
                <span>Rp{{ formatRupiah($order->tax) }}</span>
            </div>
        @endif
        @if ($order->delivery_fee > 0)
            <div class="total-row">
                <span>Ongkir</span>
                <span>Rp{{ formatRupiah($order->delivery_fee) }}</span>
            </div>
        @endif
        @if ($order->discount > 0)
            <div class="total-row">
                <span>Diskon</span>
                <span>-Rp{{ formatRupiah($order->discount) }}</span>
            </div>
        @endif
        <div class="total-row grand">
            <span>TOTAL</span>
            <span>Rp{{ formatRupiah($order->total) }}</span>
        </div>
    </div>

    <div class="footer">
        <p>Terima kasih atas kunjungan Anda!</p>
        <p>{{ $order->created_at->format('d F Y') }}</p>
    </div>
</body>
</html>
