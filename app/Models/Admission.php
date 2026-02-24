<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Admission extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'institute_id',
        'enquiry_id',
        'application_number',
        'application_step',
        'first_name',
        'middle_name',
        'last_name',
        'date_of_birth',
        'gender',
        'blood_group',
        'aadhaar_number',
        'nationality',
        'religion',
        'caste_category',
        'email',
        'phone',
        'alternate_phone',
        'current_address',
        'permanent_address',
        'city',
        'state',
        'postal_code',
        'course_applied',
        'batch_id',
        'previous_institution',
        'board',
        'previous_percentage',
        'year_of_passing',
        'neet_previous_score',
        'neet_previous_rank',
        'father_name',
        'father_occupation',
        'father_phone',
        'father_email',
        'father_income',
        'mother_name',
        'mother_occupation',
        'mother_phone',
        'mother_email',
        'guardian_name',
        'guardian_relation',
        'guardian_phone',
        'documents',
        'photo_path',
        'status',
        'submitted_at',
        'reviewed_at',
        'approved_at',
        'reviewed_by',
        'admin_remarks',
        'rejection_reason',
        'application_fee',
        'application_fee_paid',
        'payment_transaction_id',
        'source',
        'additional_info',
    ];

    protected $casts = [
        'documents'           => 'array',
        'additional_info'     => 'array',
        'application_fee_paid' => 'boolean',
        'date_of_birth'       => 'date',
        'submitted_at'        => 'datetime',
        'reviewed_at'         => 'datetime',
        'approved_at'         => 'datetime',
        'application_fee'     => 'float',
    ];

    // Relationships

    public function institute()
    {
        return $this->belongsTo(Institute::class);
    }

    public function enquiry()
    {
        return $this->belongsTo(Enquiry::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function reviewedBy()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function documents()
    {
        return $this->hasMany(AdmissionDocument::class);
    }

    public function student()
    {
        return $this->hasOne(Student::class);
    }

    // Static methods

    public static function generateApplicationNumber(): string
    {
        return 'APP' . date('Y') . str_pad(static::withTrashed()->count() + 1, 6, '0', STR_PAD_LEFT);
    }

    // Scopes

    public function scopePending(Builder $query): Builder
    {
        return $query->whereIn('status', ['submitted', 'under_review']);
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', 'approved');
    }

    public function scopeAdmitted(Builder $query): Builder
    {
        return $query->where('status', 'admitted');
    }
}
