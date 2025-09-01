<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Grn extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'grn_code',
        'order_type',
        'order_id',
        'supplier_id',
        'branch_id',
        'grn_date',
        'eff_date',
        'total_amount',
        'status',
        'remark',
        'delivery_date',
        'delivery_location',
        'delivery_remark',
        'grn_type',
        'user_id'
    ];

    protected $attributes = [
        'supplier_id' => 1,
        'status' => 'pending',
    ];

    public function user()
    {
        return $this->belongsTo(User::class); // Assuming 'user_id' is the foreign key
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function grnItems()
    {
        return $this->hasMany(GrnItem::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
