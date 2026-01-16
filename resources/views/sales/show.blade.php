@extends('layouts.app')

@section('title', 'Detail Transaksi')

@section('content')
<div class="mb-4">
    <h2 class="fw-bold">Detail Transaksi #{{ $sale->id }}</h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('sales.index') }}">Penjualan</a></li>
            <li class="breadcrumb-item active">Detail</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Informasi Transaksi</h5>
                <span class="badge bg-success">Selesai</span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td width="40%"><strong>Invoice:</strong></td>
                                <td>#{{ $sale->id }}</td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal:</strong></td>
                                <td>{{ $sale->sale_date->format('d F Y') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Pelanggan:</strong></td>
                                <td>
                                    {{ $sale->customer->name }}
                                    <br>
                                    <span class="badge bg-{{ $sale->customer->isMember() ? 'success' : 'secondary' }}">
                                        {{ $sale->customer->status }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td width="40%"><strong>Telepon:</strong></td>
                                <td>{{ $sale->customer->phone ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Alamat:</strong></td>
                                <td>{{ $sale->customer->address ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Detail Produk</h5>
            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama Produk</th>
                            <th>Harga</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sale->saleItems as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->product->name }}</td>
                            <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Ringkasan Pembayaran</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td>Subtotal:</td>
                        <td class="text-end fw-bold">Rp {{ number_format($sale->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Diskon ({{ $sale->customer->getDiscountPercentage() }}%):</td>
                        <td class="text-end fw-bold text-danger">Rp {{ number_format($sale->discount, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="border-top">
                        <td class="fw-bold fs-5">Total:</td>
                        <td class="text-end fw-bold fs-4 text-success">{{ $sale->formatted_total }}</td>
                    </tr>
                </table>
                
                <div class="d-grid gap-2 mt-4">
                    <a href="{{ route('sales.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <button onclick="window.print()" class="btn btn-outline-primary">
                        <i class="bi bi-printer"></i> Cetak Invoice
                    </button>
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-body">
                <h6 class="mb-3">Informasi Tambahan</h6>
                <small class="text-muted">
                    <i class="bi bi-clock"></i> Dibuat: {{ $sale->created_at->format('d/m/Y H:i') }}
                    <br>
                    <i class="bi bi-clock-history"></i> Diupdate: {{ $sale->updated_at->format('d/m/Y H:i') }}
                </small>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
@media print {
    .sidebar, .btn, nav {
        display: none !important;
    }
}
</style>
@endpush
@endsection