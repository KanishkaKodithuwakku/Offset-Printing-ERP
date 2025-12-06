<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillDeletionApproval extends Model
{
    protected $fillable = [
        'vendor_bill_id',
        'bill_date',
        'vendor_name',
        'ref_no',
        'total_amount',
        'requested_by',
        'reason',
        'status',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    public function vendorBill()
    {
        return $this->belongsTo(VendorBill::class, 'vendor_bill_id');
    }

    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
