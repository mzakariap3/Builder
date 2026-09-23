@extends('layouts.app')
@section('content')
<div class="page-heading-row">
    <div><h1>Financial Data</h1><p>Review and manage transaction records.</p></div>
    <a href="{{ route('transactions.create') }}" class="gold-button">+ Add Entry</a>
</div>

<form class="filter-panel" method="GET" action="{{ route('transactions.index') }}">
    <div class="filter-search"><span>⌕</span><input name="search" value="{{ request('search') }}" placeholder="Search transactions..."></div>
    <select name="type"><option value="">All Types</option><option value="income" @selected(request('type')==='income')>Pemasukan</option><option value="expense" @selected(request('type')==='expense')>Pengeluaran</option></select>
    <select name="month"><option value="">All Months</option>@foreach(range(1,12) as $m)<option value="{{ $m }}" @selected((string)request('month')===(string)$m)>{{ \Illuminate\Support\Carbon::create()->month($m)->format('F') }}</option>@endforeach</select>
    <select name="year"><option value="">All Years</option>@foreach(range(now()->year-2,now()->year+1) as $y)<option @selected((string)request('year')===(string)$y)>{{ $y }}</option>@endforeach</select>
    <select name="category_id"><option value="">All Categories</option>@foreach($categories as $category)<option value="{{ $category->id }}" @selected((string)request('category_id')===(string)$category->id)>{{ $category->name }}</option>@endforeach</select>
    <select name="tourism_place_id"><option value="">Semua Tempat Wisata</option>@foreach($places as $place)<option value="{{ $place->id }}" @selected((string)request('tourism_place_id')===(string)$place->id)>{{ $place->name }}</option>@endforeach</select>
    <select name="income_source_id"><option value="">Sumber Pendapatan</option>@foreach($sources as $source)<option value="{{ $source->id }}" @selected((string)request('income_source_id')===(string)$source->id)>{{ $source->name }}</option>@endforeach</select>
    <button class="outline-button" type="submit">Filter</button>
    <a class="text-button" href="{{ route('transactions.index') }}">Reset</a>
</form>

<div class="table-card">
    <div class="table-top"><span>{{ $transactions->total() }} entries</span><a class="outline-button compact" href="{{ route('masters.index') }}">Edit dropdown data</a></div>
    <div class="table-scroll">
    <table id="transactionTable">
        <thead><tr><th>Date</th><th>Description / Paket Wisata</th><th>Category</th><th>Place</th><th>Source</th><th>Qty</th><th>Unit Price</th><th>Amount</th><th>Status</th><th>Action</th></tr></thead>
        <tbody>
            @forelse($transactions as $t)
            <tr>
                <td>{{ $t->transaction_date->format('d M Y') }}</td>
                <td><b>{{ $t->description }}</b>@if($t->package_name)<small>{{ $t->package_name }}</small>@endif</td>
                <td><span class="tag">{{ $t->category?->name ?? 'Tanpa Kategori' }}</span></td>
                <td>{{ $t->tourismPlace?->name ?? '-' }}</td>
                <td>{{ $t->incomeSource?->name ?? '-' }}</td>
                <td>{{ rtrim(rtrim(number_format($t->quantity,2,',','.'),'0'),',') }}</td>
                <td>{{ rupiah($t->unit_price) }}</td>
                <td class="{{ $t->type==='income' ? 'amount-income' : 'amount-expense' }}">{{ $t->type==='income' ? '+' : '-' }}{{ rupiah($t->amount) }}</td>
                <td><span class="status {{ $t->status }}">● {{ ucfirst($t->status) }}</span></td>
                <td class="actions"><a title="Edit" href="{{ route('transactions.edit',$t) }}">✎</a><form method="POST" action="{{ route('transactions.destroy',$t) }}" onsubmit="return confirm('Hapus transaksi ini?')">@csrf @method('DELETE')<button type="submit" title="Delete" class="delete-btn"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg></button></form></td>
            </tr>
            @empty
            <tr><td colspan="10"><div class="empty-state">Tidak ada data sesuai filter.</div></td></tr>
            @endforelse
        </tbody>
    </table>
    </div>
    <div class="pagination-row">{{ $transactions->links() }}</div>
</div>
@endsection
