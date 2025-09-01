<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    // Define the fillable fields for mass assignment
    protected $fillable = [
        'sale_id',
        'account_id',
        'table_id',
        'debit',
        'credit',
        'transaction_type',
    ];


    /**
     * Get the account that the transaction belongs to.
     */
    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    /**
     * Get the transaction type (debit or credit).
     */
    public function getTransactionTypeAttribute($value)
    {
        return ucfirst($value); // Capitalize the first letter (debit/credit)
    }
}
