<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::latest()->paginate(10);
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'   => 'required|string|max:100',
            'alamat' => 'nullable|string',
            'no_wa'  => 'required|string|max:20',
        ], [
            'nama.required'  => 'Nama pelanggan wajib diisi.',
            'no_wa.required' => 'Nomor WhatsApp wajib diisi.',
        ]);

        Customer::create($request->all());

        return redirect()->route('customers.index')
            ->with('success', 'Pelanggan berhasil ditambahkan!');
    }

    public function show(Customer $customer)
    {
        $transaksis = $customer->transaksis()
            ->with(['hutang', 'detailTransaksis.barang'])
            ->latest()
            ->get();

        return view('customers.show', compact('customer', 'transaksis'));
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'nama'   => 'required|string|max:100',
            'alamat' => 'nullable|string',
            'no_wa'  => 'required|string|max:20',
        ]);

        $customer->update($request->all());

        return redirect()->route('customers.index')
            ->with('success', 'Data pelanggan berhasil diperbarui!');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'Pelanggan berhasil dihapus!');
    }
}
