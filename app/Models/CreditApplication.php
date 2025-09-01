<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CreditApplication extends Model
{
    use HasFactory;

    protected $table = 'credit_applications';

    protected $fillable = [
        'customer_credit_id',
        'invoice_id',
        'amount',         // Amount of credit applied to invoice
        'created_at',
        'updated_at',
    ];

    // Relationship to the original credit
    public function customerCredit()
    {
        return $this->belongsTo(CustomerCredit::class);
    }

    // Relationship to the invoice where credit is applied
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
