<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'customer_id',
        'payment_code',
        'user_id',
        'branch_id',
        'bank_id',
        'bank_branch_id',
        'entry_id',
        'amount',
        'date',
        'cheque_date',
        'method',
        'check_number',
        'status',
        'deleted_by',
        'cancel_reason',
        'memo'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Supplier::class, 'customer_id');
    }

    public function paymentDetails()
    {
        return $this->hasMany(PaymentDetail::class);
    }

    public function entry()
    {
        return $this->belongsTo(Entry::class);
    }


    public function customerCredit()
    {
        return $this->hasOne(CustomerCredit::class);
    }

    public function creditApplications()
    {
        return $this->hasManyThrough(
            CreditApplication::class,
            CustomerCredit::class,
            'payment_id',           // Foreign key on CustomerCredit table
            'customer_credit_id',   // Foreign key on CreditApplication table
            'id',                   // Local key on Payment table
            'id'                    // Local key on CustomerCredit table
        );
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }

    public function bankBranch()
    {
        return $this->belongsTo(BankBranch::class, 'bank_branch_id');
    }
}

