<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MonthlyTarget extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'month',
        'year',
        'target_amount',
        'description',
        'status',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'target_amount' => 'decimal:2',
        'year' => 'integer'
    ];

    // Relationships
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByYear($query, $year)
    {
        return $query->where('year', $year);
    }

    public function scopeByMonth($query, $month)
    {
        return $query->where('month', $month);
    }

    // Helper methods
    public function getFormattedTargetAmountAttribute()
    {
        return 'Rs.' . number_format($this->target_amount, 2);
    }

    public function getMonthYearAttribute()
    {
        return $this->month . ' ' . $this->year;
    }
}
