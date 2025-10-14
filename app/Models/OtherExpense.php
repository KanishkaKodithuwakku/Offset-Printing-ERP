<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtherExpense extends Model
{
    protected $fillable = [
        'expense_name',
        'description',
        'price',
    ];
}
