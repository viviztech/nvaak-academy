<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Batch extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'institute_id',
        'course_type',
        'batch_type',
        'name',
        'code',
        'description',
        'academic_year',
        'medium',
        'max_strength',
        'current_strength',
        'start_date',
        'end_date',
        'schedule_days',
        'schedule_time_from',
        'schedule_time_to',
        'class_room',
        'is_active',
        'coordinator_id',
    ];

    protected $casts = [
        'schedule_days' => 'array',
        'is_active'     => 'boolean',
        'start_date'    => 'date',
        'end_date'      => 'date',
    ];

    // Relationships

    public function institute()
    {
        return $this->belongsTo(Institute::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'batch_students')
            ->withPivot('enrolled_at', 'roll_number', 'status')
            ->withTimestamps();
    }

    public function faculty()
    {
        return $this->belongsToMany(Faculty::class, 'batch_faculty')
            ->withPivot('subject_id', 'is_primary', 'assigned_from', 'assigned_to')
            ->withTimestamps();
    }

    public function coordinator()
    {
        return $this->belongsTo(User::class, 'coordinator_id');
    }

    public function syllabusCoverage()
    {
        return $this->hasMany(SyllabusCoverage::class);
    }

    // Scopes

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeNeet(Builder $query): Builder
    {
        return $query->where('course_type', 'neet');
    }

    public function scopeIas(Builder $query): Builder
    {
        return $query->where('course_type', 'ias');
    }
}
