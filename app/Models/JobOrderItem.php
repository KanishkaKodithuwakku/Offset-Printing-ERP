<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    /**
     * Get the total dispatched quantity for this job order item
     *
     * @return int
     */
    public function getDispatchedQuantity()
    {
        return DB::table('dispatch_items')
            ->where('job_order_item_id', $this->id)
            ->sum('quantity');
    }

    /**
     * Get the remaining quantity to be dispatched
     *
     * @return int
     */
    public function getRemainingQuantity()
    {
        return $this->quantity - $this->getDispatchedQuantity();
    }

    /**
     * Check if this job order item is fully dispatched
     *
     * @return bool
     */
    public function isFullyDispatched()
    {
        return $this->getDispatchedQuantity() >= $this->quantity;
    }
}
