@extends('layouts.app')

@section('title', 'Buat Transaksi Penjualan')

@section('content')
<div class="mb-4">
    <h2 class="fw-bold">Buat Transaksi Penjualan</h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('sales.index') }}">Penjualan</a></li>
            <li class="breadcrumb-item active">Buat Transaksi</li>
        </ol>
    </nav>
</div>

<form action="{{ route('sales.store') }}" method="POST" id="saleForm">
    @csrf
    
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Informasi Transaksi</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pelanggan <span class="text-danger">*</span></label>
                            <select name="customer_id" id="customer_id" 
                                    class="form-select @error('customer_id') is-invalid @enderror" required>
                                <option value="">Pilih Pelanggan</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" 
                                            data-discount="{{ $customer->getDiscountPercentage() }}" 
                                            {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }} ({{ $customer->status }})
                                    </option>
                                @endforeach
                            </select>
                            @error('customer_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" name="sale_date" 
                                   class="form-control @error('sale_date') is-invalid @enderror" 
                                   value="{{ old('sale_date', date('Y-m-d')) }}" required>
                            @error('sale_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Daftar Produk</h5>
                    <button type="button" class="btn btn-sm btn-success" id="addProduct">
                        <i class="bi bi-plus-circle"></i> Tambah Produk
                    </button>
                </div>
                <div class="card-body">
                    <div id="productList">
                        <!-- Product items akan ditambahkan di sini via JavaScript -->
                    </div>
                    
                    @error('products')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card position-sticky" style="top: 20px;">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Ringkasan</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td>Subtotal:</td>
                            <td class="text-end fw-bold" id="subtotalDisplay">Rp 0</td>
                        </tr>
                        <tr>
                            <td>Diskon (<span id="discountPercent">0</span>%):</td>
                            <td class="text-end fw-bold text-danger" id="discountDisplay">Rp 0</td>
                        </tr>
                        <tr class="border-top">
                            <td class="fw-bold">Total:</td>
                            <td class="text-end fw-bold fs-5 text-success" id="totalDisplay">Rp 0</td>
                        </tr>
                    </table>
                    
                    <button type="submit" class="btn btn-primary w-100 mt-3">
                        <i class="bi bi-save"></i> Simpan Transaksi
                    </button>
                    <a href="{{ route('sales.index') }}" class="btn btn-secondary w-100 mt-2">
                        <i class="bi bi-x-circle"></i> Batal
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
let productIndex = 0;
const products = @json($products);

document.getElementById('addProduct').addEventListener('click', function() {
    addProductRow();
});

document.getElementById('customer_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const discount = selectedOption.dataset.discount || 0;
    document.getElementById('discountPercent').textContent = discount;
    calculateTotal();
});

function addProductRow() {
    const productList = document.getElementById('productList');
    
    const row = document.createElement('div');
    row.className = 'row mb-3 product-row';
    row.id = `product-row-${productIndex}`;
    
    row.innerHTML = `
        <div class="col-md-5">
            <select name="products[${productIndex}][product_id]" class="form-select product-select" required>
                <option value="">Pilih Produk</option>
                ${products.map(p => `
                    <option value="${p.id}" data-price="${p.price}" data-stock="${p.stock}">
                        ${p.name} (Stok: ${p.stock})
                    </option>
                `).join('')}
            </select>
        </div>
        <div class="col-md-3">
            <input type="number" name="products[${productIndex}][quantity]" 
                   class="form-control quantity-input" placeholder="Qty" min="1" required>
        </div>
        <div class="col-md-3">
            <input type="text" class="form-control item-subtotal" placeholder="Subtotal" readonly>
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-danger btn-sm w-100 remove-product">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    `;
    
    productList.appendChild(row);
    
    // Event listeners untuk row baru
    row.querySelector('.product-select').addEventListener('change', calculateTotal);
    row.querySelector('.quantity-input').addEventListener('input', calculateTotal);
    row.querySelector('.remove-product').addEventListener('click', function() {
        row.remove();
        calculateTotal();
    });
    
    productIndex++;
}

function calculateTotal() {
    let subtotal = 0;
    
    document.querySelectorAll('.product-row').forEach(row => {
        const select = row.querySelector('.product-select');
        const quantityInput = row.querySelector('.quantity-input');
        const itemSubtotalInput = row.querySelector('.item-subtotal');
        
        if (select.value && quantityInput.value) {
            const selectedOption = select.options[select.selectedIndex];
            const price = parseFloat(selectedOption.dataset.price);
            const quantity = parseInt(quantityInput.value);
            const stock = parseInt(selectedOption.dataset.stock);
            
            if (quantity > stock) {
                alert('Jumlah melebihi stok tersedia!');
                quantityInput.value = stock;
                return;
            }
            
            const itemSubtotal = price * quantity;
            itemSubtotalInput.value = formatRupiah(itemSubtotal);
            subtotal += itemSubtotal;
        }
    });
    
    const discountPercent = parseFloat(document.getElementById('discountPercent').textContent) || 0;
    const discount = subtotal * (discountPercent / 100);
    const total = subtotal - discount;
    
    document.getElementById('subtotalDisplay').textContent = formatRupiah(subtotal);
    document.getElementById('discountDisplay').textContent = formatRupiah(discount);
    document.getElementById('totalDisplay').textContent = formatRupiah(total);
}

function formatRupiah(amount) {
    return 'Rp ' + Math.round(amount).toString().replace(/\\B(?=(\\d{3})+(?!\\d))/g, ".");
}

// Tambahkan 1 baris produk saat halaman dimuat
addProductRow();
</script>
@endpush