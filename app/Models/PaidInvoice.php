<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PaidInvoice extends Pivot
{
    protected $table = 'paid_invoices';

    protected $fillable = [
        'postdated_cheque_id',
        'invoice_id',
        'amount_paid'
    ];

    protected $casts = [
        'amount_paid' => 'decimal:2',
    ];

    // Since this extends Pivot, we don't need to define relationships here
    // They're defined in the main models
    public function invoice()
{
    return $this->belongsTo(Invoice::class, 'invoice_id');
}
}