@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-history"></i> Riwayat Transaksi</h2>
        <div>
            <a href="{{ route('pos.index') }}" class="btn btn-primary me-2">
                <i class="fas fa-cash-register"></i> Kasir
            </a>
            <button class="btn btn-success" onclick="exportData()">
                <i class="fas fa-file-excel"></i> Export
            </button>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('pos.history') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Cari Invoice</label>
                        <input type="text" name="search" class="form-control" 
                               placeholder="No. Invoice..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Dari Tanggal</label>
                        <input type="date" name="start_date" class="form-control" 
                               value="{{ request('start_date', date('Y-m-01')) }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Sampai Tanggal</label>
                        <input type="date" name="end_date" class="form-control" 
                               value="{{ request('end_date', date('Y-m-d')) }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="all">Semua Status</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>
                                Selesai
                            </option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>
                                Dibatalkan
                            </option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Metode Bayar</label>
                        <select name="payment_method" class="form-select">
                            <option value="all">Semua Metode</option>
                            <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>
                                Tunai
                            </option>
                            <option value="card" {{ request('payment_method') == 'card' ? 'selected' : '' }}>
                                Kartu
                            </option>
                            <option value="e-wallet" {{ request('payment_method') == 'e-wallet' ? 'selected' : '' }}>
                                E-Wallet
                            </option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-primary">
                <div class="card-body text-center">
                    <i class="fas fa-receipt fa-2x text-primary mb-2"></i>
                    <h6 class="text-muted">Total Transaksi</h6>
                    <h3 class="mb-0">{{ $summary['total_transactions'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-success">
                <div class="card-body text-center">
                    <i class="fas fa-dollar-sign fa-2x text-success mb-2"></i>
                    <h6 class="text-muted">Total Penjualan</h6>
                    <h3 class="mb-0 text-success">
                        Rp {{ number_format($summary['total_amount'] ?? 0, 0, ',', '.') }}
                    </h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-info">
                <div class="card-body text-center">
                    <i class="fas fa-calendar-day fa-2x text-info mb-2"></i>
                    <h6 class="text-muted">Penjualan Hari Ini</h6>
                    <h3 class="mb-0 text-info">
                        Rp {{ number_format($summary['total_today'] ?? 0, 0, ',', '.') }}
                    </h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-warning">
                <div class="card-body text-center">
                    <i class="fas fa-chart-line fa-2x text-warning mb-2"></i>
                    <h6 class="text-muted">Rata-rata Transaksi</h6>
                    <h3 class="mb-0 text-warning">
                        Rp {{ number_format(($summary['total_transactions'] ?? 0) > 0 ? ($summary['total_amount'] ?? 0) / $summary['total_transactions'] : 0, 0, ',', '.') }}
                    </h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Transactions List -->
    <div class="card">
        <div class="card-header bg-white">
            <h5 class="mb-0">Daftar Transaksi</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">#</th>
                            <th width="15%">No Invoice</th>
                            <th width="15%">Tanggal & Waktu</th>
                            <th width="15%">Kasir</th>
                            <th width="12%">Total</th>
                            <th width="10%">Bayar</th>
                            <th width="10%">Pembayaran</th>
                            <th width="8%">Status</th>
                            <th width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $index => $transaction)
                        <tr>
                            <td>{{ $transactions->firstItem() + $index }}</td>
                            <td>
                                <strong class="text-primary">{{ $transaction->transaction_code }}</strong>
                            </td>
                            <td>
                                <div>{{ $transaction->transaction_date->format('d M Y') }}</div>
                                <small class="text-muted">{{ $transaction->transaction_date->format('H:i') }}</small>
                            </td>
                            <td>
                                <i class="fas fa-user-circle"></i> {{ $transaction->user->name }}
                            </td>
                            <td>
                                <strong>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</strong>
                            </td>
                            <td>
                                <div>Rp {{ number_format($transaction->payment_amount, 0, ',', '.') }}</div>
                                <small class="text-success">
                                    Kembali: Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}
                                </small>
                            </td>
                            <td>
                                @if($transaction->payment_method == 'cash')
                                    <span class="badge bg-success"><i class="fas fa-money-bill"></i> Tunai</span>
                                @elseif($transaction->payment_method == 'card')
                                    <span class="badge bg-primary"><i class="fas fa-credit-card"></i> Kartu</span>
                                @else
                                    <span class="badge bg-info"><i class="fas fa-wallet"></i> E-Wallet</span>
                                @endif
                            </td>
                            <td>
                                @if($transaction->status == 'completed')
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle"></i> Selesai
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        <i class="fas fa-times-circle"></i> Batal
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('pos.receipt', $transaction->id) }}" 
                                       class="btn btn-info" target="_blank" title="Cetak Struk">
                                        <i class="fas fa-print"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-0">Tidak ada transaksi ditemukan</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($transactions->hasPages())
            <div class="mt-4">
                {{ $transactions->appends(request()->query())->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<script>
function exportData() {
    // Implement export functionality
    alert('Fitur export sedang dalam pengembangan');
}
</script>
@endsection