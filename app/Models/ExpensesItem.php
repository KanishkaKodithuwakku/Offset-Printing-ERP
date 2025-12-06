<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpensesItem extends Model
{
    use HasFactory;

    protected $table = 'expenses_item';

    protected $fillable = [
        'invoice_id',
        'expense_id',
        'expense_price',
        'quantity',
        'total_price'
    ];

    protected $casts = [
        'expense_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'quantity' => 'integer'
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function expense()
    {
        return $this->belongsTo(OtherExpense::class, 'expense_id');
    }
}
