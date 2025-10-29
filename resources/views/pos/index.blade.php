@extends('layouts.app')

@section('content')
<div class="container-fluid" style="padding: 20px;">
    <div class="row">
        <!-- Product List Section -->
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-3">Daftar Produk</h5>
                    
                    <!-- Search with Icon -->
                    <div class="input-group mb-3">
                        <span class="input-group-text" style="cursor: pointer; background-color: white; border-right: none;" onclick="searchProduct()" title="Klik untuk cari ">
                            <i class="fas fa-search" style="color: #007bff; font-size: 1.1rem;"></i>
                        </span>
                        <input type="text" id="searchProduct" class="form-control" placeholder="Cari produk (nama/kode)..." style="border-left: none;">
                    </div>

                    <!-- Category Dropdown -->
                    <div class="dropdown">
                        <button class="btn btn-outline-primary dropdown-toggle w-100 text-start" type="button" 
                                id="categoryDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-filter"></i> Kategori: Semua
                        </button>
                        <ul class="dropdown-menu w-100" aria-labelledby="categoryDropdown" id="categoryList">
                            <li>
                                <a class="dropdown-item category-filter active" href="#" data-category="all" onclick="filterCategory('all', event)">
                                    <i class="fas fa-check"></i> Semua Kategori
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            @foreach($categories as $category)
                            <li>
                                <a class="dropdown-item category-filter" href="#" data-category="{{ $category->id }}" onclick="filterCategory({{ $category->id }}, event)">
                                    {{ $category->name }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="card-body" style="max-height: 70vh; overflow-y: auto;">
                    <!-- Products Grid -->
                    <div class="row" id="productGrid">
                        @foreach($products as $product)
                        <div class="col-md-4 col-sm-6 mb-3 product-item" data-category="{{ $product->category_id }}"
                             data-name="{{ strtolower($product->name) }}" data-code="{{ strtolower($product->code) }}">
                            <div class="card h-100 product-card shadow-sm" style="cursor: pointer; border: 1px solid #e0e0e0; transition: all 0.3s;" 
                                 onclick="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }}, {{ $product->stock }})">
                                <img src="{{ $product->image ? asset($product->image) : asset('images/no-image.png') }}" 
                                     class="card-img-top" alt="{{ $product->name }}" 
                                     style="height: 120px; object-fit: cover;">
                                <div class="card-body p-2">
                                    <h6 class="card-title mb-1">{{ $product->name }}</h6>
                                    <p class="mb-0 text-muted small">{{ $product->code }}</p>
                                    <p class="mb-0"><strong class="text-primary">Rp {{ number_format((float)$product->price, 0, ',', '.') }}</strong></p>
                                    <p class="mb-0 small">Stok: <span class="badge bg-info">{{ $product->stock }}</span></p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Cart Section -->
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-shopping-cart"></i> Keranjang Belanja</h5>
                </div>
                <div class="card-body">
                    <div id="cartItems" style="max-height: 300px; overflow-y: auto; min-height: 100px;">
                        <p class="text-center text-muted py-3">Keranjang kosong</p>
                    </div>

                    <hr>

                    <!-- Summary -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span><small>Subtotal:</small></span>
                            <span id="subtotal"><small>Rp 0</small></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span><small>Diskon:</small></span>
                            <input type="number" id="discount" class="form-control form-control-sm w-50" 
                                   value="0" min="0" style="text-align: right;">
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span><small>Pajak (10%):</small></span>
                            <span id="tax"><small>Rp 0</small></span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <strong>Total:</strong>
                            <strong id="total" class="text-primary" style="font-size: 1.3em;">Rp 0</strong>
                        </div>
                    </div>

                    <!-- Payment -->
                    <div class="mb-3">
                        <label class="form-label small"><strong>Metode Pembayaran:</strong></label>
                        <select id="paymentMethod" class="form-select form-select-sm">
                            <option value="cash">Tunai</option>
                            <option value="card">Kartu</option>
                            <option value="e-wallet">E-Wallet</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small"><strong>Jumlah Bayar:</strong></label>
                        <input type="number" id="paymentAmount" class="form-control form-control-sm" min="0" 
                               placeholder="Masukkan jumlah bayar..." step="1000">
                        <!-- Quick pay buttons -->
                        <div class="mt-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary me-1" onclick="quickPay(50000)">50rb</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary me-1" onclick="quickPay(100000)">100rb</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary me-1" onclick="quickPay(200000)">200rb</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="exactPay()">Uang Pas</button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <strong><small>Kembalian:</small></strong>
                            <strong id="change" class="text-success"><small>Rp 0</small></strong>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small"><strong>Catatan (Opsional):</strong></label>
                        <textarea id="notes" class="form-control form-control-sm" rows="2"></textarea>
                    </div>

                    <div class="d-grid gap-2">
                        <button class="btn btn-success" onclick="processPayment()">
                            <i class="fas fa-check"></i> Proses Pembayaran
                        </button>
                        <button class="btn btn-outline-danger" onclick="clearCart()">
                            <i class="fas fa-trash"></i> Bersihkan Keranjang
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.product-card {
    cursor: pointer;
    transition: all 0.3s;
    border: 2px solid transparent;
}
.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important;
    border-color: #007bff;
}
.category-filter.active {
    background-color: #007bff;
    color: white;
}
.dropdown-menu {
    max-height: 300px;
    overflow-y: auto;
}
</style>

