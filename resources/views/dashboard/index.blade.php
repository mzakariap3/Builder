@extends('layouts.app')
@section('content')
<div class="dashboard-page">
    <div class="hero-row">
        <div>
            <h1>Financial Overview</h1>
            <p>Track your property's financial health.</p>
        </div>
        <div class="hero-actions">
            <form method="GET" class="month-switcher">
                <select name="month" onchange="this.form.submit()">
                    @foreach(range(1,12) as $m)
                        <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>{{ \Illuminate\Support\Carbon::create()->month($m)->format('F') }}</option>
                    @endforeach
                </select>
                <select name="year" onchange="this.form.submit()">
                    @foreach(range(now()->year-2, now()->year+1) as $y)<option {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>@endforeach
                </select>
            </form>
            <button class="outline-button" type="button" onclick="window.print()">Export Report</button>
        </div>
    </div>

    <div class="metric-grid">
        <section class="metric-card"><div class="metric-label">Total income <span>↗</span></div><div class="metric-value">{{ rupiah($income, true) }}</div><div class="metric-meta up">↑ Tracked this month</div></section>
        <section class="metric-card"><div class="metric-label">Total expenses <span>↘</span></div><div class="metric-value">{{ rupiah($expense, true) }}</div><div class="metric-meta down">↓ Recorded this month</div></section>
        <section class="metric-card net"><div class="metric-label">Net Balance <span>▣</span></div><div class="metric-value">{{ rupiah($net, true) }}</div><div class="metric-meta">Healthy margin maintained</div></section>
    </div>

    <div class="dashboard-grid">
        <section class="panel chart-panel">
            <div class="panel-heading"><h2>Income vs Expenses</h2><span class="legend"><i class="dot gold"></i> Income <i class="dot red"></i> Expenses</span></div>
            <div class="bar-chart">
                @php($maxChart = max(1, $chart->max(fn($i) => max($i['income'],$i['expense']))))
                @foreach($chart as $item)
                    <div class="bar-group">
                        <div class="bars">
                            <div class="bar gold-bar" style="height: {{ max(7, ($item['income']/$maxChart)*180) }}px" title="Income: {{ rupiah($item['income']) }}"></div>
                            <div class="bar red-bar" style="height: {{ max(7, ($item['expense']/$maxChart)*180) }}px" title="Expense: {{ rupiah($item['expense']) }}"></div>
                        </div>
                        <span>{{ $item['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="panel recent-panel">
            <div class="panel-heading"><h2>Recent Transactions</h2><a href="{{ route('transactions.index') }}">View All</a></div>
            @forelse($recent as $row)
                <a class="recent-row" href="{{ route('transactions.edit',$row) }}">
                    <span class="recent-icon">{{ $row->type === 'income' ? '↗' : '↘' }}</span>
                    <span class="recent-info"><b>{{ $row->description }}</b><small>{{ $row->transaction_date->format('d M Y') }} • {{ $row->tourismPlace?->name ?? 'Umum' }}</small></span>
                    <strong class="{{ $row->type === 'income' ? 'amount-income' : 'amount-expense' }}">{{ $row->type === 'income' ? '+' : '-' }}{{ rupiah($row->amount) }}</strong>
                </a>
            @empty
                <div class="empty-state">Belum ada transaksi.</div>
            @endforelse
        </section>
    </div>
</div>
@endsection
