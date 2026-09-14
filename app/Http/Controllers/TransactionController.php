<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransactionsExport;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::latest()->get();

        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        return view('transactions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal'    => 'required|date',
            'keterangan' => 'required|string|max:255',
            'jenis'      => 'required|in:masuk,keluar',
            'nominal'    => 'required|numeric',
        ]);
        $validated['user_id'] = Auth::id();

    
        Transaction::create($validated);

        return redirect()->back()->with('success', 'Transaksi berhasil disimpan!');
    }

    public function export()
    {
        return Excel::download(new TransactionsExport, 'report-transaksi.xlsx');
    }
}
