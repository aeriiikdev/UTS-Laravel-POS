@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-history"></i> Riwayat Transaksi</h2>
        <div>
            <button type="button" class="btn btn-success" onclick="exportExcel()">
                <i class="fas fa-file-excel"></i> Export Excel
            </button>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('reports.transactions') }}" id="filterForm" class="row g-3">
                <div class="col-md-2">
                    <label class="form-label">Cari Invoice</label>
                    <input type="text" name="search" class="form-control" 
                           placeholder="No. Invoice..." 
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Dari Tanggal</label>
                    <input type="date" name="start_date" class="form-control" 
                           value="{{ request('start_date') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Sampai Tanggal</label>
                    <input type="date" name="end_date" class="form-control" 
                           value="{{ request('end_date') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="all">Semua Status</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Metode Bayar</label>
                    <select name="payment_method" class="form-select">
                        <option value="all">Semua Metode</option>
                        <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Tunai</option>
                        <option value="debit" {{ request('payment_method') == 'debit' ? 'selected' : '' }}>Debit</option>
                        <option value="qris" {{ request('payment_method') == 'qris' ? 'selected' : '' }}>QRIS</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Filter
                        </button>
                    </div>
                </div>
                
                <div class="col-12">
                    <a href="{{ route('reports.transactions') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-redo"></i> Reset Filter
                    </a>
                    <span class="text-muted ms-2">
                        Menampilkan {{ $transactions->count() }} dari {{ $transactions->total() }} transaksi
                    </span>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-2">Total Transaksi</h6>
                            <h3 class="mb-0">{{ $summary['total_transactions'] }}</h3>
                        </div>
                        <i class="fas fa-receipt fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-2">Total Penjualan</h6>
                            <h3 class="mb-0">Rp {{ number_format($summary['total_amount'], 0, ',', '.') }}</h3>
                        </div>
                        <i class="fas fa-money-bill-wave fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-2">Penjualan Hari Ini</h6>
                            <h3 class="mb-0">Rp {{ number_format($summary['total_today'], 0, ',', '.') }}</h3>
                        </div>
                        <i class="fas fa-calendar-day fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th style="width: 5%">#</th>
                            <th>No Invoice</th>
                            <th>Tanggal & Waktu</th>
                            <th>Kasir</th>
                            <th class="text-end">Total</th>
                            <th class="text-center">Metode Bayar</th>
                            <th class="text-center">Pembayaran</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $index => $transaction)
                        <tr>
                            <td>{{ $transactions->firstItem() + $index }}</td>
                            <td>
                                <strong>{{ $transaction->transaction_code }}</strong>
                            </td>
                            <td>{{ $transaction->transaction_date->format('d/m/Y H:i') }}</td>
                            <td>{{ $transaction->user->name }}</td>
                            <td class="text-end">
                                <strong>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</strong>
                            </td>
                            <td class="text-center">
                                @if($transaction->payment_method == 'cash')
                                    <span class="badge bg-success">Tunai</span>
                                @elseif($transaction->payment_method == 'debit')
                                    <span class="badge bg-primary">Debit</span>
                                @elseif($transaction->payment_method == 'qris')
                                    <span class="badge bg-info">QRIS</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($transaction->payment_method) }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <small class="text-muted">
                                    Bayar: Rp {{ number_format($transaction->payment_amount, 0, ',', '.') }}<br>
                                    Kembali: Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}
                                </small>
                            </td>
                            <td class="text-center">
                                @if($transaction->status == 'completed')
                                    <span class="badge bg-success">Selesai</span>
                                @else
                                    <span class="badge bg-danger">Dibatalkan</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('pos.receipt', $transaction->id) }}" 
                                   class="btn btn-sm btn-info" 
                                   target="_blank"
                                   title="Cetak Struk">
                                    <i class="fas fa-print"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-0">Tidak ada transaksi ditemukan</p>
                                @if(request()->hasAny(['search', 'start_date', 'end_date', 'status', 'payment_method']))
                                    <a href="{{ route('reports.transactions') }}" class="btn btn-sm btn-primary mt-2">
                                        Reset Filter
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($transactions->hasPages())
            <div class="d-flex justify-content-center mt-3">
                {{ $transactions->links('pagination::bootstrap-5') }}
            </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
function exportExcel() {
    // Get current filter parameters
    const form = document.getElementById('filterForm');
    const formData = new FormData(form);
    const params = new URLSearchParams(formData);
    
    // Redirect to export URL with parameters
    window.location.href = "{{ route('reports.export-excel') }}?" + params.toString();
}
</script>
@endpush
@endsection