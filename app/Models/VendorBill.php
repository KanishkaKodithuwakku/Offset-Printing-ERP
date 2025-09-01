<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VendorBill extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'date',
        'ref_no',
        'bill_due_date',
        'terms',
        'total_amount',
        'amount_due',
        'payment_type',
        'cheque_number',
        'payment_date',
        'payment_status',
        'memo',
        'created_by',
        'vendor_payment_id'
    ];

    public function payments()
    {
        return $this->hasMany(VendorBillPayment::class, 'vendor_bill_id');
    }

    public function vendor()
    {
        return $this->belongsTo(Supplier::class, 'vendor_id');
    }


    public function vendorBillPayments()
    {
        return $this->hasMany(VendorBillPayment::class, 'vendor_bill_id');
    }


    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function vendorPayment()
    {
        return $this->belongsTo(VendorPayment::class, 'vendor_payment_id');
    }
}
