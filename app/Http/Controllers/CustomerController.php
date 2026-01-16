<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * daftar semua customer
     */
    public function index()
    {
        $customers = Customer::latest()->paginate(10);
        return view('customers.index', compact('customers'));
    }

    /**
     * tampilkan form tambah customer
     */
    public function create()
    {
        return view('customers.create');
    }

    /**
     * simpan pelanggan baru ke database
     */
    public function store(Request $request)
    {
        // validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:Member,Non-Member',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string'
        ]);

        // simpan ke database
        Customer::create($validated);

        // redirect pesan sukses
        return redirect()->route('customers.index')
            ->with('success', 'Pelanggan berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * form edit pelanggan
     */
    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    /**
     * Update data pelanggan
     */
    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:Member,Non-Member',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string'
        ]);

        $customer->update($validated);

        return redirect()->route('customers.index')
            ->with('success', 'Pelanggan berhasil diupdate!');
    }

    /**
     * Hapus Pelanggan
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'Pelanggan berhasil dihapus!');
    }
}
