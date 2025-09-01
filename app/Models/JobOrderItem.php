<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'item_id', 'quantity', 'price', 'total',
    ];

    public function order()
    {
        return $this->belongsTo(JobOrder::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
