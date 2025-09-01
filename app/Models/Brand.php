<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['brand_name', 'brand_description', 'logo', 'status'];

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }
}
