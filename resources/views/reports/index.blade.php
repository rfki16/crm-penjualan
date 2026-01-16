@extends('layouts.app')

@section('title', 'Laporan Penjualan')

@section('content')
<div class="mb-4">
    <h2 class="fw-bold">Laporan Penjualan</h2>
    <p class="text-muted">Laporan transaksi penjualan berdasarkan periode</p>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('reports.index') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Tanggal Mulai</label>
                <input type="date" name="start_date" class="form-control" 
                       value="{{ request('start_date') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Tanggal Akhir</label>
                <input type="date" name="end_date" class="form-control" 
                       value="{{ request('end_date') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-funnel"></i> Filter
                    </button>
                    <a href="{{ route('reports.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-clockwise"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Total Transaksi</h6>
                        <h3 class="fw-bold mb-0">{{ $totalTransactions }}</h3>
                    </div>
                    <div class="fs-1 text-primary">
                        <i class="bi bi-cart-check"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Total Penjualan</h6>
                        <h3 class="fw-bold mb-0 text-success">Rp {{ number_format($totalSales, 0, ',', '.') }}</h3>
                    </div>
                    <div class="fs-1 text-success">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Transaksi</h5>
        <button onclick="window.print()" class="btn btn-sm btn-outline-primary">
            <i class="bi bi-printer"></i> Cetak Laporan
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Invoice</th>
                        <th>Tanggal</th>
                        <th>Pelanggan</th>
                        <th>Status Member</th>
                        <th>Subtotal</th>
                        <th>Diskon</th>
                        <th>Total</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $sale)
                    <tr>
                        <td>{{ $loop->iteration + ($sales->currentPage() - 1) * $sales->perPage() }}</td>
                        <td><strong>#{{ $sale->id }}</strong></td>
                        <td>{{ $sale->sale_date->format('d/m/Y') }}</td>
                        <td>{{ $sale->customer->name }}</td>
                        <td>
                            <span class="badge bg-{{ $sale->customer->isMember() ? 'success' : 'secondary' }}">
                                {{ $sale->customer->status }}
                            </span>
                        </td>
                        <td>Rp {{ number_format($sale->subtotal, 0, ',', '.') }}</td>
                        <td class="text-danger">Rp {{ number_format($sale->discount, 0, ',', '.') }}</td>
                        <td class="fw-bold text-success">{{ $sale->formatted_total }}</td>
                        <td>
                            <a href="{{ route('sales.show', $sale) }}" class="btn btn-sm btn-info text-white">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">
                            Tidak ada data transaksi
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                @if($sales->count() > 0)
                <tfoot class="table-light">
                    <tr>
                        <th colspan="5" class="text-end">Total:</th>
                        <th>Rp {{ number_format($sales->sum('subtotal'), 0, ',', '.') }}</th>
                        <th class="text-danger">Rp {{ number_format($sales->sum('discount'), 0, ',', '.') }}</th>
                        <th class="text-success">Rp {{ number_format($sales->sum('total'), 0, ',', '.') }}</th>
                        <th></th>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
        
        <div class="mt-3">
            {{ $sales->withQueryString()->links('pagination::bootstrap-5') }}

        </div>
    </div>
</div>

@push('styles')
<style>
@media print {
    .sidebar, .btn, nav, .alert, form {
        display: none !important;
    }
    .card {
        box-shadow: none !important;
    }
}
</style>
@endpush
@endsection