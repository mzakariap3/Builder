<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\IncomeSource;
use App\Models\TourismPlace;
use App\Models\Transaction;
use App\Notifications\PaymentStatusNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TransactionController extends Controller
{
    public function index(Request $request): View
    {
        $query = Transaction::query()->with(['category', 'tourismPlace', 'incomeSource']);

        if ($search = trim((string) $request->input('search'))) {
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('package_name', 'like', "%{$search}%")
                  ->orWhereHas('category', fn ($cq) => $cq->where('name', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('tourism_place_id')) {
            $query->where('tourism_place_id', $request->tourism_place_id);
        }

        if ($request->filled('income_source_id')) {
            $query->where('income_source_id', $request->income_source_id);
        }

        if ($request->filled('month')) {
            $query->whereMonth('transaction_date', $request->month);
        }

        if ($request->filled('year')) {
            $query->whereYear('transaction_date', $request->year);
        }

        $incomeTotal = (clone $query)->where('type', 'income')->sum('amount');
        $expenseTotal = (clone $query)->where('type', 'expense')->sum('amount');

        $incomes = (clone $query)->where('type', 'income')
            ->latest('transaction_date')
            ->paginate(8, ['*'], 'income_page')
            ->withQueryString();

        $expenses = (clone $query)->where('type', 'expense')
            ->latest('transaction_date')
            ->paginate(8, ['*'], 'expense_page')
            ->withQueryString();

        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $places = TourismPlace::where('is_active', true)->orderBy('name')->get();
        $sources = IncomeSource::where('is_active', true)->orderBy('name')->get();

        return view('transactions.index', compact(
            'incomes',
            'expenses',
            'incomeTotal',
            'expenseTotal',
            'categories',
            'places',
            'sources'
        ));
    }

    public function create(): View
    {
        return view('transactions.create', $this->formData());
    }

    public function edit(Transaction $transaction): View
    {
        return view('transactions.edit', array_merge(['transaction' => $transaction], $this->formData()));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateEntry($request);
        $validated['user_id'] = $request->user()?->id;
        $validated['amount'] = round((float) ($validated['quantity'] ?? 0) * (float) ($validated['unit_price'] ?? 0), 2);
        $validated['status'] = $validated['status'] ?? 'pending';
        $validated['proof_path'] = $this->storeProof($request);
        $validated['custom_values'] = $this->processCustomValues($request);

        $transaction = Transaction::create($validated);

        $statusNotif = $transaction->status === 'completed' ? 'success' : 'pending';

        $pesanNotif = $statusNotif === 'success'
            ? __('messages.transaction_recorded', [
                'type' => $transaction->type === 'income'
                    ? __('messages.income')
                    : __('messages.expense'),
                'description' => $transaction->description,
            ])
            : __('messages.transaction_pending', [
                'type' => $transaction->type === 'income'
                    ? __('messages.income')
                    : __('messages.expense'),
                'description' => $transaction->description,
            ]);

        $request->user()?->notify(new PaymentStatusNotification(
            $statusNotif,
            $transaction->amount,
            $pesanNotif
        ));

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil ditambahkan.');
    }

    public function update(Request $request, Transaction $transaction): RedirectResponse
    {
        $validated = $this->validateEntry($request);
        $validated['amount'] = round((float) ($validated['quantity'] ?? 0) * (float) ($validated['unit_price'] ?? 0), 2);
        $validated['status'] = $validated['status'] ?? $transaction->status;

        if ($path = $this->storeProof($request)) {
            if ($transaction->proof_path) {
                Storage::disk('public')->delete($transaction->proof_path);
            }
            $validated['proof_path'] = $path;
        }

        $validated['custom_values'] = $this->processCustomValues($request, $transaction);
        $transaction->update($validated);

        $statusNotif = $transaction->status === 'completed' ? 'success' : 'pending';
        $pesanNotif = __('messages.transaction_updated', [
            'description' => $transaction->description,
        ]);

        $request->user()?->notify(new PaymentStatusNotification(
            $statusNotif,
            $transaction->amount,
            $pesanNotif
        ));

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil diperbarui.');
    }

    public function destroy(Transaction $transaction): RedirectResponse
    {
        if ($transaction->proof_path) {
            Storage::disk('public')->delete($transaction->proof_path);
        }

        $transaction->delete();

        return back()->with('success', 'Transaksi berhasil dihapus.');
    }

    private function validateEntry(Request $request): array
    {
        $validated = $request->validate([
            'type' => ['required', 'in:income,expense'],
            'category_id' => ['required', 'exists:categories,id'],
            'transaction_date' => ['required', 'date'],
            'quantity' => ['nullable', 'numeric', 'min:0'],
            'unit_price' => ['nullable', 'numeric', 'min:0'],
            'tourism_place_id' => ['nullable', 'exists:tourism_places,id'],
            'income_source_id' => ['nullable', 'exists:income_sources,id'],
            'package_name' => ['nullable', 'string', 'max:150'],
            'payment_method' => ['required', 'string', 'max:60'],
            'description' => ['required', 'string', 'max:500'],
            'status' => ['nullable', 'in:completed,pending'],
            'proof' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'custom_values' => ['nullable', 'array'],
        ], [
            'category_id.required' => 'Kategori wajib dipilih sebelum tanggal dan jumlah.',
            'proof.max' => 'Bukti bayar maksimal 5 MB.',
        ]);

        if ($validated['type'] === 'expense') {
            $validated['income_source_id'] = null;
        }

        // Custom field uploads are handled separately and must not be passed as JSON values.
        unset($validated['proof'], $validated['custom_values']);

        return $validated;
    }

    /**
     * Merge custom field text values and uploads into the JSON custom_values column.
     * On update, existing custom file paths are preserved when no replacement is uploaded.
     */
    private function processCustomValues(Request $request, ?Transaction $transaction = null): array
    {
        $values = $transaction?->custom_values ?? [];

        if (!is_array($values)) {
            $values = [];
        }

        $submittedValues = $request->input('custom_values', []);
        if (is_array($submittedValues)) {
            foreach ($submittedValues as $key => $value) {
                if (is_string($key) || is_int($key)) {
                    $values[$key] = is_scalar($value) || $value === null ? $value : '';
                }
            }
        }

        // Nested uploads such as custom_values[field_name] are read from allFiles().
        $allFiles = $request->allFiles();
        $customFiles = $allFiles['custom_values'] ?? [];

        if (is_array($customFiles)) {
            foreach ($customFiles as $key => $file) {
                if ($file instanceof \Illuminate\Http\UploadedFile && $file->isValid()) {
                    $oldPath = $values[$key] ?? null;
                    $newPath = $file->store('custom_field_uploads', 'public');

                    if ($newPath) {
                        if (is_string($oldPath) && $oldPath !== '') {
                            Storage::disk('public')->delete($oldPath);
                        }
                        $values[$key] = $newPath;
                    }
                }
            }
        }

        return $values;
    }

    private function storeProof(Request $request): ?string
    {
        return $request->hasFile('proof')
            ? $request->file('proof')->store('payment_proofs', 'public')
            : null;
    }

    private function formData(): array
    {
        return [
            'categories' => Category::where('is_active', true)->orderBy('type')->orderBy('name')->get(),
            'places' => TourismPlace::where('is_active', true)->orderBy('name')->get(),
            'sources' => IncomeSource::where('is_active', true)->orderBy('name')->get(),
        ];
    }
}
