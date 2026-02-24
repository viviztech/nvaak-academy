<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentFeeAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'batch_id',
        'fee_structure_id',
        'concession_type',
        'concession_amount',
        'concession_reason',
        'approved_by',
        'final_amount',
        'is_active',
    ];

    protected $casts = [
        'concession_amount' => 'decimal:2',
        'final_amount'      => 'decimal:2',
        'is_active'         => 'boolean',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function feeStructure()
    {
        return $this->belongsTo(FeeStructure::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function payments()
    {
        return $this->hasMany(FeePayment::class, 'fee_assignment_id');
    }

    public function getTotalPaidAttribute(): float
    {
        return (float) $this->payments()->where('status', 'completed')->sum('amount_paid');
    }

    public function getBalanceAttribute(): float
    {
        return (float) $this->final_amount - $this->total_paid;
    }

    public function getIsFullyPaidAttribute(): bool
    {
        return $this->balance <= 0;
    }
}
