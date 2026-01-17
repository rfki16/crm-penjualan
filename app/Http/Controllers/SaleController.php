<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Customer;
use App\Models\Product;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sales = Sale::with('customer')->latest()->paginate(10);
        return view('sales.index', compact('sales'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::all();
        $products = Product::where('stock', '>', 0)->get();

        return view('sales.create', compact('customers', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'sale_date' => 'required|date',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1'
        ]);

        // gunakan database transaction
        DB::beginTransaction();

        try {
            // buat sale baru
            $sale = Sale::create([
                'customer_id' => $validated['customer_id'],
                'sale_date' => $validated['sale_date'],
                'subtotal' => 0,
                'discount' => 0,
                'total' => 0
            ]);

            // proses item produk
            foreach ($validated['products'] as $item) {
                $product = Product::findOrFail($item['product_id']);

                // cek stok
                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stok {$product->name} tidak mencukupi!");
                }

                // sale item
                $saleItem = new SaleItem([
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price
                ]);

                // hitung subtotal
                $saleItem->calculateSubtotal();

                // simpan item ke sale
                $sale->saleItems()->save($saleItem);

                // kurangi stok produk
                $product->reduceStock($item['quantity']);
            }

            // total keseluruhan include diskon
            $sale->calculateTotals();
            $sale->save();

            // commit
            DB::commit();

            return redirect()->route('sales.show', $sale)
                ->with('success', 'Transaksi penjualan berhasil dibuat');
        } catch (\Exception $e) {
            // rollback jika error
            DB::rollBack();

            return back()->withInput()
                ->with('error', 'Gagal membuat transaksi. Silakan periksa data dan coba lagi.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Sale $sale)
    {
        $sale->load(['customer', 'saleItems.product']);
        return view('sales.show', compact('sale'));
    }

    /**
     * 🧾 TAMPILKAN INVOICE (METHOD BARU)
     */
    public function invoice(Sale $sale)
    {
        $sale->load(['customer', 'saleItems.product']);
        return view('sales.invoice', compact('sale'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sale $sale)
    {
        // Kembalikan stok produk sebelum hapus
        foreach ($sale->saleItems as $item) {
            $product = $item->product;
            $product->stock += $item->quantity;
            $product->save();
        }

        $sale->delete();

        return redirect()->route('sales.index')
            ->with('success', 'Transaksi berhasil dihapus!');
    }
}