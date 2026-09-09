<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Default filter bulan menggunakan bulan berjalan jika tidak ada filter
        $bulan = $request->input('bulan', date('Y-m'));

        // Filter data berdasarkan bulan dan tahun
        $query = Transaction::whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan]);

        $totalPendapatan = (clone $query)->where('jenis', 'masuk')->sum('nominal');
        $totalPengeluaran = (clone $query)->where('jenis', 'keluar')->sum('nominal');
        $saldo = $totalPendapatan - $totalPengeluaran;

        $transactions = $query->latest('tanggal')->get();

        return view('reports.index', compact('totalPendapatan', 'totalPengeluaran', 'saldo', 'transactions'));
    }
}