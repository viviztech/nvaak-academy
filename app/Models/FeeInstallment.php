<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeInstallment extends Model
{
    use HasFactory;

    protected $fillable = [
        'fee_structure_id',
        'installment_number',
        'name',
        'amount',
        'due_date',
    ];

    protected $casts = [
        'amount'   => 'decimal:2',
        'due_date' => 'date',
    ];

    public function feeStructure()
    {
        return $this->belongsTo(FeeStructure::class);
    }

    public function payments()
    {
        return $this->hasMany(FeePayment::class, 'installment_id');
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->due_date->isPast()
            && ! $this->payments()->where('status', 'completed')->exists();
    }
}
