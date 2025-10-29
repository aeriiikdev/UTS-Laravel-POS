{{-- resources/views/categories/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col-md-6">
            <h2>Manajemen Kategori</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('categories.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Kategori
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

    <!-- Search & Filter -->
    <div class="card mb-3">
        <div class="card-body">
            <form action="{{ route('categories.index') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Cari Kategori</label>
                        <input type="text" name="search" class="form-control" 
                               placeholder="Nama atau Deskripsi..." 
                               value="{{ request('search') }}">
                    </div>
                    
                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">Semua</option>
                            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">Urutkan</label>
                        <select name="sort_by" class="form-select">
                            <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Terbaru</option>
                            <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Nama</option>
                            <option value="products_count" {{ request('sort_by') == 'products_count' ? 'selected' : '' }}>Jumlah Produk</option>
                        </select>
                    </div>
                    
                    <div class="col-md-2">
                        <label class="form-label">Per Halaman</label>
                        <select name="per_page" class="form-select">
                            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        </select>
                    </div>
                    
                    <div class="col-md-1">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-2">
                    <div class="col-12">
                        <a href="{{ route('categories.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-redo"></i> Reset Filter
                        </a>
                        <span class="text-muted ms-2">
                            Menampilkan {{ $categories->count() }} dari {{ $categories->total() }} kategori
                        </span>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Categories Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th style="width: 5%">No</th>
                            <th style="width: 25%">Nama Kategori</th>
                            <th style="width: 35%">Deskripsi</th>
                            <th style="width: 15%" class="text-center">Jumlah Produk</th>
                            <th style="width: 10%" class="text-center">Status</th>
                            <th style="width: 10%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $index => $category)
                        <tr>
                            <td>{{ $categories->firstItem() + $index }}</td>
                            <td><strong>{{ $category->name }}</strong></td>
                            <td>{{ $category->description ?? '-' }}</td>
                            <td class="text-center">
                                <span class="badge bg-info">{{ $category->products_count }} produk</span>
                            </td>
                            <td class="text-center">
                                @if($category->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('categories.edit', $category) }}" 
                                   class="btn btn-sm btn-warning me-1" 
                                   title="Edit">
                                    <i class="fas fa-edit"></i> 
                                </a>
                                <button type="button" 
                                        class="btn btn-sm btn-danger" 
                                        onclick="deleteCategory({{ $category->id }})"
                                        title="Hapus"
                                        {{ $category->products_count > 0 ? 'disabled' : '' }}>
                                    <i class="fas fa-trash"></i> 
                                </button>
                                
                                <form id="delete-form-{{ $category->id }}" 
                                      action="{{ route('categories.destroy', $category) }}" 
                                      method="POST" 
                                      style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Tidak ada kategori ditemukan</p>
                                @if(request()->hasAny(['search', 'status']))
                                    <a href="{{ route('categories.index') }}" class="btn btn-sm btn-primary">
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
            @if($categories->hasPages())
            <div class="d-flex justify-content-center mt-3">
                <nav aria-label="Page navigation">
                    {{ $categories->links('pagination::bootstrap-5') }}
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
                    <h6 class="card-subtitle mb-2">Total Kategori</h6>
                    <h2 class="card-title mb-0">{{ $stats['total'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2">Kategori Aktif</h6>
                    <h2 class="card-title mb-0">{{ $stats['active'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2">Kategori Nonaktif</h6>
                    <h2 class="card-title mb-0">{{ $stats['inactive'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2">Punya Produk</h6>
                    <h2 class="card-title mb-0">{{ $stats['with_products'] }}</h2>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function deleteCategory(id) {
    if (confirm('Yakin ingin menghapus kategori ini?')) {
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
</style>
@endpush
@endsection