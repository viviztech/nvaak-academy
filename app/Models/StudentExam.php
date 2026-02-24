<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class StudentExam extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id',
        'student_id',
        'batch_id',
        'attempt_number',
        'status',
        'started_at',
        'submitted_at',
        'time_taken_seconds',
        'ip_address',
        'tab_switch_count',
    ];

    protected $casts = [
        'started_at'       => 'datetime',
        'submitted_at'     => 'datetime',
        'attempt_number'   => 'integer',
        'time_taken_seconds' => 'integer',
        'tab_switch_count' => 'integer',
    ];

    // Relationships

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

    public function answers(): HasMany
    {
        return $this->hasMany(StudentAnswer::class);
    }

    public function result(): HasOne
    {
        return $this->hasOne(ExamResult::class);
    }
}
