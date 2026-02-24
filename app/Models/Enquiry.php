<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Enquiry extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'institute_id',
        'source',
        'first_name',
        'last_name',
        'email',
        'phone',
        'alternate_phone',
        'city',
        'state',
        'date_of_birth',
        'gender',
        'course_interest',
        'batch_interest',
        'academic_background',
        'previous_marks',
        'current_school_college',
        'query_notes',
        'referral_name',
        'assigned_to',
        'status',
        'priority',
        'source_campaign',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    // Relationships

    public function institute()
    {
        return $this->belongsTo(Institute::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function followUps()
    {
        return $this->hasMany(EnquiryFollowUp::class);
    }

    public function admission()
    {
        return $this->hasOne(Admission::class, 'enquiry_id');
    }

    // Scopes

    public function scopeNew(Builder $query): Builder
    {
        return $query->where('status', 'new');
    }

    public function scopeConverted(Builder $query): Builder
    {
        return $query->where('status', 'converted');
    }

    public function scopeMyLeads(Builder $query, int $userId): Builder
    {
        return $query->where('assigned_to', $userId);
    }

    public function scopeTodayFollowUps(Builder $query): Builder
    {
        return $query->whereHas('followUps', function (Builder $q) {
            $q->whereDate('next_follow_up_at', today());
        });
    }

    // Accessors

    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
