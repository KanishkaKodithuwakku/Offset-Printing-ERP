<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorPaymentDetail extends Model
{
    protected $fillable = [
        'vendor_payment_id', 'vendor_bill_id', 'amount', 'branch_id',
    ];

    public function vendorPayment()
    {
        return $this->belongsTo(VendorPayment::class);
    }

    public function vendorBill()
    {
        return $this->belongsTo(VendorBill::class);
    }
}
