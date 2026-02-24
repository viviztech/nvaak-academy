<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'institute_id',
        'admission_id',
        'student_code',
        'date_of_birth',
        'gender',
        'blood_group',
        'address',
        'city',
        'state',
        'parent_name',
        'parent_phone',
        'parent_email',
        'emergency_contact',
        'is_active',
        'joined_at',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'joined_at'     => 'date',
        'is_active'     => 'boolean',
    ];

    // Relationships

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function institute()
    {
        return $this->belongsTo(Institute::class);
    }

    public function batches()
    {
        return $this->belongsToMany(Batch::class, 'batch_students')
            ->withPivot('enrolled_at', 'roll_number', 'status')
            ->withTimestamps();
    }

    public function admission()
    {
        return $this->belongsTo(Admission::class);
    }

    // Accessors

    public function getFullNameAttribute(): string
    {
        return $this->user ? $this->user->name : '';
    }
}
