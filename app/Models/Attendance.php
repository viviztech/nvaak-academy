<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'institute_id',
        'batch_id',
        'student_id',
        'subject_id',
        'faculty_id',
        'date',
        'status',
        'check_in_time',
        'remarks',
        'marked_by',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function institute()
    {
        return $this->belongsTo(Institute::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function faculty()
    {
        return $this->belongsTo(User::class, 'faculty_id');
    }

    public function markedBy()
    {
        return $this->belongsTo(User::class, 'marked_by');
    }

    public function scopePresent($q)
    {
        return $q->where('status', 'present');
    }

    public function scopeAbsent($q)
    {
        return $q->where('status', 'absent');
    }

    public function scopeForDate($q, $date)
    {
        return $q->whereDate('date', $date);
    }

    public function scopeForBatch($q, $batchId)
    {
        return $q->where('batch_id', $batchId);
    }

    public static function getAttendancePercentage(int $studentId, int $batchId, $from = null, $to = null): float
    {
        $query = static::where('student_id', $studentId)->where('batch_id', $batchId);

        if ($from) {
            $query->whereDate('date', '>=', $from);
        }
        if ($to) {
            $query->whereDate('date', '<=', $to);
        }

        $total   = $query->count();
        $present = (clone $query)->whereIn('status', ['present', 'late'])->count();

        return $total > 0 ? round($present / $total * 100, 2) : 0.0;
    }
}
