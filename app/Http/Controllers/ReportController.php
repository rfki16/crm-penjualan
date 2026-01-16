<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Sale::with('customer');

        // Filter berdasarkan tanggal jika ada
        if ($request->filled('start_date')) {
            $query->whereDate('sale_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('sale_date', '<=', $request->end_date);
        }

        $sales = $query->latest('sale_date')->paginate(15);

        // Hitung statistik
        $totalSales = $sales->sum('total');
        $totalTransactions = $sales->total();

        return view('reports.index', compact('sales', 'totalSales', 'totalTransactions'));
    }
}
