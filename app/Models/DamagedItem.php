<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DamagedItem extends Model
{
    protected $fillable = [
        'job_number',
        'job_id',
        'customer_id',
        'item_id',
        'quantity',
        'reason',
        'status',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
     public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
    public function order()
    {
        return $this->belongsTo(JobOrder::class, 'job_id');
    }

}