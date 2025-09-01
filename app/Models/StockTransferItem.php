<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockTransferItem extends Model
{
    use HasFactory;

    protected $table = 'stock_transfer_items';

    protected $fillable = [
        'stock_transfer_id',
        'item_id',
        'order_id',
        'order_item_id',
        'quantity',
        'price',
        'total',
        'status',
        'transferred_quantity',
        'partially_added_quantity',
    ];

    /**
     * Relationships
     */

    public function stockTransferNote()
    {
        return $this->belongsTo(StockTransferNote::class, 'stock_transfer_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function order()
    {
        return $this->belongsTo(JobOrder::class, 'order_id');
    }

    public function orderItem()
    {
        return $this->belongsTo(JobOrderItem::class, 'order_item_id');
    }
}
