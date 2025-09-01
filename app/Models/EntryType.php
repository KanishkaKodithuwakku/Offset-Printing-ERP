<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EntryType extends Model
{
    protected $table = 'entrytypes';
    public $timestamps = false;

    protected $fillable = [
        'label',
        'name',
        'description',
        'base_type',
        'numbering',
        'prefix',
        'suffix',
        'zero_padding',
        'restriction_bankcash',
    ];


    public function entries()
    {
        return $this->hasMany(Entry::class);
    }

    /*
     * Helpers
     */
    public function getFormattedNumber($number)
    {
        return $this->prefix .
            str_pad($number, $this->zero_padding, '0', STR_PAD_LEFT) .
            $this->suffix;
    }

    public function getRestrictionLabelAttribute()
    {
        return [
            0 => 'Unrestricted',
            1 => 'At least one Bank or Cash account must be present on Debit side',
            2 => 'At least one Bank or Cash account must be present on Credit side',
            3 => 'Only Bank or Cash account can be present on both sides',
            4 => 'Only Non Bank or Non Cash account can be present on both sides',
        ][$this->restriction_bankcash] ?? 'Unknown';
    }
}
