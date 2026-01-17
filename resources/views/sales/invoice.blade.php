@extends('layouts.invoice')

@section('content')

<div class="mb-4 text-center">
    <h3 class="fw-bold">INVOICE PENJUALAN</h3>
    <small>No Invoice: #{{ $sale->id }}</small>
</div>

{{-- INFORMASI TRANSAKSI --}}
<div class="card mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Informasi Transaksi</h5>
        <span class="badge bg-success">Selesai</span>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-borderless mb-0">
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
                            {{ $sale->customer->name }} <br>
                            <span class="badge bg-{{ $sale->customer->isMember() ? 'success' : 'secondary' }}">
                                {{ $sale->customer->status }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-borderless mb-0">
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

{{-- DETAIL PRODUK --}}
<div class="card mb-4">
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

{{-- RINGKASAN PEMBAYARAN --}}
<div class="card mb-4">
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
    </div>
</div>

{{-- INFORMASI TAMBAHAN --}}
<div class="card mb-4">
    <div class="card-body">
        <h6 class="mb-3">Informasi Tambahan</h6>
        <small class="text-muted">
            Dibuat: {{ $sale->created_at->format('d/m/Y H:i') }} <br>
            Diupdate: {{ $sale->updated_at->format('d/m/Y H:i') }}
        </small>
    </div>
</div>

{{-- BUTTON PRINT --}}
<div class="text-center no-print">
    <button onclick="window.print()" class="btn btn-primary px-4">
        Print Invoice
    </button>
</div>

@endsection
