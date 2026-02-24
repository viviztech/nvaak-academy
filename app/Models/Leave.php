<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'batch_id',
        'leave_type',
        'from_date',
        'to_date',
        'total_days',
        'reason',
        'document_path',
        'status',
        'applied_at',
        'approved_by',
        'remarks',
    ];

    protected $casts = [
        'from_date'  => 'date',
        'to_date'    => 'date',
        'applied_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function scopePending($q)
    {
        return $q->where('status', 'pending');
    }

    public function scopeApproved($q)
    {
        return $q->where('status', 'approved');
    }

    public function scopeRejected($q)
    {
        return $q->where('status', 'rejected');
    }
}
