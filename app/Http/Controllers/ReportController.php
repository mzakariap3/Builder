<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $month = (int) ($request->input('month') ?: now()->month);
        $year = (int) ($request->input('year') ?: now()->year);
        $type = $request->input('type');

        $base = Transaction::query()->with(['category', 'tourismPlace', 'incomeSource'])
            ->whereYear('transaction_date', $year)->whereMonth('transaction_date', $month);
        if (in_array($type, ['income', 'expense'], true)) $base->where('type', $type);

        $transactions = (clone $base)->latest('transaction_date')->get();
        $income = (clone $base)->where('type', 'income')->sum('amount');
        $expense = (clone $base)->where('type', 'expense')->sum('amount');
        $net = $income - $expense;

        $distribution = (clone $base)->where('type', 'expense')
            ->selectRaw('category_id, SUM(amount) as total')
            ->groupBy('category_id')->orderByDesc('total')->get()
            ->map(function ($row) use ($expense) {
                $row->category_name = Category::find($row->category_id)?->name ?? 'Tanpa Kategori';
                $row->percentage = $expense > 0 ? round(((float) $row->total / $expense) * 100, 1) : 0;
                return $row;
            });

        return view('reports.index', compact('transactions', 'income', 'expense', 'net', 'distribution', 'month', 'year', 'type'));
    }
}
