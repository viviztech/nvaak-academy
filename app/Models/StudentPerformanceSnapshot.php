<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentPerformanceSnapshot extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'batch_id',
        'snapshot_date',
        'snapshot_type',
        'overall_score_percent',
        'rank_in_batch',
        'rank_overall',
        'predicted_neet_score',
        'predicted_neet_rank',
        'subject_scores',
        'strong_chapters',
        'weak_chapters',
        'attendance_percent',
        'exams_attempted',
        'improvement_from_last',
    ];

    protected $casts = [
        'snapshot_date'         => 'date',
        'subject_scores'        => 'array',
        'strong_chapters'       => 'array',
        'weak_chapters'         => 'array',
        'overall_score_percent' => 'decimal:2',
        'attendance_percent'    => 'decimal:2',
        'improvement_from_last' => 'decimal:2',
    ];

    // Relationships

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    // Scopes

    public function scopeLatest($query)
    {
        return $query->orderByDesc('snapshot_date');
    }
}
