<?php

namespace App\Models;

use App\Models\Invoice;
use App\Models\PaidInvoice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PostdatedCheque extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'cheque_number',
        'bank_name',
        'branch_name',
        'amount',
        'cheque_date',
        'status',
        'details'
    ];

    protected $casts = [
        'cheque_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function paidInvoices()
    {
        return $this->hasMany(PaidInvoice::class);
    }

    public function invoices()
    {
        return $this->belongsToMany(Invoice::class, 'paid_invoices')
                    ->withPivot('amount_paid')
                    ->using(PaidInvoice::class);
    }
    public function customer()
{
    return $this->belongsTo(Customer::class, 'customer_id');
}
}