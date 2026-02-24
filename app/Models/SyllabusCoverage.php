<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SyllabusCoverage extends Model
{
    use HasFactory;

    protected $table = 'syllabus_coverage';

    protected $fillable = [
        'batch_id',
        'subject_id',
        'chapter_id',
        'topic_id',
        'faculty_id',
        'covered_on',
        'coverage_percentage',
        'notes',
        'class_duration_minutes',
        'students_present',
    ];

    protected $casts = [
        'covered_on'              => 'date',
        'coverage_percentage'     => 'integer',
        'class_duration_minutes'  => 'integer',
        'students_present'        => 'integer',
    ];

    // Relationships

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function faculty()
    {
        return $this->belongsTo(User::class, 'faculty_id');
    }
}
