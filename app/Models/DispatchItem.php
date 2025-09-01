<?php

namespace App\Models;

use App\Livewire\Dispatch\Dispatch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DispatchItem extends Model
{
    use HasFactory;

    // Define the table associated with the model
    protected $table = 'dispatch_items';

    // Define the fillable fields (columns that can be mass-assigned)
    protected $fillable = [
        'item_id',
        'order_id',
        'job_order_item_id',
        'dispatch_id',
        'user_id',
        'quantity',
        'total_amount',
        'status',
        'branch_assigned',
        'branch_done',
    ];

    // Define relationships to other models
    public function dispatchNote()
    {
        return $this->belongsTo(Dispatch::class, 'dispatch_id');
    }

    // Each dispatch item belongs to an item (i.e., a product)
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    // Each dispatch item belongs to a customer order (order_id from the customer order)
    public function order()
    {
        return $this->belongsTo(CustomerOrder::class, 'order_id');
    }

    // Each dispatch item belongs to a job order item (job_order_item_id from job_order_items table)
    public function jobOrderItem()
    {
        return $this->belongsTo(JobOrderItem::class, 'job_order_item_id');
    }

    // Each dispatch item is associated with a user (user who created the dispatch)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
