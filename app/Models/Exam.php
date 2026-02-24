<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exam extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'institute_id',
        'exam_series_id',
        'batch_id',
        'subject_id',
        'name',
        'code',
        'description',
        'exam_type',
        'course_type',
        'status',
        'start_time',
        'end_time',
        'duration_minutes',
        'total_marks',
        'passing_marks',
        'max_attempts',
        'negative_marking_enabled',
        'randomize_questions',
        'randomize_options',
        'show_results_immediately',
        'allow_review_after_submit',
        'show_correct_answers',
        'prevent_tab_switch',
        'created_by',
    ];

    protected $casts = [
        'start_time'               => 'datetime',
        'end_time'                 => 'datetime',
        'negative_marking_enabled' => 'boolean',
        'randomize_questions'      => 'boolean',
        'randomize_options'        => 'boolean',
        'show_results_immediately' => 'boolean',
        'allow_review_after_submit'=> 'boolean',
        'show_correct_answers'     => 'boolean',
        'prevent_tab_switch'       => 'boolean',
        'duration_minutes'         => 'integer',
        'total_marks'              => 'integer',
        'passing_marks'            => 'integer',
        'max_attempts'             => 'integer',
    ];

    // Relationships

    public function institute(): BelongsTo
    {
        return $this->belongsTo(Institute::class);
    }

    public function examSeries(): BelongsTo
    {
        return $this->belongsTo(ExamSeries::class);
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function sections(): HasMany
    {
        return $this->hasMany(ExamSection::class)->orderBy('order');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(ExamQuestion::class)->orderBy('order');
    }

    public function studentExams(): HasMany
    {
        return $this->hasMany(StudentExam::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(ExamResult::class);
    }

    // Scopes

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function scopeLive(Builder $query): Builder
    {
        return $query->where('status', 'live');
    }

    public function scopeByStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    // Accessors

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'draft'     => 'bg-gray-100 text-gray-700',
            'published' => 'bg-blue-100 text-blue-700',
            'live'      => 'bg-green-100 text-green-700',
            'completed' => 'bg-red-100 text-red-700',
            default     => 'bg-gray-100 text-gray-700',
        };
    }

    public function getIsLiveAttribute(): bool
    {
        return $this->status === 'live' &&
               now()->between($this->start_time, $this->end_time);
    }
}