<script>
let cart = [];
let currentCategory = 'all';

function addToCart(productId, name, price, stock) {
    const existingItem = cart.find(item => item.product_id === productId);
    
    if (existingItem) {
        if (existingItem.quantity < stock) {
            existingItem.quantity++;
        } else {
            alert('Stok tidak mencukupi!');
            return;
        }
    } else {
        cart.push({
            product_id: productId,
            name: name,
            price: parseFloat(price),
            quantity: 1,
            stock: stock
        });
    }
    
    renderCart();
    calculateTotal();
}

function removeFromCart(index) {
    cart.splice(index, 1);
    renderCart();
    calculateTotal();
}

function updateQuantity(index, quantity) {
    const item = cart[index];
    quantity = parseInt(quantity);
    
    if (quantity > 0 && quantity <= item.stock) {
        item.quantity = quantity;
        renderCart();
        calculateTotal();
    } else if (quantity > item.stock) {
        alert('Stok tidak mencukupi! Stok tersedia: ' + item.stock);
        renderCart();
    } else if (quantity <= 0) {
        if (confirm('Hapus item dari keranjang?')) {
            removeFromCart(index);
        } else {
            renderCart();
        }
    }
}

function renderCart() {
    const cartContainer = document.getElementById('cartItems');
    
    if (cart.length === 0) {
        cartContainer.innerHTML = '<p class="text-center text-muted py-3">Keranjang kosong</p>';
        return;
    }
    
    let html = '<div class="list-group">';
    cart.forEach((item, index) => {
        html += `
            <div class="list-group-item">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <strong style="font-size: 0.9rem;">${item.name}</strong>
                    <button class="btn btn-sm btn-close" onclick="removeFromCart(${index})" type="button"></button>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted">Rp ${item.price.toLocaleString('id-ID')}</small>
                    </div>
                    <div class="input-group" style="width: 130px;">
                        <button class="btn btn-sm btn-outline-secondary" type="button" 
                                onclick="updateQuantity(${index}, ${item.quantity - 1})">-</button>
                        <input type="number" class="form-control form-control-sm text-center" 
                               value="${item.quantity}" 
                               onchange="updateQuantity(${index}, this.value)" 
                               min="1" max="${item.stock}">
                        <button class="btn btn-sm btn-outline-secondary" type="button"
                                onclick="updateQuantity(${index}, ${item.quantity + 1})">+</button>
                    </div>
                    <strong>Rp ${(item.price * item.quantity).toLocaleString('id-ID')}</strong>
                </div>
                <small class="text-muted">Stok: ${item.stock}</small>
            </div>
        `;
    });
    html += '</div>';
    
    cartContainer.innerHTML = html;
}

function calculateTotal() {
    const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const discount = parseFloat(document.getElementById('discount').value) || 0;
    const tax = (subtotal - discount) * 0.10;
    const total = subtotal - discount + tax;
    
    document.getElementById('subtotal').innerHTML = '<small>Rp ' + subtotal.toLocaleString('id-ID') + '</small>';
    document.getElementById('tax').innerHTML = '<small>Rp ' + Math.round(tax).toLocaleString('id-ID') + '</small>';
    document.getElementById('total').textContent = 'Rp ' + Math.round(total).toLocaleString('id-ID');
    
    calculateChange();
}

function calculateChange() {
    const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const discount = parseFloat(document.getElementById('discount').value) || 0;
    const tax = (subtotal - discount) * 0.10;
    const finalTotal = subtotal - discount + tax;
    const paid = parseFloat(document.getElementById('paymentAmount').value) || 0;
    const change = paid - finalTotal;
    
    const changeElement = document.getElementById('change');
    if (paid >= finalTotal && paid > 0) {
        changeElement.innerHTML = '<small class="text-success">Rp ' + Math.round(change).toLocaleString('id-ID') + '</small>';
    } else if (paid > 0) {
        changeElement.innerHTML = '<small class="text-danger">Kurang Rp ' + Math.round(Math.abs(change)).toLocaleString('id-ID') + '</small>';
    } else {
        changeElement.innerHTML = '<small>Rp 0</small>';
    }
}

