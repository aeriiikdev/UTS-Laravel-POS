{{-- home.blade.php / Dashboard --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Dashboard POS</h2>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="mb-0">Penjualan Hari Ini</h6>
                            <h3 class="mt-2">Rp {{ number_format(\App\Models\Transaction::whereDate('created_at', today())->sum('total'), 0, ',', '.') }}</h3>
                        </div>
                        <div>
                            <i class="fas fa-money-bill-wave fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="mb-0">Transaksi Hari Ini</h6>
                            <h3 class="mt-2">{{ \App\Models\Transaction::whereDate('created_at', today())->count() }}</h3>
                        </div>
                        <div>
                            <i class="fas fa-shopping-cart fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="mb-0">Total Produk</h6>
                            <h3 class="mt-2">{{ \App\Models\Product::count() }}</h3>
                        </div>
                        <div>
                            <i class="fas fa-boxes fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="mb-0">Stok Menipis</h6>
                            <h3 class="mt-2">{{ \App\Models\Product::where('stock', '<', 10)->count() }}</h3>
                        </div>
                        <div>
                            <i class="fas fa-exclamation-triangle fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Access -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Akses Cepat</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('pos.index') }}" class="btn btn-primary btn-lg w-100">
                                <i class="fas fa-cash-register fa-2x d-block mb-2"></i>
                                Kasir
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('products.index') }}" class="btn btn-info btn-lg w-100">
                                <i class="fas fa-box fa-2x d-block mb-2"></i>
                                Produk
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('pos.history') }}" class="btn btn-success btn-lg w-100">
                                <i class="fas fa-history fa-2x d-block mb-2"></i>
                                Riwayat
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('categories.index') }}" class="btn btn-warning btn-lg w-100">
                                <i class="fas fa-tags fa-2x d-block mb-2"></i>
                                Kategori
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Transaksi Terbaru</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Invoice</th>
                                    <th>Waktu</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(\App\Models\Transaction::latest()->take(5)->get() as $transaction)
                                <tr>
                                    <td>{{ $transaction->invoice_number }}</td>
                                    <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                    <td>Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
                                    <td><span class="badge bg-success">Selesai</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Produk Stok Menipis</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach(\App\Models\Product::where('stock', '<', 10)->take(5)->get() as $product)
                        <li class="list-group-item d-flex justify-content-between">
                            <span>{{ $product->name }}</span>
                            <span class="badge bg-danger">{{ $product->stock }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection