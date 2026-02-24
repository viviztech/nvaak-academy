<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Question extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'question_bank_id',
        'subject_id',
        'chapter_id',
        'topic_id',
        'subtopic_id',
        'question_type',
        'question_text',
        'question_text_html',
        'question_image',
        'options',
        'correct_answer',
        'answer_range_from',
        'answer_range_to',
        'explanation',
        'explanation_html',
        'explanation_image',
        'difficulty',
        'marks',
        'negative_marks',
        'tags',
        'year_asked',
        'source',
        'is_verified',
        'verified_by',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'options'        => 'array',
        'correct_answer' => 'array',
        'tags'           => 'array',
        'is_verified'    => 'boolean',
        'is_active'      => 'boolean',
        'marks'          => 'integer',
        'negative_marks' => 'float',
    ];

    // Relationships

    public function questionBank(): BelongsTo
    {
        return $this->belongsTo(QuestionBank::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // Scopes

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeByDifficulty(Builder $query, string $level): Builder
    {
        return $query->where('difficulty', $level);
    }

    public function scopeByType(Builder $query, string $type): Builder
    {
        return $query->where('question_type', $type);
    }

    public function scopeVerified(Builder $query): Builder
    {
        return $query->where('is_verified', true);
    }

    // Accessors

    public function getDifficultyColorAttribute(): string
    {
        return match ($this->difficulty) {
            'easy'      => 'bg-green-100 text-green-800',
            'medium'    => 'bg-yellow-100 text-yellow-800',
            'hard'      => 'bg-orange-100 text-orange-800',
            'very_hard' => 'bg-red-100 text-red-800',
            default     => 'bg-gray-100 text-gray-800',
        };
    }

    public function getTypeColorAttribute(): string
    {
        return match ($this->question_type) {
            'mcq_single'      => 'bg-blue-100 text-blue-800',
            'mcq_multiple'    => 'bg-indigo-100 text-indigo-800',
            'numerical'       => 'bg-purple-100 text-purple-800',
            'match_following' => 'bg-pink-100 text-pink-800',
            'assertion_reason'=> 'bg-cyan-100 text-cyan-800',
            'true_false'      => 'bg-teal-100 text-teal-800',
            default           => 'bg-gray-100 text-gray-800',
        };
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->question_type) {
            'mcq_single'       => 'MCQ Single',
            'mcq_multiple'     => 'MCQ Multiple',
            'numerical'        => 'Numerical',
            'match_following'  => 'Match Following',
            'assertion_reason' => 'Assertion Reason',
            'true_false'       => 'True/False',
            default            => 'Unknown',
        };
    }

    // Methods

    public function isAnsweredCorrectly(mixed $givenAnswer): bool
    {
        if ($this->question_type !== 'mcq_single') {
            return false;
        }

        $correct = $this->correct_answer;
        $correctKey = $correct[0] ?? null;

        if (is_array($givenAnswer)) {
            return in_array($correctKey, $givenAnswer);
        }

        return (string) $givenAnswer === (string) $correctKey;
    }
}
