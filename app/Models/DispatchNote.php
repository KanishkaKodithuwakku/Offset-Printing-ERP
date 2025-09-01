<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DispatchNote extends Model
{

    protected $fillable = ['job_order_id','dispatch_number', 'quantity','balance_qty','description', 'dispatched_at','user_id'];

    public function jobOrder()
    {
        return $this->belongsTo(JobOrder::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function dispatchItems()
    {
        return $this->hasMany(DispatchItem::class, 'dispatch_id');
    }


}
