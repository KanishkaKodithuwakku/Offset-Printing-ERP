<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorPayment extends Model
{
    protected $fillable = [
        'vendor_id', 'payment_date', 'receipt_number', 'payment_method',
        'check_number', 'check_date', 'total_amount', 'bank_ledger_id',
        'payment_voucher_no',
    ];

    public function vendor()
    {
        return $this->belongsTo(Supplier::class, 'vendor_id');
    }

    public function paymentDetails()
    {
        return $this->hasMany(VendorPaymentDetail::class, 'vendor_payment_id');
    }

    public function vendorBills()
    {
        return $this->hasMany(VendorBill::class, 'vendor_payment_id');
    }

    public function bankLedger()
    {
        return $this->belongsTo(Ledger::class, 'bank_ledger_id');
    }
}
