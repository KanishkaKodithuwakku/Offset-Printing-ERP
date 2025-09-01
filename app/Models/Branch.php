<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Import SoftDeletes trait

class Branch extends Model
{
    use HasFactory, SoftDeletes; // Use SoftDeletes trait for soft deleting functionality

    // The table associated with the model (optional if table name matches the plural of the model name)
    protected $table = 'branches';

    // The attributes that are mass assignable
    protected $fillable = [
        'branch_name',
        'branch_code',
        'location',
        'contact_number',
        'description',
    ];

    // The attributes that should be mutated to dates
    protected $dates = ['deleted_at']; // This is required for soft deletes

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function jobOrder()
    {
        return $this->hasMany(JobOrder::class);
    }
}
