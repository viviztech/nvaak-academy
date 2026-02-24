<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialAccessLog extends Model
{
    use HasFactory;

    const UPDATED_AT = null;

    protected $fillable = [
        'material_id',
        'student_id',
        'accessed_at',
        'completed',
        'progress_percent',
        'device_type',
        'ip_address',
    ];

    protected $casts = [
        'accessed_at' => 'datetime',
        'completed'   => 'boolean',
    ];

    // Relationships

    public function material()
    {
        return $this->belongsTo(StudyMaterial::class, 'material_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
