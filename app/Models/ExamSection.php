<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExamSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id',
        'name',
        'total_questions',
        'marks_per_correct',
        'negative_marks',
        'min_questions_to_attempt',
        'order',
    ];

    protected $casts = [
        'total_questions'         => 'integer',
        'marks_per_correct'       => 'float',
        'negative_marks'          => 'float',
        'min_questions_to_attempt'=> 'integer',
        'order'                   => 'integer',
    ];

    // Relationships

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function examQuestions(): HasMany
    {
        return $this->hasMany(ExamQuestion::class);
    }
}
