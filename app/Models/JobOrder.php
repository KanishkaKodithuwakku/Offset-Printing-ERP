<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class JobOrder extends Model
{
    use SoftDeletes;
    protected $table = 'job_orders';

    protected $fillable = [
        'job_number',
        'customer_po_number',
        'date_created',
        'customer_id',
        'user_id',
        'branch_id',
        'invoice_branch',
        'assign_to',
        'description',
        'special_instruction',
        'job_done_by',
        'job_checked_by',
        'delivery_date',
        'status',
        'previous_status',
        'plate_backing',
        'backing_qty',
        'print_count',
        'reorder_count',
        'total_amount'
    ];

    protected $casts = [
        'delivery_date' => 'datetime',
    ];


    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function assignTo()
    {
        return $this->belongsTo(User::class, 'assign_to');
    }


    public function jobDoneBy()
    {
        return $this->belongsTo(User::class, 'job_done_by');
    }


    public function jobCheckedBy()
    {
        return $this->belongsTo(User::class, 'job_checked_by');
    }

    public function dispatchNotes()
    {
        return $this->hasMany(DispatchNote::class);
    }


    public function orderItems()
    {
        return $this->hasMany(JobOrderItem::class, 'order_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function invoiceBranch()
    {
        return $this->belongsTo(Branch::class, 'invoice_branch');
    }

    /**
     * Check if all items in this job order are dispatched
     *
     * @return bool
     */
    public function isFullyDispatched()
    {
        return $this->orderItems()
            ->where('status', '!=', 'dispatched')
            ->doesntExist();
    }

    /**
     * Check if any items in this job order are dispatched
     *
     * @return bool
     */
    public function hasDispatchedItems()
    {
        return $this->orderItems()
            ->where('status', '=', 'dispatched')
            ->exists();
    }

    /**
     * Update job order status based on dispatch items status
     *
     * @return void
     */
    public function updateDispatchStatus()
    {
        if ($this->isFullyDispatched()) {
            $this->update(['status' => 'dispatched']);
            
            // Update all related dispatch notes to dispatched
            $this->dispatchNotes()->update(['status' => 'dispatched']);
        } elseif ($this->hasDispatchedItems()) {
            $this->update(['status' => 'dispatching']);
        }
    }

    /**
     * Get total ordered quantity for all items
     *
     * @return int
     */
    public function getTotalOrderedQuantity()
    {
        return $this->orderItems()->sum('quantity');
    }

    /**
     * Get total dispatched quantity for all items
     *
     * @return int
     */
    public function getTotalDispatchedQuantity()
    {
        return DB::table('dispatch_items')
            ->whereIn('job_order_item_id', $this->orderItems()->pluck('id'))
            ->sum('quantity');
    }
}
