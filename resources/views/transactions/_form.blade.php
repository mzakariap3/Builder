@php($editing = isset($transaction))
<form method="POST" action="{{ $editing ? route('transactions.update',$transaction) : route('transactions.store') }}" enctype="multipart/form-data" class="entry-form" id="entryForm">
    @if ($errors->any())
    <div class="alert alert-danger" style="margin-bottom: 20px; padding: 15px; background: #fee2e2; color: #991b1b; border-radius: 8px;">
        <strong>Transaksi gagal disimpan:</strong>
        <ul style="margin: 8px 0 0 20px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    @csrf
    @if($editing) @method('PUT') @endif
    
    {{-- TAB TIPE (Income / Expense) --}}
    <div class="entry-tabs">
        <button type="button"
            class="entry-tab {{ old('type',$transaction->type??'income')==='income' ? 'active' : '' }}"
            data-type="income">
            {{ __('messages.income') }}
        </button>

        <button type="button"
            class="entry-tab {{ old('type',$transaction->type??'income')==='expense' ? 'active' : '' }}"
            data-type="expense">
            {{ __('messages.expense') }}
        </button>
    </div>

    <input type="hidden" name="type" id="typeInput"
           value="{{ old('type',$transaction->type??'income') }}">

    <div class="required-order-note">
        1. {{ __('messages.select_category') }}
        &nbsp; → &nbsp;
        2. {{ __('messages.date') }}, {{ __('messages.quantity') }},
        {{ __('messages.description') }}
    </div>

    <div class="form-grid">

        {{-- LANGKAH 1: KATEGORI --}}
        <div class="field full">
            <label>{{ __('messages.category') }} <span>*</span></label>

            <select name="category_id" id="categorySelect" required>
                <option value="">
                    -- {{ __('messages.select_category') }} --
                </option>

                @foreach($categories as $category)
                    <option value="{{ $category->id }}"
                        data-type="{{ $category->type }}"
                        data-fields="{{ json_encode($category->required_fields ?? ['date', 'quantity', 'unit_price', 'amount', 'payment_method', 'description', 'place', 'source', 'package', 'proof']) }}"
                        @selected((string)old('category_id',$transaction->category_id??'') === (string)$category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

    </div>

    {{-- LANGKAH 2: FIELD LAINNYA --}}
    <div id="dynamicFields" style="display: none; margin-top: 16px;">
        <div class="form-grid">

            <div class="field" data-field="date">
                <label>{{ __('messages.date') }} <span>*</span></label>
                <input type="date"
                       name="transaction_date"
                       value="{{ old('transaction_date',optional($transaction->transaction_date??null)->format('Y-m-d') ?? date('Y-m-d')) }}">
            </div>

            <div class="field" data-field="quantity">
                <label>{{ __('messages.quantity') }} <span>*</span></label>
                <input type="number"
                       min="0.01"
                       step="0.01"
                       name="quantity"
                       id="quantityInput"
                       value="{{ old('quantity',$transaction->quantity??1) }}">
            </div>

            <div class="field" data-field="unit_price">
                <label>{{ __('messages.unit_price') }} <span>*</span></label>
                <input type="number"
                       min="0"
                       step="0.01"
                       name="unit_price"
                       id="unitPriceInput"
                       value="{{ old('unit_price',$transaction->unit_price??0) }}">
            </div>

            <div class="field" data-field="amount">
                <label>{{ __('messages.total') ?? 'Total Amount' }}</label>
                <input class="readonly-money"
                       type="text"
                       id="totalDisplay"
                       value="Rp 0"
                       readonly
                       style="background-color: #f3f4f6; font-weight: bold;">
                <input type="hidden" name="amount" id="amountInput" value="{{ old('amount', $transaction->amount ?? 0) }}">
            </div>

            <div class="field" data-field="place">
                <label>{{ __('messages.place') }}</label>

                <select name="tourism_place_id">
                    <option value="">
                        {{ __('messages.all_places') }}
                    </option>

                    @foreach($places as $place)
                        <option value="{{ $place->id }}"
                            @selected((string)old('tourism_place_id',$transaction->tourism_place_id??'') === (string)$place->id)>
                            {{ $place->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="field income-only" data-field="source">
                <label>{{ __('messages.income_source') }}</label>

                <select name="income_source_id">
                    <option value="">
                        {{ __('messages.income_source') }}
                    </option>

                    @foreach($sources as $source)
                        <option value="{{ $source->id }}"
                            @selected((string)old('income_source_id',$transaction->income_source_id??'') === (string)$source->id)>
                            {{ $source->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="field" data-field="payment_method">
                <label>{{ __('messages.payment_method') }} <span>*</span></label>

                <select name="payment_method">
                    <option value="">
                        {{ __('messages.select_method') }}
                    </option>

                    @foreach(['Cash','Transfer','QRIS','Debit','E-Wallet'] as $method)
                        <option value="{{ $method }}" @selected(old('payment_method',$transaction->payment_method??'') === $method)>
                            {{ $method }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="field full" data-field="package">
                <label>{{ __('messages.tour_package') }}</label>

                <input type="text"
                       name="package_name"
                       value="{{ old('package_name',$transaction->package_name??'') }}"
                       placeholder="Contoh: Paket Jelajah Kampung Naga">
            </div>

            <div class="field full" data-field="description">
                <label>{{ __('messages.description') }} <span>*</span></label>

                <textarea name="description"
                          rows="4"
                          placeholder="{{ __('messages.description_placeholder') }}">{{ old('description',$transaction->description??'') }}</textarea>
            </div>

            <div class="field full" data-field="proof">
                <label>{{ __('messages.payment_proof') }}</label>

                <input type="file"
                       name="proof"
                       accept="image/*,.pdf"
                       data-proof-input>

                <small>
                    {{ __('messages.payment_proof_hint') }}
                </small>

                @if($editing &&$transaction->proof_path)
                    <a class="existing-proof"
                       target="_blank"
                       href="{{ asset('storage/'.$transaction->proof_path) }}">
                        {{ __('messages.view_current_proof') }}
                    </a>
                @endif

                <div data-proof-preview class="proof-preview"></div>
            </div>

            <div class="field" data-field="status">
                <label>{{ __('messages.status') }}</label>

                <select name="status">
                    <option value="pending"
                        @selected(old('status',$transaction->status??'pending') === 'pending')>
                        {{ __('messages.pending') }}
                    </option>

                    <option value="completed"
                        @selected(old('status',$transaction->status??'pending') === 'completed')>
                        {{ __('messages.completed') }}
                    </option>
                </select>
            </div>

        </div>

        {{-- FIELD KUSTOM  --}}
        <div id="customFieldsContainer" class="field full" style="display: none; margin-top: 16px;">
            <div id="customFieldsList" class="form-grid"></div>
        </div>

        <div class="form-actions" style="margin-top: 24px;">
            <a class="text-button" href="{{ route('transactions.index') }}">
                {{ __('messages.cancel') }}
            </a>

            <button class="gold-button" type="submit">
                {{ $editing
                    ? __('messages.update_entry')
                    : __('messages.save_entry') }}
            </button>
        </div>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.getElementById('categorySelect');
    const dynamicFields = document.getElementById('dynamicFields');
    const typeInput = document.getElementById('typeInput');
    const tabs = document.querySelectorAll('.entry-tab');
    
    const qtyInput = document.getElementById('quantityInput');
    const priceInput = document.getElementById('unitPriceInput');
    const totalDisplay = document.getElementById('totalDisplay');
    const amountInput = document.getElementById('amountInput');

    const customFieldsContainer = document.getElementById('customFieldsContainer');
    const customFieldsList = document.getElementById('customFieldsList');

    const existingCustomValues = @json(old('custom_values', $transaction->custom_values ?? []));

    function calculateTotal() {
        const qty = parseFloat(qtyInput?.value) || 0;
        const price = parseFloat(priceInput?.value) || 0;
        const total = qty * price;

        if (totalDisplay) {
            totalDisplay.value = 'Rp ' + total.toLocaleString('id-ID');
        }
        if (amountInput) {
            amountInput.value = total;
        }
    }

    if (qtyInput && priceInput) {
        qtyInput.addEventListener('input', calculateTotal);
        priceInput.addEventListener('input', calculateTotal);
        calculateTotal();
    }

    function renderCategoryCustomFields(categoryId) {
        if (!categoryId || !customFieldsContainer || !customFieldsList) return;

        fetch(`/api/categories/${categoryId}/fields`)
            .then(response => {
                if (!response.ok) throw new Error('Gagal memuat custom fields.');
                return response.json();
            })
            .then(fields => {
                customFieldsList.innerHTML = '';

                if (!Array.isArray(fields) || fields.length === 0) {
                    customFieldsContainer.style.display = 'none';
                    return;
                }

                customFieldsContainer.style.display = 'block';

                fields.forEach(field => {
                    const fieldType = String(field.field_type || 'text').toLowerCase();
                    const keyName = String(field.field_name || field.id);
                    const value = existingCustomValues[keyName] ?? '';
                    const isRequired = Boolean(field.is_required);

                    const fieldDiv = document.createElement('div');
                    fieldDiv.className = (fieldType === 'textarea' || fieldType === 'file')
                        ? 'field full'
                        : 'field';

                    const label = document.createElement('label');
                    label.textContent = field.field_label || keyName;
                    if (isRequired) {
                        const mark = document.createElement('span');
                        mark.textContent = ' *';
                        mark.style.color = 'red';
                        label.appendChild(mark);
                    }
                    fieldDiv.appendChild(label);

                    let input;

                    if (fieldType === 'textarea') {
                        input = document.createElement('textarea');
                        input.rows = 3;
                        input.value = value == null ? '' : String(value);
                    } else if (fieldType === 'select') {
                        input = document.createElement('select');

                        const placeholder = document.createElement('option');
                        placeholder.value = '';
                        placeholder.textContent = '-- Pilih --';
                        input.appendChild(placeholder);

                        let options = field.options || [];
                        if (typeof options === 'string') {
                            try {
                                options = JSON.parse(options);
                            } catch (error) {
                                options = options.split(',');
                            }
                        }
                        if (!Array.isArray(options)) options = [];

                        options.forEach(optionValue => {
                            const option = document.createElement('option');
                            option.value = String(optionValue).trim();
                            option.textContent = String(optionValue).trim();
                            option.selected = String(value) === option.value;
                            input.appendChild(option);
                        });
                    } else if (fieldType === 'file') {
                        input = document.createElement('input');
                        input.type = 'file';
                        input.accept = 'image/*,.pdf';

                        if (value && typeof value === 'string') {
                            const currentFile = document.createElement('small');
                            currentFile.className = 'existing-custom-file';
                            currentFile.textContent = `File tersimpan: ${value}`;
                            currentFile.style.display = 'block';
                            currentFile.style.marginTop = '6px';
                            fieldDiv.appendChild(input);
                            fieldDiv.appendChild(currentFile);
                            input.name = `custom_values[${keyName}]`;
                            customFieldsList.appendChild(fieldDiv);
                            return;
                        }
                    } else {
                        input = document.createElement('input');
                        input.type = ['text', 'number', 'date'].includes(fieldType)
                            ? fieldType
                            : 'text';
                        input.value = value == null ? '' : String(value);
                    }

                    input.name = `custom_values[${keyName}]`;
                    if (isRequired && fieldType !== 'file') input.required = true;

                    fieldDiv.appendChild(input);
                    customFieldsList.appendChild(fieldDiv);
                });
            })
            .catch(error => {
                console.error(error);
                customFieldsContainer.style.display = 'none';
            });
    }

    function syncFormState() {
        const selectedOption = categorySelect.options[categorySelect.selectedIndex];
        
        if (!categorySelect.value) {
            dynamicFields.style.display = 'none';
            if (customFieldsContainer) customFieldsContainer.style.display = 'none';
            return;
        }

        dynamicFields.style.display = 'block';

        const currentType = typeInput.value;
        let allowedFields = [];
        try {
            const parsedFields = JSON.parse(selectedOption.getAttribute('data-fields') || '[]');
            allowedFields = Array.isArray(parsedFields) ? parsedFields : [];
        } catch (error) {
            allowedFields = [];
        }

        // Support both legacy aliases and database column names.
        const fieldAliases = {
            transaction_date: 'date',
            tourism_place_id: 'place',
            income_source_id: 'source',
            package_name: 'package',
            proof_path: 'proof'
        };
        allowedFields = allowedFields.map(name => fieldAliases[name] || name);

        document.querySelectorAll('[data-field]').forEach(fieldEl => {
            const fieldName = fieldEl.getAttribute('data-field');
            
            let isAllowed = allowedFields.includes(fieldName);

            if (fieldName === 'amount' && (allowedFields.includes('amount') || (allowedFields.includes('quantity') && allowedFields.includes('unit_price')))) {
                isAllowed = true;
            }

            if (fieldName === 'source' && currentType !== 'income') {
                isAllowed = false;
            }

            if (fieldName === 'status' && currentType !== 'income') {
                isAllowed = false;
            } else if (fieldName === 'status') {
                isAllowed = true;
            }

            if (isAllowed) {
                fieldEl.style.display = '';
                const inputs = fieldEl.querySelectorAll('input, select, textarea');
                inputs.forEach(input => {
                    if (['transaction_date', 'quantity', 'unit_price', 'payment_method', 'description'].includes(input.name)) {
                        input.setAttribute('required', 'required');
                    }
                });
            } else {
                fieldEl.style.display = 'none';
                const inputs = fieldEl.querySelectorAll('input, select, textarea');
                inputs.forEach(input => input.removeAttribute('required'));
            }
        });

        calculateTotal();
        renderCategoryCustomFields(categorySelect.value);
    }

    categorySelect.addEventListener('change', syncFormState);

    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            tabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            
            const type = this.getAttribute('data-type');
            typeInput.value = type;

            Array.from(categorySelect.options).forEach(opt => {
                if (!opt.value) return;
                const optType = opt.getAttribute('data-type');
                if (optType === 'both' || optType === type) {
                    opt.style.display = '';
                } else {
                    opt.style.display = 'none';
                    if (opt.selected) categorySelect.value = '';
                }
            });

            syncFormState();
        });
    });

    syncFormState();
});
</script>