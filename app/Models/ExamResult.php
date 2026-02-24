<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_exam_id',
        'exam_id',
        'student_id',
        'batch_id',
        'total_marks',
        'marks_obtained',
        'correct_answers',
        'wrong_answers',
        'unattempted',
        'percentage',
        'pass_fail',
        'rank_in_batch',
        'rank_overall',
        'time_taken_seconds',
        'subject_wise_scores',
        'is_published',
    ];

    protected $casts = [
        'total_marks'        => 'float',
        'marks_obtained'     => 'float',
        'correct_answers'    => 'integer',
        'wrong_answers'      => 'integer',
        'unattempted'        => 'integer',
        'percentage'         => 'float',
        'rank_in_batch'      => 'integer',
        'rank_overall'       => 'integer',
        'time_taken_seconds' => 'integer',
        'subject_wise_scores'=> 'array',
        'is_published'       => 'boolean',
    ];

    // Relationships

    public function studentExam(): BelongsTo
    {
        return $this->belongsTo(StudentExam::class);
    }

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }

    // Accessors

    public function getPassFailColorAttribute(): string
    {
        return $this->pass_fail === 'pass'
            ? 'bg-green-100 text-green-700'
            : 'bg-red-100 text-red-700';
    }
}
