<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Enumerable;

class TransactionsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    private int $rowNumber = 0;

    /**
    * @return \Illuminate\Support\Collection|\Illuminate\Support\Enumerable
    */
    public function collection(): Enumerable
    {
        return Transaction::all();
    }


    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Keterangan',
            'Jenis Transaksi',
            'Nominal (Rp)',
        ];
    }


    public function map($transaction): array
    {
        $this->rowNumber++;

        return [
            $this->rowNumber,
            $transaction->tanggal,
            $transaction->keterangan,
            ucfirst($transaction->jenis),
            'Rp ' . number_format($transaction->nominal, 0, ',', '.'),
        ];
    }
}