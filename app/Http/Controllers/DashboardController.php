<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        return $this->renderDashboard($request, false);
    }

    public function admin(Request $request): View
    {
        return $this->renderDashboard($request, true);
    }

    private function renderDashboard(Request $request, bool $admin): View
    {
        $month = $request->integer('month') ?: now()->month;
        $year = $request->integer('year') ?: now()->year;
        $start = Carbon::create($year, $month, 1)->startOfMonth();
        $end = $start->copy()->endOfMonth();

        $base = Transaction::query()->whereBetween('transaction_date', [$start, $end]);
        $income = (clone $base)->where('type', 'income')->sum('amount');
        $expense = (clone $base)->where('type', 'expense')->sum('amount');
        $recent = (clone $base)->with(['category', 'tourismPlace'])->latest('transaction_date')->limit(6)->get();

        $chart = collect(range(1, 4))->map(function (int $week) use ($start) {
            $weekStart = $start->copy()->addWeeks($week - 1)->startOfWeek();
            $weekEnd = $weekStart->copy()->endOfWeek();
            return [
                'label' => 'Week '.$week,
                'income' => Transaction::where('type', 'income')->whereBetween('transaction_date', [$weekStart, $weekEnd])->sum('amount'),
                'expense' => Transaction::where('type', 'expense')->whereBetween('transaction_date', [$weekStart, $weekEnd])->sum('amount'),
            ];
        });

        $net = $income - $expense;
        $margin = $income > 0 ? ($net / $income) * 100 : 0;

        return view('dashboard.index', compact('admin', 'income', 'expense', 'net', 'margin', 'recent', 'chart', 'month', 'year'));
    }
}
