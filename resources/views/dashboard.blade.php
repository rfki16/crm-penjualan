@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="mb-4">
    <h2 class="fw-bold">Dashboard</h2>
    <p class="text-muted">Selamat datang di CRM Penjualan</p>
</div>

<div class="row g-4">

    {{-- TOTAL PELANGGAN --}}
    <div class="col-md-3">
        <a href="{{ route('customers.index') }}" class="text-decoration-none">
            <div class="card text-white h-100"
                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); cursor:pointer;">
                <div class="card-body">
                    <h6 class="card-title">Total Pelanggan</h6>
                    <h2 class="fw-bold">{{ \App\Models\Customer::count() }}</h2>
                    <small>
                        <i class="bi bi-people"></i>
                        Member: {{ \App\Models\Customer::where('status', 'Member')->count() }}
                    </small>
                </div>
            </div>
        </a>
    </div>

    {{-- TOTAL PRODUK --}}
    <div class="col-md-3">
        <a href="{{ route('products.index') }}" class="text-decoration-none">
            <div class="card text-white h-100"
                style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); cursor:pointer;">
                <div class="card-body">
                    <h6 class="card-title">Total Produk</h6>
                    <h2 class="fw-bold">{{ \App\Models\Product::count() }}</h2>
                    <small>
                        <i class="bi bi-box-seam"></i>
                        Stok: {{ \App\Models\Product::sum('stock') }}
                    </small>
                </div>
            </div>
        </a>
    </div>

    {{-- TOTAL TRANSAKSI --}}
    <div class="col-md-3">
        <a href="{{ route('sales.index') }}" class="text-decoration-none">
            <div class="card text-white h-100"
                style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); cursor:pointer;">
                <div class="card-body">
                    <h6 class="card-title">Total Transaksi</h6>
                    <h2 class="fw-bold">{{ \App\Models\Sale::count() }}</h2>
                    <small><i class="bi bi-cart-check"></i> Semua Data</small>
                </div>
            </div>
        </a>
    </div>

    {{-- TOTAL PENJUALAN --}}
    <div class="col-md-3">
        <a href="{{ route('sales.index') }}" class="text-decoration-none">
            <div class="card text-white h-100"
                style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); cursor:pointer;">
                <div class="card-body">
                    <h6 class="card-title">Total Penjualan</h6>
                    <h2 class="fw-bold">
                        Rp {{ number_format(\App\Models\Sale::sum('total'), 0, ',', '.') }}
                    </h2>
                    <small><i class="bi bi-graph-up"></i> Semua Waktu</small>
                </div>
            </div>
        </a>
    </div>
</div>

@php
    // Kalau controller belum mengirim $recentSales, kita buat di sini supaya pagination tetap jalan.
    // Urutan rapi: invoice terbaru dulu (id DESC).
    $recentSales = $recentSales ?? \App\Models\Sale::with('customer')
        ->orderByDesc('id')
        ->paginate(5, ['*'], 'recent_page');
@endphp

<div class="row mt-5">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Transaksi Terakhir</h5>
                <a href="{{ route('sales.index') }}" class="btn btn-sm btn-outline-primary">
                    Lihat Semua
                </a>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Invoice</th>
                                <th>Tanggal</th>
                                <th>Pelanggan</th>
                                <th>Total</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentSales as $sale)
                                <tr>
                                    <td><strong>#{{ $sale->id }}</strong></td>
                                    <td>
                                        {{-- kalau sale_date sudah di-cast date, ini aman --}}
                                        {{ $sale->sale_date ? $sale->sale_date->format('d/m/Y') : '-' }}
                                    </td>
                                    <td>
                                        {{ $sale->customer->name ?? '-' }}
                                        @if($sale->customer)
                                            <span class="badge bg-{{ $sale->customer->isMember() ? 'success' : 'secondary' }}">
                                                {{ $sale->customer->status }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="fw-bold text-success">
                                        {{ $sale->formatted_total ?? ('Rp ' . number_format($sale->total ?? 0, 0, ',', '.')) }}
                                    </td>
                                    <td class="text-center">
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

                {{-- PAGINATION (Prev/Next + angka) --}}
                <div class="mt-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="text-muted small">
                        Menampilkan {{ $recentSales->firstItem() ?? 0 }} - {{ $recentSales->lastItem() ?? 0 }}
                        dari {{ $recentSales->total() }} data
                    </div>

                    <div>
                        {{ $recentSales->links('pagination::bootstrap-5') }}
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
