@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="mb-4">
    <h2 class="fw-bold">Dashboard</h2>
    <p class="text-muted">Selamat datang di CRM Penjualan</p>
</div>

<div class="row g-4">
    <div class="col-md-3">
        <div class="card text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="card-body">
                <h6 class="card-title">Total Pelanggan</h6>
                <h2 class="fw-bold">{{ \App\Models\Customer::count() }}</h2>
                <small>
                    <i class="bi bi-people"></i>
                    Member: {{ \App\Models\Customer::where('status', 'Member')->count() }}
                </small>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-white" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
            <div class="card-body">
                <h6 class="card-title">Total Produk</h6>
                <h2 class="fw-bold">{{ \App\Models\Product::count() }}</h2>
                <small>
                    <i class="bi bi-box-seam"></i>
                    Stok: {{ \App\Models\Product::sum('stock') }}
                </small>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-white" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
            <div class="card-body">
                <h6 class="card-title">Total Transaksi</h6>
                <h2 class="fw-bold">{{ \App\Models\Sale::count() }}</h2>
                <small><i class="bi bi-cart-check"></i> Bulan Ini</small>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-white" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
            <div class="card-body">
                <h6 class="card-title">Total Penjualan</h6>
                <h2 class="fw-bold">
                    Rp {{ number_format(\App\Models\Sale::sum('total'), 0, ',', '.') }}
                </h2>
                <small><i class="bi bi-graph-up"></i> Semua Waktu</small>
            </div>
        </div>
    </div>
</div>

<div class="row mt-5">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Transaksi Terakhir</h5>
            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Invoice</th>
                            <th>Tanggal</th>
                            <th>Pelanggan</th>
                            <th>Total</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(\App\Models\Sale::with('customer')->latest()->take(5)->get() as $sale)
                        <tr>
                            <td><strong>#{{ $sale->id }}</strong></td>
                            <td>{{ $sale->sale_date->format('d/m/Y') }}</td>
                            <td>
                                {{ $sale->customer->name }}
                                <span class="badge bg-{{ $sale->customer->isMember() ? 'success' : 'secondary' }}">
                                    {{ $sale->customer->status }}
                                </span>
                            </td>
                            <td class="fw-bold text-success">{{ $sale->formatted_total }}</td>
                            <td>
                                <a href="{{ route('sales.show', $sale) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Belum ada transaksi</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
