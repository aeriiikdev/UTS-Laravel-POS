<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk - {{ $transaction->invoice_number }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f5f5;
            padding: 20px;
        }
        .receipt-container {
            max-width: 400px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            font-family: 'Courier New', monospace;
        }
        .receipt-header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        .receipt-header h2 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }
        .receipt-info {
            font-size: 12px;
            margin: 10px 0;
            line-height: 1.6;
        }
        .receipt-items {
            margin: 20px 0;
            border-top: 1px solid #ccc;
            border-bottom: 1px solid #ccc;
            padding: 15px 0;
        }
        .item-row {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            margin-bottom: 8px;
        }
        .item-name {
            flex: 1;
        }
        .item-qty {
            width: 40px;
            text-align: center;
        }
        .item-price {
            width: 70px;
            text-align: right;
        }
        .receipt-total {
            margin: 15px 0;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            margin-bottom: 5px;
        }
        .total-row.final {
            font-size: 14px;
            font-weight: bold;
            border-top: 2px solid #333;
            border-bottom: 2px solid #333;
            padding: 8px 0;
            margin: 10px 0;
        }
        .receipt-footer {
            text-align: center;
            font-size: 11px;
            margin-top: 20px;
            color: #666;
        }
        .buttons {
            margin-top: 20px;
            display: flex;
            gap: 10px;
        }
        .btn-custom {
            flex: 1;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
        }
        .btn-print {
            background-color: #007bff;
            color: white;
        }
        .btn-back {
            background-color: #6c757d;
            color: white;
        }
        @media print {
            body {
                background: white;
                padding: 0;
            }
            .receipt-container {
                box-shadow: none;
                max-width: 80mm;
                margin: 0;
                padding: 10px;
            }
            .buttons {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <!-- Header -->
        <div class="receipt-header">
            <h2>POS SYSTEM</h2>
            <div class="receipt-info">
                <strong>STRUK TRANSAKSI</strong>
            </div>
        </div>

        <!-- Transaction Info -->
        <div class="receipt-info">
            <div>No. Invoice: <strong>{{ $transaction->invoice_number }}</strong></div>
            <div>Tanggal: {{ $transaction->created_at->format('d/m/Y H:i') }}</div>
            <div>Kasir: {{ $transaction->user->name }}</div>
            <div>Metode: {{ ucfirst($transaction->payment_method) }}</div>
        </div>

        <!-- Items -->
        <div class="receipt-items">
            <div class="item-row" style="font-weight: bold; margin-bottom: 10px;">
                <span class="item-name">ITEM</span>
                <span class="item-qty">QTY</span>
                <span class="item-price">HARGA</span>
            </div>

            @foreach($transaction->transactionDetails as $detail)
            <div class="item-row">
                <span class="item-name">{{ $detail->product->name }}</span>
                <span class="item-qty">{{ $detail->quantity }}</span>
                <span class="item-price">Rp {{ number_format((float)$detail->subtotal, 0, ',', '.') }}</span>
            </div>
            @endforeach
        </div>

        <!-- Totals -->
        <div class="receipt-total">
            <?php
                $subtotal = $transaction->transactionDetails->sum(function($detail) {
                    return $detail->price * $detail->quantity;
                });
                $tax = $subtotal * 0.1;
                $total = $subtotal + $tax;
                $paid = $transaction->payment_amount ?? 0;
                $change = $paid - $total;
            ?>
            
            <div class="total-row">
                <span>Subtotal:</span>
                <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
            </div>

            <div class="total-row">
                <span>Pajak (10%):</span>
                <span>Rp {{ number_format($tax, 0, ',', '.') }}</span>
            </div>

            <div class="total-row final">
                <span>TOTAL:</span>
                <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>

            <div class="total-row">
                <span>Pembayaran:</span>
                <span>Rp {{ number_format($paid, 0, ',', '.') }}</span>
            </div>

            <div class="total-row">
                <span>Kembalian:</span>
                <span>Rp {{ number_format(max(0, $change), 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Footer -->
        <div class="receipt-footer">
            <p>Terima kasih atas pembelian Anda!</p>
            <p style="font-size: 10px; margin-top: 5px;">
                Tanggal: {{ now()->format('d/m/Y H:i:s') }}
            </p>
        </div>

        <!-- Buttons -->
        <div class="buttons">
            <button class="btn-custom btn-print" onclick="window.print()">
                🖨️ Cetak
            </button>
            <button class="btn-custom btn-back" onclick="window.location.href='{{ route('pos.index') }}'">
                ← Kembali
            </button>
        </div>
    </div>

    <script>
        // Auto print untuk thermal printer (opsional)
        // Uncomment jika ingin auto-print:
        // window.print();
    </script>
</body>
</html>