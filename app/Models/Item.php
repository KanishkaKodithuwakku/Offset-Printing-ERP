<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Item extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'brands_id',
        'item_code',
        'item_name',
        'item_description',
        'item_short_description',
        'uom',
        'mrp',
        'discount',
        'item_type', // Raw Material or Finished Goods (RW, FG)
        'status',
        'returnable',
        'dimensions',
        'weight',
        'mpn',
        'isbn',
        'upc',
        'ean',
        'sales_price',
        'purchase_price',
        'sales_account',
        'purchase_account',
        'sales_tax',
        'purchase_tax',
        'preferred_vendor',
        'inventory_account',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the brand associated with the item.
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brands_id');
    }

    /**
     * Get all stock records for the item.
     */
    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class, 'items_id');
    }


    /**
     * Get all details associated with the item.
     */
    public function details(): HasMany
    {
        return $this->hasMany(ItemDetail::class);
    }

    /**
     * Get the sales account associated with the item.
     */
    public function salesAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'sales_account');
    }

    /**
     * Get the purchase account associated with the item.
     */
    public function purchaseAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'purchase_account');
    }

    /**
     * Get the sales tax associated with the item.
     */
    public function salesTax(): BelongsTo
    {
        return $this->belongsTo(Tax::class, 'sales_tax');
    }

    /**
     * Get the purchase tax associated with the item.
     */
    public function purchaseTax(): BelongsTo
    {
        return $this->belongsTo(Tax::class, 'purchase_tax');
    }

    /**
     * Get the preferred vendor for the item.
     */
    public function preferredVendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class, 'preferred_vendor');
    }

    /**
     * Get the inventory account associated with the item.
     */
    public function inventoryAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'inventory_account');
    }

    /**
     * Get the user who created the item.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who updated the item.
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function stockMovements()
    {
        return $this->hasMany(Stock::class, 'items_id');
    }
}
