@extends('layouts.app')

@section('title', 'Data Penjualan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold">Data Penjualan</h2>
        <p class="text-muted">Kelola transaksi penjualan</p>
    </div>
    <a href="{{ route('sales.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Buat Transaksi
    </a>
</div>

{{-- RINGKASAN TOTAL --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <small class="text-muted">Total Subtotal (Semua Data)</small>
                <div class="fw-bold fs-5">
                    Rp {{ number_format($grandSubtotal ?? 0, 0, ',', '.') }}
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <small class="text-muted">Total Diskon (Semua Data)</small>
                <div class="fw-bold fs-5 text-danger">
                    Rp {{ number_format($grandDiscount ?? 0, 0, ',', '.') }}
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <small class="text-muted">Total Penjualan (Semua Data)</small>
                <div class="fw-bold fs-5 text-success">
                    Rp {{ number_format($grandTotal ?? 0, 0, ',', '.') }}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>Invoice</th>
                    <th>Tanggal</th>
                    <th>Pelanggan</th>
                    <th>Subtotal</th>
                    <th>Diskon</th>
                    <th>Total</th>
                    <th width="15%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sales as $sale)
                <tr>
                    <td>{{ $loop->iteration + ($sales->currentPage() - 1) * $sales->perPage() }}</td>
                    <td><strong>#{{ $sale->id }}</strong></td>
                    <td>{{ $sale->sale_date->format('d/m/Y') }}</td>
                    <td>
                        {{ $sale->customer->name }}
                        <br>
                        <span class="badge bg-{{ $sale->customer->isMember() ? 'success' : 'secondary' }}">
                            {{ $sale->customer->status }}
                        </span>
                    </td>
                    <td>Rp {{ number_format($sale->subtotal, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($sale->discount, 0, ',', '.') }}</td>
                    <td class="fw-bold text-success">{{ $sale->formatted_total }}</td>
                    <td>
                        <a href="{{ route('sales.show', $sale) }}" class="btn btn-sm btn-info text-white">
                            <i class="bi bi-eye"></i>
                        </a>
                        <form action="{{ route('sales.destroy', $sale) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Yakin ingin menghapus transaksi ini?')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">Belum ada data penjualan</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-3">
            {{ $sales->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
