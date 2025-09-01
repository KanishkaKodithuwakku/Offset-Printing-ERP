<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasFactory;

    // Allow mass assignment for these fields
    protected $fillable = [
        'invoice_id',
        'item_id',
        'quantity',
        'unit_price',
        'total_price',
    ];

    /**
     * Get the invoice that owns the invoice item.
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Get the product associated with the invoice item.
     */
    public function item()
    {
        return $this->belongsTo(Item::class); // Assuming you have a Product model
    }
}
