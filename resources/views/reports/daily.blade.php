@extends('layouts.app')

@section('content')
<div class="container-fluid" style="padding: 20px;">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2><i class="fas fa-calendar-day"></i> Laporan Harian</h2>
        </div>
        <div class="col-md-4 text-end">
            <form method="GET" class="d-flex gap-2" style="justify-content: flex-end;">
                <input type="date" name="date" class="form-control" value="{{ $date }}" onchange="this.form.submit()">
                <a href="{{ route('reports.daily') }}" class="btn btn-outline-secondary">Hari Ini</a>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h6 class="card-title">Total Penjualan</h6>
                    <h3>{{ number_format($totalSales, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h6 class="card-title">Jumlah Transaksi</h6>
                    <h3>{{ $totalTransactions }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h6 class="card-title">Total Pembayaran</h6>
                    <h3>Rp {{ number_format($totalPayment, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h6 class="card-title">Rata-rata Transaksi</h6>
                    <h3>Rp {{ number_format($totalTransactions > 0 ? $totalSales / $totalTransactions : 0, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="card">
        <div class="card-header">
            <h5>Detail Transaksi</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No. Invoice</th>
                        <th>Kasir</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Metode</th>
                        <th>Waktu</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                    <tr>
                        <td><strong>{{ $transaction->transaction_code }}</strong></td>
                        <td>{{ $transaction->user->name }}</td>
                        <td>
                            <span class="badge bg-info">{{ $transaction->transactionDetails->count() }} item</span>
                        </td>
                        <td>Rp {{ number_format((float)$transaction->total_amount, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge bg-secondary">{{ ucfirst($transaction->payment_method) }}</span>
                        </td>
                        <td>{{ $transaction->created_at->format('H:i:s') }}</td>
                        <td>
                            <a href="{{ route('pos.receipt', $transaction->id) }}" class="btn btn-sm btn-primary" target="_blank">
                                <i class="fas fa-receipt"></i> Struk
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-3">Tidak ada transaksi</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection