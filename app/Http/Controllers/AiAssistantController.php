<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AiAssistantController extends Controller
{
    public function chat(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:1000'],
        ]);

        $message = mb_strtolower(trim($validated['message']));

        $start = now()->startOfMonth();
        $end = now()->endOfMonth();

        $previousStart = now()->subMonthNoOverflow()->startOfMonth();
        $previousEnd = now()->subMonthNoOverflow()->endOfMonth();

        // Perbandingan bulan ini dengan bulan sebelumnya
        if (
            str_contains($message, 'bandingkan') ||
            str_contains($message, 'dibandingkan') ||
            str_contains($message, 'bulan sebelumnya')
        ) {
            return response()->json([
                'reply' => $this->comparison(
                    $start,
                    $end,
                    $previousStart,
                    $previousEnd
                ),
            ]);
        }

        // Sumber pemasukan terbesar
        if (
            str_contains($message, 'sumber pemasukan') ||
            str_contains($message, 'pemasukan terbesar') ||
            str_contains($message, 'pendapatan terbesar')
        ) {
            return response()->json([
                'reply' => $this->largestIncomeSource($start, $end),
            ]);
        }

        // Pengeluaran terbesar
        if (
            str_contains($message, 'pengeluaran terbesar') ||
            str_contains($message, 'biaya terbesar') ||
            str_contains($message, 'transaksi terbesar')
        ) {
            return response()->json([
                'reply' => $this->largestExpense($start, $end),
            ]);
        }

        // Ringkasan atau total keuangan
        if (
            str_contains($message, 'total') ||
            str_contains($message, 'ringkasan') ||
            str_contains($message, 'laporan') ||
            str_contains($message, 'pemasukan') ||
            str_contains($message, 'pengeluaran') ||
            str_contains($message, 'keuangan')
        ) {
            return response()->json([
                'reply' => $this->financialSummary($start, $end),
            ]);
        }

        return response()->json([
            'reply' => "Maaf, saya belum memahami pertanyaan tersebut.\n\n"
                . "Saat ini saya bisa membantu:\n"
                . "• Menghitung total pemasukan dan pengeluaran bulan ini.\n"
                . "• Menampilkan sumber pemasukan terbesar.\n"
                . "• Menampilkan pengeluaran terbesar.\n"
                . "• Membandingkan keuangan antarbulan.\n"
                . "• Membuat ringkasan laporan keuangan.",
        ]);
    }

    private function financialSummary($start, $end): string
    {
        $income = $this->totalByType('income', $start, $end);
        $expense = $this->totalByType('expense', $start, $end);
        $balance = $income - $expense;

        $month = now()->translatedFormat('F Y');

        $condition = $balance >= 0
            ? 'positif'
            : 'defisit';

        return "Berikut ringkasan keuangan {$month}:\n\n"
            . "Total pemasukan: " . $this->rupiah($income) . "\n"
            . "Total pengeluaran: " . $this->rupiah($expense) . "\n"
            . "Selisih: " . $this->rupiah($balance) . "\n\n"
            . "Analisis:\n"
            . "Keuangan periode ini menunjukkan kondisi {$condition} "
            . "sebesar " . $this->rupiah(abs($balance)) . ".\n\n"
            . "Analisis dihitung berdasarkan transaksi yang tercatat "
            . "pada database Si Kawan Odeon.";
    }

    private function largestIncomeSource($start, $end): string
    {
        $source = Transaction::query()
            ->with('incomeSource')
            ->where('type', 'income')
            ->whereBetween('transaction_date', [$start, $end])
            ->whereNotNull('income_source_id')
            ->selectRaw('income_source_id, SUM(amount) as total_amount')
            ->groupBy('income_source_id')
            ->orderByDesc('total_amount')
            ->first();

        if (!$source || !$source->incomeSource) {
            return "Belum ada data pemasukan berdasarkan sumber pendapatan "
                . "pada bulan ini.";
        }

        return "Sumber pemasukan terbesar bulan ini adalah:\n\n"
            . $source->incomeSource->name . "\n"
            . "Total pemasukan: " . $this->rupiah($source->total_amount)
            . "\n\nData dihitung dari transaksi pemasukan yang tercatat "
            . "pada bulan ini.";
    }

    private function largestExpense($start, $end): string
    {
        $expense = Transaction::query()
            ->with('category')
            ->where('type', 'expense')
            ->whereBetween('transaction_date', [$start, $end])
            ->orderByDesc('amount')
            ->first();

        if (!$expense) {
            return "Belum ada transaksi pengeluaran pada bulan ini.";
        }

        $category = $expense->category?->name ?? 'Tidak ada kategori';

        return "Transaksi pengeluaran terbesar bulan ini:\n\n"
            . "Keterangan: " . $expense->description . "\n"
            . "Kategori: " . $category . "\n"
            . "Tanggal: " . $expense->transaction_date->format('d-m-Y') . "\n"
            . "Jumlah: " . $this->rupiah($expense->amount);
    }

    private function comparison(
        $start,
        $end,
        $previousStart,
        $previousEnd
    ): string {
        $currentIncome = $this->totalByType('income', $start, $end);
        $currentExpense = $this->totalByType('expense', $start, $end);

        $previousIncome = $this->totalByType(
            'income',
            $previousStart,
            $previousEnd
        );

        $previousExpense = $this->totalByType(
            'expense',
            $previousStart,
            $previousEnd
        );

        $currentBalance = $currentIncome - $currentExpense;
        $previousBalance = $previousIncome - $previousExpense;

        return "Perbandingan keuangan:\n\n"
            . "Bulan ini (" . now()->translatedFormat('F Y') . ")\n"
            . "Pemasukan: " . $this->rupiah($currentIncome) . "\n"
            . "Pengeluaran: " . $this->rupiah($currentExpense) . "\n"
            . "Selisih: " . $this->rupiah($currentBalance) . "\n\n"
            . "Bulan sebelumnya ("
            . now()->subMonthNoOverflow()->translatedFormat('F Y') . ")\n"
            . "Pemasukan: " . $this->rupiah($previousIncome) . "\n"
            . "Pengeluaran: " . $this->rupiah($previousExpense) . "\n"
            . "Selisih: " . $this->rupiah($previousBalance) . "\n\n"
            . "Analisis:\n"
            . "Perubahan selisih keuangan: "
            . $this->rupiah($currentBalance - $previousBalance)
            . ".";
    }

    private function totalByType(string $type, $start, $end): float
    {
        return (float) Transaction::query()
            ->where('type', $type)
            ->whereBetween('transaction_date', [$start, $end])
            ->sum('amount');
    }

    private function rupiah($amount): string
    {
        return 'Rp' . number_format((float) $amount, 0, ',', '.');
    }
}
