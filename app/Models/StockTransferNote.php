<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockTransferNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'transfer_code',
        'from_branch_id',
        'to_branch_id',
        'job_order_id',
        'user_id',
        'accepted_by',
        'status',
        'quantity',
        'remark',
    ];

    /**
     * Relationships
     */

    public function fromBranch()
    {
        return $this->belongsTo(Branch::class, 'from_branch_id');
    }

    public function toBranch()
    {
        return $this->belongsTo(Branch::class, 'to_branch_id');
    }

    public function job()
    {
        return $this->belongsTo(JobOrder::class, 'job_order_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function acceptedBy()
    {
        return $this->belongsTo(User::class, 'accepted_by');
    }

    public function items()
    {
        return $this->hasMany(StockTransferItem::class, 'stock_transfer_id');
    }

    /**
     * Accessors
     */

    // public function getItemsCountAttribute()
    // {
    //     return $this->items()->count();
    // }

    public function getFromWarehouseAttribute()
    {
        return $this->fromBranch?->name ?? 'N/A';
    }

    public function getToWarehouseAttribute()
    {
        return $this->toBranch?->name ?? 'N/A';
    }
}
