<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'item_id', 'quantity', 'price', 'total',
    ];

    public function order()
    {
        return $this->belongsTo(CustomerOrder::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
