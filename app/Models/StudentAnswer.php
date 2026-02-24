<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_exam_id',
        'question_id',
        'given_answer',
        'is_correct',
        'is_skipped',
        'is_marked_for_review',
        'marks_awarded',
        'time_spent_seconds',
    ];

    protected $casts = [
        'given_answer'        => 'array',
        'is_correct'          => 'boolean',
        'is_skipped'          => 'boolean',
        'is_marked_for_review'=> 'boolean',
        'marks_awarded'       => 'float',
        'time_spent_seconds'  => 'integer',
    ];

    // Relationships

    public function studentExam(): BelongsTo
    {
        return $this->belongsTo(StudentExam::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
