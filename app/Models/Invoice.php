<?php

namespace App\Models;

use App\Models\PaidInvoice;
use App\Models\PostdatedCheque;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_id',
        'order_type',
        'customer_id',
        'branch_id',
        'invoice_number',
        'advance_payment',
        'payment_method',
        'bank_name',
        'payment_method',
        'cheque_no',
        'cheque_realization_date',
        'invoice_details',
        'total_amount',
        'amount_due',
        'status',
        'invoice_details',
        'deleted_by',
        'cancel_reason',
        'backed_plates_price',
         'is_used'
    ];

    /**
     * Get the order that owns the invoice.
     */

    public function entry()
    {
        return $this->belongsTo(Entry::class, 'entry_id');
    }

    public function order()
    {
        return $this->morphTo();
    }

    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
     public function postdatedCheques()
    {
        return $this->belongsToMany(PostdatedCheque::class, 'paid_invoices')
                    ->withPivot('amount_paid')
                    ->using(PaidInvoice::class);
    }
}
