<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Institute extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'logo_path',
        'address',
        'city',
        'state',
        'phone',
        'email',
        'website',
        'type',
        'settings',
        'is_active',
    ];

    protected $casts = [
        'settings'  => 'array',
        'is_active' => 'boolean',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function batches()
    {
        return $this->hasMany(Batch::class);
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    public function enquiries()
    {
        return $this->hasMany(Enquiry::class);
    }

    public function admissions()
    {
        return $this->hasMany(Admission::class);
    }
}
