<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EntryItem extends Model
{
    protected $table = 'entryitems';

    protected $fillable = [
        'entry_id',
        'ledger_id',
        'customer_id',
        'amount',
        'dc',
        'reconciliation_date',
    ];

    protected $dates = ['reconciliation_date'];

   
    public function entry()
    {
        return $this->belongsTo(Entry::class);
    }

    public function ledger()
    {
        return $this->belongsTo(Ledger::class);
    }

    /*
     * Accessors
     */
    public function isDebit()
    {
        return $this->dc === 'D';
    }

    public function isCredit()
    {
        return $this->dc === 'C';
    }
}
