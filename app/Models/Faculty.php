<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Faculty extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'faculty';

    protected $fillable = [
        'user_id',
        'institute_id',
        'employee_code',
        'designation',
        'specialization',
        'qualification',
        'experience_years',
        'joining_date',
        'is_active',
    ];

    protected $casts = [
        'specialization' => 'array',
        'joining_date'   => 'date',
        'is_active'      => 'boolean',
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
        return $this->belongsToMany(Batch::class, 'batch_faculty')
            ->withPivot('subject_id', 'is_primary', 'assigned_from', 'assigned_to')
            ->withTimestamps();
    }
}
