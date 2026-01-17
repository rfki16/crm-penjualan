<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Halaman Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Routes untuk Customer (CRUD lengkap)
Route::resource('customers', CustomerController::class);

// Routes untuk Product (CRUD lengkap)
Route::resource('products', ProductController::class);

// Routes untuk Sales
Route::resource('sales', SaleController::class)->except(['edit', 'update']);

// Routes untuk Reports
Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

// Routes untuk invoice
Route::get('/sales/{sale}/invoice', [SaleController::class, 'invoice'])
    ->name('sales.invoice');
