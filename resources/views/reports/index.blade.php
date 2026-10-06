@extends('layouts.app')

@section('content')

<div class="page-heading-row">
    <div>
        <h1>{{ __('messages.financial_reports') }}</h1>
        <p>{{ __('messages.report_description') }}</p>
    </div>

    <div class="hero-actions">
        <button class="outline-button" onclick="window.print()">
            {{ __('messages.download_pdf') }}
        </button>

        <button class="gold-button" onclick="downloadTableCsv('reportTable','laporan-keuangan.csv')">
            {{ __('messages.export_excel') }}
        </button>
    </div>
</div>

<form class="filter-panel report-filter" method="GET">

        {{-- Filter Bulan (Sudah Ditambahkan "Semua Bulan" & Auto Submit) --}}
        <select name="month" onchange="this.form.submit()">
            <option value="">{{ __('transactions.filter_all_months') ?? 'Semua Bulan' }}</option>
            @foreach(range(1, 12) as $m)
                <option value="{{ $m }}" @selected((string) request('month') === (string) $m)>
                    {{ \Illuminate\Support\Carbon::create()->locale(app()->getLocale())->month($m)->translatedFormat('F') }}
                </option>
            @endforeach
        </select>

        {{-- Filter Tahun --}}
        <select name="year" onchange="this.form.submit()">
            <option value="">{{ __('transactions.filter_all_years') ?? 'Semua Tahun' }}</option>
            @foreach(range(now()->year - 2, now()->year + 1) as $y)
                <option value="{{ $y }}" @selected((string) request('year') === (string) $y)>
                    {{ $y }}
                </option>
            @endforeach
        </select>

        {{-- Filter Tipe Transaksi --}}
        <select name="type" onchange="this.form.submit()">
            <option value="">
                {{ __('messages.all_types') ?? 'Semua Tipe' }}
            </option>
            <option value="income" @selected(request('type') === 'income')>
                {{ __('messages.income') }}
            </option>
            <option value="expense" @selected(request('type') === 'expense')>
                {{ __('messages.expense') }}
            </option>
        </select>

        {{-- Filter Kategori --}}
        <select name="category_id" onchange="this.form.submit()">
            <option value="">
                {{ __('messages.all_categories') ?? 'Semua Kategori' }}
            </option>
            <option value="uncategorized" @selected(request('category_id') === 'uncategorized')>
                {{ __('messages.uncategorized') ?? 'Tanpa Kategori' }}
            </option>
            @if(isset($categories))
                @foreach($categories as $cat)
                    @php
                        $catTranslated = __('categories.' . \Illuminate\Support\Str::slug($cat->name, '_')) !== 'categories.' . \Illuminate\Support\Str::slug($cat->name, '_')
                            ? __('categories.' . \Illuminate\Support\Str::slug($cat->name, '_'))
                            : $cat->name;
                    @endphp
                    <option value="{{ $cat->id }}" @selected((string) request('category_id') === (string) $cat->id)>
                        {{ $catTranslated }}
                    </option>
                @endforeach
            @endif
        </select>

        {{-- Reset Filter --}}
        <a class="outline-button" href="{{ request()->url() }}">
            {{ __('transactions.btn_reset') ?? 'Reset' }}
        </a>
    </form>

</form>

<div class="metric-grid report-metrics">

    <section class="metric-card">
        <div class="metric-label">
            {{ __('messages.total_income') }}
        </div>
        <div class="metric-value">
            {{ rupiah($income) }}
        </div>
    </section>

    <section class="metric-card">
        <div class="metric-label">
            {{ __('messages.total_expense') }}
        </div>
        <div class="metric-value">
            {{ rupiah($expense) }}
        </div>
    </section>

    <section class="metric-card net">
        <div class="metric-label">
            {{ __('messages.net_profit_margin') }}
        </div>
        <div class="metric-value">
            {{ number_format($income ? ($net / $income) * 100 : 0, 1, ',', '.') }}%
        </div>
        <div class="metric-meta">
            {{ __('messages.net') }}: {{ rupiah($net) }}
        </div>
    </section>
</div>

<div class="dashboard-grid report-grid">
    {{-- Distribusi Pengeluaran --}}
    <section class="panel chart-panel">
        <div class="panel-heading">
            <h2>{{ __('messages.expense_distribution') }}</h2>
            <span>{{ __('messages.by_category') }}</span>
        </div>
        <div class="donut-wrap">
            @php
                $offset = 0;
                $circumference = 477.52;
                $categoryColors = [
                    'maintenance' => '#a24945',
                    'marketing' => '#c6a44a',
                    'operations' => '#d3c7b3',
                ];
                $fallbackColors = ['#5e2b2d', '#7e6210', '#8a7464', '#9b8060'];
            @endphp

            <div class="donut" role="group" aria-label="{{ __('messages.expense_distribution') }}">
                <svg class="donut-chart" viewBox="0 0 200 200">
                    @php
                        $offset = 0;
                    @endphp
                    @foreach($distribution as $item)
                        @php
                            $categorySlug = \Illuminate\Support\Str::slug($item->category_name, '_');
                            $categoryTranslation = __('categories.' . $categorySlug);
                            $categoryLabel = $categoryTranslation !== 'categories.' . $categorySlug
                                ? $categoryTranslation
                                : $item->category_name;
                            $color = $categoryColors[$categorySlug] ?? $fallbackColors[$loop->index % count($fallbackColors)];
                            $segmentLength = $circumference * $item->percentage / 100;
                        @endphp
                        <circle
                            class="donut-segment"
                            cx="100"
                            cy="100"
                            r="76"
                            fill="none"
                            stroke="{{ $color }}"
                            stroke-width="45"
                            stroke-dasharray="{{ $segmentLength }} {{ $circumference }}"
                            stroke-dashoffset="{{ -$circumference * $offset / 100 }}"
                            tabindex="0"
                            role="img"
                            aria-label="{{ $categoryLabel }}: {{ number_format($item->percentage, 1, ',', '.') }}%, {{ rupiah($item->total) }}"
                        >
                            <title>{{ $categoryLabel }}: {{ number_format($item->percentage, 1, ',', '.') }}% — {{ rupiah($item->total) }}</title>
                        </circle>
                        @php
                            $offset += $item->percentage;
                        @endphp
                    @endforeach
                </svg>
                <div>
                    <small>{{ __('messages.total') }}</small>
                    <b>
                        @if(app()->getLocale() === 'id')
                            @if($expense >= 1_000_000_000_000)
                                Rp {{ number_format($expense / 1_000_000_000_000, 1, ',', '.') }} T
                            @elseif($expense >= 1_000_000_000)
                                Rp {{ number_format($expense / 1_000_000_000, 1, ',', '.') }} M
                            @elseif($expense >= 1_000_000)
                                Rp {{ number_format($expense / 1_000_000, 1, ',', '.') }} jt
                            @elseif($expense >= 1_000)
                                Rp {{ number_format($expense / 1_000, 1, ',', '.') }} rb
                            @else
                                {{ rupiah($expense) }}
                            @endif
                        @else
                            {{ rupiah($expense, true) }}
                        @endif
                    </b>
                </div>
            </div>
            <div class="distribution-list">
                @foreach($distribution as $item)
                    @php
                        $categorySlug = \Illuminate\Support\Str::slug($item->category_name, '_');
                        $categoryTranslation = __('categories.' . $categorySlug);
                        $categoryLabel = $categoryTranslation !== 'categories.' . $categorySlug
                            ? $categoryTranslation
                            : $item->category_name;
                        $color = $categoryColors[$categorySlug] ?? $fallbackColors[$loop->index % count($fallbackColors)];
                    @endphp
                    <div
                        title="{{ $categoryLabel }}: {{ number_format($item->percentage, 1, ',', '.') }}%"
                        aria-label="{{ $categoryLabel }}: {{ number_format($item->percentage, 1, ',', '.') }}%"
                    >
                        <span>
                            <i class="dot" style="background-color: {{ $color }}"></i>
                            {{ $categoryLabel }}
                        </span>
                        <b>{{ number_format($item->percentage, 1, ',', '.') }}%</b>
                    </div>

                @endforeach

                @if($distribution->isEmpty())
                    <div class="empty-state">
                        {{ __('messages.no_expense_data') }}
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- Transaksi Terakhir --}}
    <section class="panel">
        <div class="panel-heading">
            <h2>{{ __('messages.recent_entries') }}</h2>

            <a href="{{ route('transactions.index') }}">
                {{ __('messages.view_full_ledger') }} →
            </a>
        </div>
        <div class="table-scroll">
            <table id="reportTable" class="report-table">
                <thead>
                    <tr>
                        <th>{{ __('messages.date') }}</th>
                        <th>{{ __('messages.description') }}</th>
                        <th>{{ __('messages.category') }}</th>
                        <th>{{ __('messages.place') }}</th>
                        <th>{{ __('messages.reference') }}</th>
                        <th>{{ __('messages.amount') }}</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($transactions as $t)
                        <tr>
                            <td>
                                {{ $t->transaction_date->locale('id')->translatedFormat('d M Y') }}
                            </td>

                            <td>
                                {{ $t->description }}
                            </td>

                            <td>
                                <span class="tag">
                                    {{ $t->category?->name ? (__('categories.' . \Illuminate\Support\Str::slug($t->category->name, '_')) !== 'categories.' . \Illuminate\Support\Str::slug($t->category->name, '_') ? __('categories.' . \Illuminate\Support\Str::slug($t->category->name, '_')) : $t->category->name) : '-' }}
                                </span>
                            </td>

                            <td>
                                {{ $t->tourismPlace?->name ?? '-' }}
                            </td>

                            <td>
                                {{ $t->package_name ?? '-' }}
                            </td>

                            <td class="{{ $t->type === 'income' ? 'amount-income' : 'amount-expense' }}">
                                {{ $t->type === 'income' ? '+' : '-' }}{{ rupiah($t->amount) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection