<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'type', 'transaction_date', 'category_id', 'tourism_place_id', 'income_source_id',
        'package_name', 'description', 'quantity', 'unit_price', 'amount',
        'payment_method', 'status', 'proof_path',
    ];

    protected function casts(): array
    {
        return [
            'transaction_date' => 'date',
            'quantity' => 'decimal:2',
            'unit_price' => 'decimal:2',
            'amount' => 'decimal:2',
        ];
    }

    public function category(): BelongsTo { return $this->belongsTo(Category::class); }
    public function tourismPlace(): BelongsTo { return $this->belongsTo(TourismPlace::class); }
    public function incomeSource(): BelongsTo { return $this->belongsTo(IncomeSource::class); }
}
