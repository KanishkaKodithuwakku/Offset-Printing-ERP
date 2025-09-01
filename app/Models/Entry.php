<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Entry extends Model
{
    // use SoftDeletes;

    protected $table = 'entries';

    protected $fillable = [
        'tag_id',
        'entrytype_id',
        'customer_id',
        'number',
        'date',
        'dr_total',
        'cr_total',
        'narration',
    ];

    protected $dates = ['date'];


    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }

    public function entryType()
    {
        return $this->belongsTo(EntryType::class, 'entrytype_id');
    }

    public function customer(){
        return $this->belongsTo(Customer::class);
    }
     public function vendor()
    {
        return $this->belongsTo(Supplier::class, 'customer_id');
    }

    public function entryitems()
    {
        return $this->hasMany(EntryItem::class);
    }

    /*
     * Custom Scopes or Helpers
     */
    public function scopeOfType($query, $entryTypeId)
    {
        return $query->where('entrytype_id', $entryTypeId);
    }

    /*
     * Utility for next number
     */
    public static function getNextNumber($entryTypeId)
    {
        $max = self::where('entrytype_id', $entryTypeId)->max('number');
        return $max ? $max + 1 : 1;
    }
}