function clearCart() {
    if (cart.length === 0) {
        alert('Keranjang sudah kosong!');
        return;
    }
    
    if (confirm('Yakin ingin mengosongkan keranjang?')) {
        cart = [];
        renderCart();
        calculateTotal();
        document.getElementById('discount').value = 0;
        document.getElementById('paymentAmount').value = '';
        document.getElementById('notes').value = '';
    }
}

function filterCategory(categoryId, event) {
    event.preventDefault();
    
    currentCategory = categoryId;
    document.getElementById('searchProduct').value = '';
    
    const btn = document.getElementById('categoryDropdown');
    if (categoryId === 'all') {
        btn.innerHTML = '<i class="fas fa-filter"></i> Kategori: Semua';
    } else {
        const categoryName = event.target.textContent.trim();
        btn.innerHTML = '<i class="fas fa-filter"></i> Kategori: ' + categoryName;
    }
    
    document.querySelectorAll('.category-filter').forEach(btn => {
        btn.classList.remove('active');
    });
    event.target.classList.add('active');
    
    filterProducts();
    
    const dropdown = bootstrap.Dropdown.getInstance(document.getElementById('categoryDropdown'));
    dropdown.hide();
}

function filterProducts() {
    const searchTerm = document.getElementById('searchProduct').value.toLowerCase();
    const products = document.querySelectorAll('.product-item');
    
    products.forEach(product => {
        const categoryId = product.dataset.category;
        const name = product.dataset.name;
        const code = product.dataset.code;
        
        const matchCategory = currentCategory === 'all' || categoryId == currentCategory;
        const matchSearch = name.includes(searchTerm) || code.includes(searchTerm);
        
        if (matchCategory && matchSearch) {
            product.style.display = 'block';
        } else {
            product.style.display = 'none';
        }
    });
}

function searchProduct() {
    filterProducts();
}

function quickPay(amount) {
    document.getElementById('paymentAmount').value = amount;
    calculateChange();
}

function exactPay() {
    const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const discount = parseFloat(document.getElementById('discount').value) || 0;
    const tax = (subtotal - discount) * 0.10;
    const finalTotal = Math.ceil(subtotal - discount + tax);
    
    document.getElementById('paymentAmount').value = finalTotal;
    calculateChange();
}

async function processPayment() {
    if (cart.length === 0) {
        alert('Keranjang masih kosong!');
        return;
    }
    
    const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const discount = parseFloat(document.getElementById('discount').value) || 0;
    const tax = (subtotal - discount) * 0.10;
    const total = subtotal - discount + tax;
    const paid = parseFloat(document.getElementById('paymentAmount').value) || 0;
    
    if (paid <= 0) {
        alert('Masukkan jumlah pembayaran!');
        document.getElementById('paymentAmount').focus();
        return;
    }
    
    if (paid < total) {
        alert('Pembayaran kurang! Total: Rp ' + Math.round(total).toLocaleString('id-ID'));
        document.getElementById('paymentAmount').focus();
        return;
    }
    
    const data = {
        items: cart,
        payment_amount: paid,
        payment_method: document.getElementById('paymentMethod').value,
        notes: document.getElementById('notes').value
    };
    
    try {
        const response = await fetch('{{ route("pos.process") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            alert('Transaksi berhasil!\n\nNo. Invoice: ' + result.data.transaction_code + 
                  '\nKembalian: Rp ' + Math.round(result.data.change_amount).toLocaleString('id-ID'));
            
            window.open(result.data.receipt_url, '_blank');
            
            cart = [];
            renderCart();
            calculateTotal();
            document.getElementById('discount').value = 0;
            document.getElementById('paymentAmount').value = '';
            document.getElementById('notes').value = '';
        } else {
            alert('Error: ' + result.message);
        }
    } catch (error) {
        alert('Terjadi kesalahan: ' + error.message);
        console.error('Error:', error);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('discount').addEventListener('input', calculateTotal);
    document.getElementById('paymentAmount').addEventListener('input', calculateChange);
    document.getElementById('searchProduct').addEventListener('input', searchProduct);
    document.getElementById('searchProduct').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            searchProduct();
        }
    });
});
</script>
@endsection