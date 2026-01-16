<?php

namespace App\Http\Controllers;

use App\Models\Sale;

class DashboardController extends Controller
{
    public function index()
    {
        // Pagination khusus untuk widget transaksi terakhir di dashboard
        $recentSales = Sale::with('customer')
            ->orderByDesc('id')
            ->paginate(5, ['*'], 'recent_page');

        return view('dashboard', compact('recentSales'));
    }
}
