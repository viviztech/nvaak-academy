<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IasAnswerSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'batch_id',
        'exam_id',
        'subject_id',
        'question_text',
        'submission_type',
        'answer_file_path',
        'answer_text',
        'word_count',
        'time_taken_minutes',
        'submitted_at',
        'status',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
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

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function evaluation()
    {
        return $this->hasOne(IasAnswerEvaluation::class, 'submission_id');
    }

    // Scopes

    public function scopePending($query)
    {
        return $query->whereIn('status', ['submitted', 'under_evaluation']);
    }

    public function scopeEvaluated($query)
    {
        return $query->whereIn('status', ['evaluated', 'returned']);
    }
}
