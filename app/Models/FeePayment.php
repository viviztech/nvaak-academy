<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeePayment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'institute_id',
        'student_id',
        'fee_assignment_id',
        'installment_id',
        'receipt_number',
        'payment_date',
        'amount_paid',
        'payment_mode',
        'razorpay_order_id',
        'razorpay_payment_id',
        'razorpay_signature',
        'transaction_reference',
        'bank_name',
        'cheque_number',
        'cheque_date',
        'status',
        'collected_by',
        'remarks',
    ];

    protected $casts = [
        'amount_paid'  => 'decimal:2',
        'payment_date' => 'date',
        'cheque_date'  => 'date',
    ];

    public function institute()
    {
        return $this->belongsTo(Institute::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function feeAssignment()
    {
        return $this->belongsTo(StudentFeeAssignment::class, 'fee_assignment_id');
    }

    public function installment()
    {
        return $this->belongsTo(FeeInstallment::class);
    }

    public function collectedBy()
    {
        return $this->belongsTo(User::class, 'collected_by');
    }

    public static function generateReceiptNumber(): string
    {
        return 'RCP' . date('Y') . str_pad(static::withTrashed()->count() + 1, 6, '0', STR_PAD_LEFT);
    }
}
