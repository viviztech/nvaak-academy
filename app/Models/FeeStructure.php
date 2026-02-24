<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FeeStructure extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'institute_id',
        'batch_id',
        'name',
        'description',
        'fee_type',
        'total_amount',
        'installments_allowed',
        'installment_count',
        'installment_plan',
        'valid_from',
        'valid_to',
        'late_fee_per_day',
        'discount_allowed',
        'max_discount_percent',
        'is_active',
    ];

    protected $casts = [
        'installment_plan'      => 'array',
        'installments_allowed'  => 'boolean',
        'discount_allowed'      => 'boolean',
        'is_active'             => 'boolean',
        'total_amount'          => 'decimal:2',
        'valid_from'            => 'date',
        'valid_to'              => 'date',
    ];

    public function institute()
    {
        return $this->belongsTo(Institute::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function installments()
    {
        return $this->hasMany(FeeInstallment::class);
    }

    public function assignments()
    {
        return $this->hasMany(StudentFeeAssignment::class);
    }

    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }
}
