@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col-md-6">
            <h2>Manajemen Produk</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('products.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Produk
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Filter & Search -->
    <div class="card mb-3">
        <div class="card-body">
            <form action="{{ route('products.index') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Cari Produk</label>
                        <input type="text" name="search" class="form-control" 
                               placeholder="Nama atau Kode..." 
                               value="{{ request('search') }}">
                    </div>
                    
                    <div class="col-md-2">
                        <label class="form-label">Kategori</label>
                        <select name="category_id" class="form-select">
                            <option value="">Semua</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}" 
                                    {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">Semua</option>
                            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                    
                    <div class="col-md-2">
                        <label class="form-label">Stok</label>
                        <select name="stock_status" class="form-select">
                            <option value="">Semua</option>
                            <option value="empty" {{ request('stock_status') == 'empty' ? 'selected' : '' }}>Habis</option>
                            <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>Menipis</option>
                            <option value="available" {{ request('stock_status') == 'available' ? 'selected' : '' }}>Tersedia</option>
                        </select>
                    </div>
                
                    <div class="col-md-1">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-2">
                    <div class="col-12">
                        <a href="{{ route('products.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-redo"></i> Reset Filter
                        </a>
                        <span class="text-muted ms-2">
                            Menampilkan {{ $products->count() }} dari {{ $products->total() }} produk
                        </span>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Products Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th style="width: 60px">Gambar</th>
                            <th>Kode</th>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th class="text-center">Stok</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr>
                            <td>
                                @if($product->image && file_exists(public_path($product->image)))
                                    <img src="{{ asset($product->image) }}" 
                                         alt="{{ $product->name }}" 
                                         class="img-thumbnail"
                                         style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                    <div class="bg-secondary d-flex align-items-center justify-content-center" 
                                         style="width: 50px; height: 50px;">
                                        <i class="fas fa-image text-white"></i>
                                    </div>
                                @endif
                            </td>
                            <td><small>{{ $product->code }}</small></td>
                            <td><strong>{{ $product->name }}</strong></td>
                            <td>
                                <span class="badge bg-info">
                                    {{ $product->category->name }}
                                </span>
                            </td>
                            <td><strong>Rp {{ number_format($product->price, 0, ',', '.') }}</strong></td>
                            <td class="text-center">
                                @if($product->stock == 0)
                                    <span class="badge bg-danger">{{ $product->stock }}</span>
                                @elseif($product->stock < 10)
                                    <span class="badge bg-warning text-dark">{{ $product->stock }}</span>
                                @else
                                    <span class="badge bg-success">{{ $product->stock }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($product->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('products.edit', $product) }}" 
                                   class="btn btn-sm btn-warning me-1"
                                   title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" 
                                        class="btn btn-sm btn-danger" 
                                        onclick="deleteProduct({{ $product->id }})"
                                        title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                                
                                <form id="delete-form-{{ $product->id }}" 
                                      action="{{ route('products.destroy', $product) }}" 
                                      method="POST" 
                                      style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Tidak ada produk ditemukan</p>
                                @if(request()->hasAny(['search', 'category_id', 'status', 'stock_status']))
                                    <a href="{{ route('products.index') }}" class="btn btn-sm btn-primary">
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
            @if($products->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap">
                <div class="mb-2 mb-md-0">
                    <form action="{{ route('products.index') }}" method="GET" class="d-inline-block">
                        @foreach(request()->except('per_page') as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        <select name="per_page" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
                            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10 per halaman</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 per halaman</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 per halaman</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 per halaman</option>
                        </select>
                    </form>
                </div>
                <nav aria-label="Page navigation">
                    {{ $products->links('pagination::bootstrap-5') }}
                </nav>
            </div>
            @endif
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2">Total Produk</h6>
                    <h2 class="card-title mb-0">{{ $stats['total'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2">Produk Aktif</h6>
                    <h2 class="card-title mb-0">{{ $stats['active'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2">Stok Menipis</h6>
                    <h2 class="card-title mb-0">{{ $stats['low_stock'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2">Stok Habis</h6>
                    <h2 class="card-title mb-0">{{ $stats['empty_stock'] }}</h2>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function deleteProduct(id) {
    if (confirm('Yakin ingin menghapus produk ini?')) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
@endpush

@push('styles')
<style>
.pagination {
    margin-bottom: 0;
}
.pagination .page-link {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
}
.page-item.active .page-link {
    background-color: #0d6efd;
    border-color: #0d6efd;
}
</style>
@endpush
@endsection