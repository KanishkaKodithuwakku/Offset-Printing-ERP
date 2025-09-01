<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerCredit extends Model
{
    use HasFactory;

    protected $table = 'customer_credits';

    protected $fillable = [
        'customer_id',
        'amount',         // Credit amount available
        'balance',        // Remaining balance after partial usage
        'description'
    ];

    // Relationship to customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Scope to only active credits with remaining balance


    /**
     * This is a local query scope in Laravel.
     * The method name starts with scope and then Available (so call it as available() on the model's query builder)
     * $query is the current Eloquent query builder instance passed automatically use the scope.
     * Inside, it modifies the query by adding a WHERE condition:
     * It filters the records to only include those where the balance column is greater than zero.
     * can use this scope when querying CustomerCredit like this:
     * $availableCredits = CustomerCredit::available()->get();
     * This will fetch all CustomerCredit records whose balance is greater than zero — meaning credits that still have some unused balance left.
     * 
     * Why is this useful?
     * It gives a readable, reusable way to filter only "active" or "usable" credits.
     * Instead of repeating the where('balance', '>', 0) condition everywhere, you write it once in the model.
     * Improves code clarity and reduces duplication.
     * 
     * get all credits regardless of balance.
     * CustomerCredit::all();
     * 
     * returns only credits with some remaining balance.
     * CustomerCredit::available()->get();
     * 
     */


    public function scopeAvailable($query)
    {
        return $query->where('balance', '>', 0);
    }

    public function applications()
    {
        return $this->hasMany(CreditApplication::class);
    }
}
