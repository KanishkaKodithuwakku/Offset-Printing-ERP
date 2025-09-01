<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'company_name',
        'branch_id',
        'email',
        'phone',
        'address',
        'status',
    ];

    protected $attributes = [
        'status' => 'active', // Default status
    ];

    public function grns()
    {
        return $this->hasMany(Grn::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
