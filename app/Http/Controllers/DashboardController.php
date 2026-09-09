<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPendapatan = Transaction::where('jenis', 'masuk')->sum('nominal');
        $totalPengeluaran = Transaction::where('jenis', 'keluar')->sum('nominal');
        $recentTransactions = Transaction::latest()->take(4)->get();

        return view('dashboard', compact('totalPendapatan', 'totalPengeluaran', 'recentTransactions'));
    }
}
