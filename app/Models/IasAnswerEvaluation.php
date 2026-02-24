<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IasAnswerEvaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'submission_id',
        'evaluator_id',
        'evaluated_at',
        'total_marks',
        'marks_awarded',
        'content_score',
        'language_score',
        'structure_score',
        'analytical_score',
        'general_feedback',
        'strengths',
        'improvements_needed',
        'annotated_file_path',
        'model_answer_path',
        'is_returned_to_student',
        'returned_at',
    ];

    protected $casts = [
        'evaluated_at'           => 'datetime',
        'returned_at'            => 'datetime',
        'marks_awarded'          => 'decimal:2',
        'content_score'          => 'decimal:2',
        'language_score'         => 'decimal:2',
        'structure_score'        => 'decimal:2',
        'analytical_score'       => 'decimal:2',
        'is_returned_to_student' => 'boolean',
    ];

    // Relationships

    public function submission()
    {
        return $this->belongsTo(IasAnswerSubmission::class, 'submission_id');
    }

    public function evaluator()
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }
}
