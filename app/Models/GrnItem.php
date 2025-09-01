<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrnItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'grn_id',
        'brands_id',
        'item_id',
        'item_code',
        'item_name',
        'sku_code',
        'item_description',
        'item_short_description',
        'item_type',
        'mrp',
        'uom',
        'returnable',
        'discount',
        'quantity',
        'dimensions',
        'weight',
        'mpn',
        'isbn',
        'upc',
        'ean',
        'selling_price',
        'purchase_price',
        'sales_account',
        'purchase_account',
        'sales_tax',
        'purchase_tax',
        'preferred_vendor',
        'inventory_account',
        'total',
        'status',
    ];

    public function grn()
    {
        return $this->belongsTo(Grn::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
