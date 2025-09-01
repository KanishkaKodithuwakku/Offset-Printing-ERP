<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorBillPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_bill_id',
        'ledger_id',
        'amount',
        'payment_date',
        'remark',
        'created_by'
    ];

    /**
     * The bill this payment belongs to.
     */
    public function vendorBill()
    {
        return $this->belongsTo(VendorBill::class, 'vendor_bill_id');
    }

    // Relation with Ledger (e.g., bank)
    public function ledger()
    {
        return $this->belongsTo(Ledger::class, 'ledger_id');
    }
    // Created by user
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
