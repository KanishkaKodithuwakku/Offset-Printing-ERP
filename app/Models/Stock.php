<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stock extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'brands_id',
        'items_id',
        'supplier_id',
        'branch_id',
        'user_id',
        'quantity',
        'sales_price',
        'purchase_price',
        'mrp',
        'p_id',
        'f_id',
        'table_name',
        'effective_date',
        'dimensions',
        'weight',
        'sku_code',
        'purchase_date',
        'online'
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brands_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branches_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'items_id');
    }
}
