<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::latest()->get();

        $totalMasuk = Transaction::where('jenis', 'masuk')->sum('nominal');
        $totalKeluar = Transaction::where('jenis', 'keluar')->sum('nominal');
        $saldo = $totalMasuk - $totalKeluar;

        return view('transactions.index', compact('transactions', 'saldo'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal'    => 'required|date',
            'keterangan' => 'required|string|max:255',
            'jenis'      => 'required|in:masuk,keluar',
            'nominal'    => 'required|numeric',
        ]);

        Transaction::create($request->all());

        return redirect()->back()->with('success', 'Transaksi berhasil disimpan!');
    }
}
