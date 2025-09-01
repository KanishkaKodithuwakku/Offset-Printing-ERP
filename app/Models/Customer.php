<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name', 'branch_id','ledger_id','email', 'phone', 'mobile_number', 'address', 'city', 'country', 'status',
        'customer_number',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function ledger()
    {
        return $this->belongsTo(Ledger::class);
    }

    public function invoices()
    {
        return $this->hasMany(\App\Models\Invoice::class, 'customer_id');
    }
}
