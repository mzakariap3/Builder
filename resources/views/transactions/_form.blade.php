@php($editing = isset($transaction))
<form method="POST" action="{{ $editing ? route('transactions.update',$transaction) : route('transactions.store') }}" enctype="multipart/form-data" class="entry-form" id="entryForm">
    @csrf
    @if($editing) @method('PUT') @endif
    <div class="entry-tabs">
        <button type="button" class="entry-tab {{ old('type',$transaction->type??'income')==='income' ? 'active' : '' }}" data-type="income">Income</button>
        <button type="button" class="entry-tab {{ old('type',$transaction->type??'income')==='expense' ? 'active' : '' }}" data-type="expense">Expense</button>
    </div>
    <input type="hidden" name="type" id="typeInput" value="{{ old('type',$transaction->type??'income') }}">

    <div class="required-order-note">1. Pilih kategori terlebih dahulu &nbsp; → &nbsp; 2. Isi tanggal, jumlah, dan detail transaksi</div>

    <div class="form-grid">
        <div class="field full"><label>Kategori <span>*</span></label><select name="category_id" id="categorySelect" required><option value="">Select Category</option>@foreach($categories as $category)<option value="{{ $category->id }}" data-type="{{ $category->type }}" @selected((string)old('category_id',$transaction->category_id??'')===(string)$category->id)>{{ $category->name }}</option>@endforeach</select>@error('category_id')<small class="error-text">{{ $message }}</small>@enderror</div>
        <div class="field"><label>Date <span>*</span></label><input type="date" name="transaction_date" value="{{ old('transaction_date',optional($transaction->transaction_date??null)->format('Y-m-d')) }}" required>@error('transaction_date')<small class="error-text">{{ $message }}</small>@enderror</div>
        <div class="field"><label>Jumlah <span>*</span></label><input type="number" min="0.01" step="0.01" name="quantity" id="quantityInput" value="{{ old('quantity',$transaction->quantity??1) }}" required>@error('quantity')<small class="error-text">{{ $message }}</small>@enderror</div>
        <div class="field"><label>Harga Satuan (IDR) <span>*</span></label><input type="number" min="0" step="0.01" name="unit_price" id="unitPriceInput" value="{{ old('unit_price',$transaction->unit_price??0) }}" required>@error('unit_price')<small class="error-text">{{ $message }}</small>@enderror</div>
        <div class="field"><label>Total (IDR)</label><input class="readonly-money" type="text" id="totalDisplay" value="Rp 0" readonly></div>
        <div class="field"><label>Tempat Wisata</label><select name="tourism_place_id"><option value="">Pilih tempat wisata</option>@foreach($places as $place)<option value="{{ $place->id }}" @selected((string)old('tourism_place_id',$transaction->tourism_place_id??'')===(string)$place->id)>{{ $place->name }}</option>@endforeach</select></div>
        <div class="field income-only"><label>Sumber Pendapatan</label><select name="income_source_id"><option value="">Pilih sumber pendapatan</option>@foreach($sources as $source)<option value="{{ $source->id }}" @selected((string)old('income_source_id',$transaction->income_source_id??'')===(string)$source->id)>{{ $source->name }}</option>@endforeach</select></div>
        <div class="field"><label>Payment Method <span>*</span></label><select name="payment_method" required><option value="">Select Method</option>@foreach(['Cash','Transfer','QRIS','Debit','E-Wallet'] as $method)<option @selected(old('payment_method',$transaction->payment_method??'')===$method)>{{ $method }}</option>@endforeach</select></div>
        <div class="field full"><label>Paket Wisata (opsional)</label><input type="text" name="package_name" value="{{ old('package_name',$transaction->package_name??'') }}" placeholder="Contoh: Paket Jelajah Kampung Naga"></div>
        <div class="field full"><label>Description <span>*</span></label><textarea name="description" rows="4" required placeholder="Enter details...">{{ old('description',$transaction->description??'') }}</textarea>@error('description')<small class="error-text">{{ $message }}</small>@enderror</div>
        <div class="field full"><label>Bukti Bayar</label><input type="file" name="proof" accept="image/*,.pdf" data-proof-input><small>JPG, PNG, atau PDF maksimal 5 MB. Preview tersedia sebelum submit.</small>@if($editing && $transaction->proof_path)<a class="existing-proof" target="_blank" href="{{ asset('storage/'.$transaction->proof_path) }}">Lihat bukti saat ini</a>@endif<div data-proof-preview class="proof-preview"></div></div>
        <div class="field"><label>Status</label><select name="status"><option value="completed" @selected(old('status',$transaction->status??'completed')==='completed')>Completed</option><option value="pending" @selected(old('status',$transaction->status??'completed')==='pending')>Pending</option></select></div>
    </div>

    <div class="form-actions"><a class="text-button" href="{{ route('transactions.index') }}">Cancel</a><button class="gold-button" type="submit">{{ $editing ? 'Update Entry' : 'Save Entry' }}</button></div>
</form>
