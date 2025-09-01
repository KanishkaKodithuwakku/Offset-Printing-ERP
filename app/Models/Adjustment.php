<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adjustment extends Model
{
    use HasFactory;

    protected $fillable = [
        'reason',
        'status',
        'created_by',
    ];

   

    public function adjustmentItems()
    {
        return $this->hasMany(AdjustmentItem::class);
    }
}
