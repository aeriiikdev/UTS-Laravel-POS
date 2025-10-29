@extends('layouts.app')

@section('content')
<div class="container-fluid" style="padding: 20px;">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2><i class="fas fa-box"></i> Laporan Produk & Stok</h2>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h6 class="card-title">Total Produk</h6>
                    <h3>{{ $totalProducts }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h6 class="card-title">Stok Terbatas</h6>
                    <h3>{{ $lowStock }}</h3>
                    <small>(&lt; 10 unit)</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger">
                <div class="card-body">
                    <h6 class="card-title">Stok Habis</h6>
                    <h3>{{ $outOfStock }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h6 class="card-title">Stok Aman</h6>
                    <h3>{{ $totalProducts - $lowStock - $outOfStock }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Kategori</label>
                    <select name="category_id" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Urutkan</label>
                    <select name="sort" class="form-select" onchange="this.form.submit()">
                        <option value="stock" {{ $sort == 'stock' ? 'selected' : '' }}>Stok (Rendah ke Tinggi)</option>
                        <option value="name" {{ $sort == 'name' ? 'selected' : '' }}>Nama (A-Z)</option>
                        <option value="price" {{ $sort == 'price' ? 'selected' : '' }}>Harga (Tinggi ke Rendah)</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">&nbsp;</label>
                    <a href="{{ route('reports.products') }}" class="btn btn-outline-secondary w-100">Reset Filter</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Kode</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td><code>{{ $product->code }}</code></td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->category->name }}</td>
                        <td>Rp {{ number_format((float)$product->price, 0, ',', '.') }}</td>
                        <td><strong>{{ $product->stock }}</strong> unit</td>
                        <td>
                            @if($product->stock == 0)
                                <span class="badge bg-danger">Habis</span>
                            @elseif($product->stock < 10)
                                <span class="badge bg-warning">Terbatas</span>
                            @else
                                <span class="badge bg-success">Aman</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-3">Tidak ada produk</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection