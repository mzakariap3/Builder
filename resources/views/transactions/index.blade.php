@extends('layouts.app')

@section('content')

    {{-- Page Heading --}}
    <div class="page-heading-row">
        <div>
            <h1>{{ __('transactions.title') }}</h1>
            <p>{{ __('transactions.subtitle') }}</p>
        </div>

        <a href="{{ route('transactions.create') }}" class="gold-button">
            + {{ __('transactions.btn_add') }}
        </a>
    </div>

    <div class="transaction-summary">
        <section class="transaction-total income-total">
            <span>{{ __('messages.total_income') }}</span>
            <strong>{{ rupiah($incomeTotal) }}</strong>
            <small>{{ $incomes->total() }} {{ __('transactions.entries_count') }}</small>
        </section>
        <section class="transaction-total expense-total">
            <span>{{ __('messages.total_expense') }}</span>
            <strong>{{ rupiah($expenseTotal) }}</strong>
            <small>{{ $expenses->total() }} {{ __('transactions.entries_count') }}</small>
        </section>
    </div>

    {{-- Filter --}}
    <form class="filter-panel" method="GET" action="{{ route('transactions.index') }}">
        <div class="filter-search">
            <span>⌕</span>
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="{{ __('transactions.filter_search_placeholder') }}"
                aria-label="{{ __('transactions.filter_search_placeholder') }}"
                onchange="this.form.submit()"
            >
        </div>

        <select name="month" onchange="this.form.submit()">
            <option value="">{{ __('transactions.filter_all_months') }}</option>
            @foreach (range(1, 12) as $m)
                <option value="{{ $m }}" @selected((string) request('month') === (string) $m)>
                    {{ \Illuminate\Support\Carbon::create()->locale(app()->getLocale())->month($m)->translatedFormat('F') }}
                </option>
            @endforeach
        </select>

        <select name="year" onchange="this.form.submit()">
            <option value="">{{ __('transactions.filter_all_years') }}</option>
            @foreach (range(now()->year - 2, now()->year + 1) as $y)
                <option value="{{ $y }}" @selected((string) request('year') === (string) $y)>
                    {{ $y }}
                </option>
            @endforeach
        </select>

        <select name="category_id" onchange="this.form.submit()">
            <option value="">{{ __('transactions.filter_all_categories') }}</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected((string) request('category_id') === (string) $category->id)>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>

        <select name="tourism_place_id" onchange="this.form.submit()">
            <option value="">{{ __('transactions.filter_all_places') }}</option>
            @foreach ($places as $place)
                <option value="{{ $place->id }}" @selected((string) request('tourism_place_id') === (string) $place->id)>
                    {{ $place->name }}
                </option>
            @endforeach
        </select>

        <select name="income_source_id" onchange="this.form.submit()">
            <option value="">{{ __('transactions.filter_all_sources') }}</option>
            @foreach ($sources as $source)
                <option value="{{ $source->id }}" @selected((string) request('income_source_id') === (string) $source->id)>
                    {{ $source->name }}
                </option>
            @endforeach
        </select>

        <a class="outline-button filter-reset" href="{{ route('transactions.index') }}">
            {{ __('transactions.btn_reset') }}
        </a>
    </form>

    <div class="transaction-tables">

    {{-- ================= TABEL PEMASUKAN ================= --}}
    <div class="table-card">
        <div class="table-top transaction-table-top income-table-top">
            <div class="transaction-table-title">
                <span>{{ __('messages.income') ?? 'Data Pemasukan' }}</span>
                <span class="tag">
                    {{ $incomes->total() }} {{ __('transactions.entries_count') }}
                </span>
            </div>
            <a class="outline-button compact" href="{{ route('masters.index') }}">
                {{ __('transactions.btn_edit_options') }}
            </a>
        </div>

        <div class="table-scroll">
            <table class="transaction-table">
                <thead>
                    <tr>
                        <th>{{ __('transactions.th_date') }}</th>
                        <th>{{ __('transactions.th_description') }}</th>
                        <th>{{ __('transactions.th_category') }}</th>
                        <th>{{ __('transactions.th_place') }}</th>
                        <th>{{ __('transactions.th_source') }}</th>
                        <th>{{ __('transactions.th_quantity') }}</th>
                        <th>{{ __('transactions.th_unit_price') }}</th>
                        <th>{{ __('transactions.th_total') }}</th>
                        <th>{{ __('transactions.th_status') }}</th>
                        <th>{{ __('transactions.th_actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($incomes as $t)
                        <tr>
                            <td>{{ $t->transaction_date->format('d M Y') }}</td>
                            <td>
                                <b>{{ $t->description }}</b>
                                @if ($t->package_name)
                                    <div><small>{{ $t->package_name }}</small></div>
                                @endif

                                @if (!empty($t->custom_values))
                                    <div class="transaction-custom-values">
                                        @foreach ($t->custom_values as $key => $value)
                                            <div>
                                                <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong>
                                                {{ is_array($value) ? implode(', ', $value) : $value }}
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </td>
                            <td>
                                <span class="tag">
                                    {{ $t->category?->name ?? __('transactions.no_category') }}
                                </span>
                            </td>
                            <td>{{ $t->tourismPlace?->name ?? '-' }}</td>
                            <td>{{ $t->incomeSource?->name ?? '-' }}</td>
                            <td>{{ rtrim(rtrim(number_format($t->quantity, 2, ',', '.'), '0'), ',') }}</td>
                            <td>{{ rupiah($t->unit_price) }}</td>
                            <td class="amount-income">+{{ rupiah($t->amount) }}</td>
                            <td>
                                <span class="status {{ $t->status }}">
                                    ● {{ $t->status === 'pending' ? __('messages.pending') : __('messages.completed') }}
                                </span>
                            </td>
                            <td class="actions">
                                <a title="{{ __('transactions.btn_edit') }}" href="{{ route('transactions.edit', $t) }}">✎</a>
                                <form method="POST" action="{{ route('transactions.destroy', $t) }}" onsubmit="return confirm('{{ __('transactions.confirm_delete') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="{{ __('transactions.btn_delete') }}" class="delete-btn">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10">
                                <div class="empty-state">
                                    {{ __('transactions.empty_state') }}
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

            {{-- Pagination Pemasukan --}}
            @if ($incomes->hasPages())
        <div class="pagination-row">
            <div class="pagination-info">
                {{ __('transactions.showing') }}
                <strong>{{ $incomes->firstItem() ?? 0 }}</strong>
                {{ __('transactions.to') }}
                <strong>{{ $incomes->lastItem() ?? 0 }}</strong>
                {{ __('transactions.of') }}
                <strong>{{ $incomes->total() }}</strong>
                {{ __('transactions.entries') }}
            </div>

            <div class="pagination-links">
                {{-- Previous --}}
                @if ($incomes->onFirstPage())
                    <span class="disabled">« {{ __('transactions.previous') }}</span>
                @else
                    <a href="{{ $incomes->previousPageUrl() }}">« {{ __('transactions.previous') }}</a>
                @endif

                {{-- Page Numbers --}}
                @for ($page = 1; $page <= $incomes->lastPage(); $page++)
                    @if ($page === $incomes->currentPage())
                        <span class="active">{{ $page }}</span>
                    @else
                        <a href="{{ $incomes->url($page) }}">{{ $page }}</a>
                    @endif
                @endfor

                {{-- Next --}}
                @if ($incomes->hasMorePages())
                    <a href="{{ $incomes->nextPageUrl() }}">{{ __('transactions.next') }} »</a>
                @else
                    <span class="disabled">{{ __('transactions.next') }} »</span>
                @endif
            </div>
        </div>
    @endif
    </div>


    {{-- ================= TABEL PENGELUARAN ================= --}}
    <div class="table-card">
        <div class="table-top transaction-table-top expense-table-top">
            <div class="transaction-table-title">
                <span>{{ __('messages.expense') ?? 'Data Pengeluaran' }}</span>
                <span class="tag">
                    {{ $expenses->total() }} {{ __('transactions.entries_count') }}
                </span>
            </div>
            <a class="outline-button compact" href="{{ route('masters.index') }}">
                {{ __('transactions.btn_edit_options') }}
            </a>
        </div>

        <div class="table-scroll">
            <table class="transaction-table">
                <thead>
                    <tr>
                        <th>{{ __('transactions.th_date') }}</th>
                        <th>{{ __('transactions.th_description') }}</th>
                        <th>{{ __('transactions.th_category') }}</th>
                        <th>{{ __('transactions.th_place') }}</th>
                        <th>{{ __('transactions.th_source') }}</th>
                        <th>{{ __('transactions.th_quantity') }}</th>
                        <th>{{ __('transactions.th_unit_price') }}</th>
                        <th>{{ __('transactions.th_total') }}</th>
                        <th>{{ __('transactions.th_actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($expenses as $t)
                        <tr>
                            <td>{{ $t->transaction_date->format('d M Y') }}</td>
                            <td>
                                <b>{{ $t->description }}</b>
                                @if ($t->package_name)
                                    <div><small>{{ $t->package_name }}</small></div>
                                @endif

                                @if (!empty($t->custom_values))
                                    <div class="transaction-custom-values">
                                        @foreach ($t->custom_values as $key => $value)
                                            <div>
                                                <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong>
                                                {{ is_array($value) ? implode(', ', $value) : $value }}
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </td>
                            <td>
                                <span class="tag">
                                    {{ $t->category?->name ?? __('transactions.no_category') }}
                                </span>
                            </td>
                            <td>{{ $t->tourismPlace?->name ?? '-' }}</td>
                            <td>{{ $t->incomeSource?->name ?? '-' }}</td>
                            <td>{{ rtrim(rtrim(number_format($t->quantity, 2, ',', '.'), '0'), ',') }}</td>
                            <td>{{ rupiah($t->unit_price) }}</td>
                            <td class="amount-expense">-{{ rupiah($t->amount) }}</td>
                            <td class="actions">
                                <a title="{{ __('transactions.btn_edit') }}" href="{{ route('transactions.edit', $t) }}">✎</a>
                                <form method="POST" action="{{ route('transactions.destroy', $t) }}" onsubmit="return confirm('{{ __('transactions.confirm_delete') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="{{ __('transactions.btn_delete') }}" class="delete-btn">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9">
                                <div class="empty-state">
                                    {{ __('transactions.empty_state') }}
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

            {{-- Pagination Pengeluaran --}}
            @if ($expenses->hasPages())
        <div class="pagination-row">
            <div class="pagination-info">
                {{ __('transactions.showing') }}
                <strong>{{ $expenses->firstItem() ?? 0 }}</strong>
                {{ __('transactions.to') }}
                <strong>{{ $expenses->lastItem() ?? 0 }}</strong>
                {{ __('transactions.of') }}
                <strong>{{ $expenses->total() }}</strong>
                {{ __('transactions.entries') }}
            </div>

            <div class="pagination-links">
                {{-- Previous --}}
                @if ($expenses->onFirstPage())
                    <span class="disabled">« {{     __('transactions.previous') }}</span>
                @else
                    <a href="{{ $expenses->previousPageUrl() }}">« {{ __('transactions.previous') }}</a>
                @endif

                {{-- Page Numbers --}}
                @for ($page = 1; $page <= $expenses->lastPage(); $page++)
                    @if ($page === $expenses->currentPage())
                        <span class="active">{{ $page }}</span>
                    @else
                        <a href="{{ $expenses->url($page) }}">{{ $page }}</a>
                    @endif
                @endfor

                {{-- Next --}}
                @if ($expenses->hasMorePages())
                    <a href="{{ $expenses->nextPageUrl() }}">{{ __('transactions.next') }} »</a>
                @else
                    <span class="disabled">{{ __('transactions.next') }} »</span>
                @endif
            </div>
        </div>
    @endif

    </div>

@endsection