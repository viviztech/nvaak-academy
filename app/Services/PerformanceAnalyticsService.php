<?php

namespace App\Services;

use App\Models\ExamResult;
use App\Models\Attendance;
use App\Models\StudentPerformanceSnapshot;
use App\Models\Student;
use Carbon\Carbon;

class PerformanceAnalyticsService
{
    public function generateSnapshot(Student $student, int $batchId): StudentPerformanceSnapshot
    {
        $results = ExamResult::where('student_id', $student->id)
            ->where('batch_id', $batchId)
            ->where('is_published', true)
            ->with('exam.questions.subject')
            ->get();

        $subjectScores = [];
        $totalPct      = 0;

        if ($results->count() > 0) {
            foreach ($results as $result) {
                if ($result->subject_wise_scores) {
                    foreach ($result->subject_wise_scores as $ss) {
                        $sid = $ss['subject_id'] ?? null;
                        if (! $sid) {
                            continue;
                        }
                        if (! isset($subjectScores[$sid])) {
                            $subjectScores[$sid] = ['name' => $ss['name'] ?? '', 'total_marks' => 0, 'marks_obtained' => 0];
                        }
                        $subjectScores[$sid]['total_marks']    += $ss['total'] ?? 0;
                        $subjectScores[$sid]['marks_obtained'] += $ss['marks'] ?? 0;
                    }
                }
            }
            $totalPct = $results->avg('percentage');
        }

        $subjectScoresArr = collect($subjectScores)->map(fn ($ss, $sid) => [
            'subject_id' => $sid,
            'name'       => $ss['name'],
            'percentage' => $ss['total_marks'] > 0 ? round($ss['marks_obtained'] / $ss['total_marks'] * 100, 1) : 0,
        ])->values()->toArray();

        $attendancePct = Attendance::getAttendancePercentage($student->id, $batchId);

        $rank = ExamResult::where('batch_id', $batchId)
            ->where('is_published', true)
            ->selectRaw('student_id, AVG(percentage) as avg_pct')
            ->groupBy('student_id')
            ->orderByDesc('avg_pct')
            ->pluck('student_id')
            ->search($student->id);

        $rankInBatch = $rank !== false ? $rank + 1 : null;

        $strongChapters = [];
        $weakChapters   = [];

        $last = StudentPerformanceSnapshot::where('student_id', $student->id)
            ->where('batch_id', $batchId)
            ->where('snapshot_type', 'weekly')
            ->latest('snapshot_date')
            ->first();

        $improvement = $last ? round($totalPct - $last->overall_score_percent, 2) : null;

        return StudentPerformanceSnapshot::updateOrCreate(
            [
                'student_id'    => $student->id,
                'batch_id'      => $batchId,
                'snapshot_date' => today(),
                'snapshot_type' => 'weekly',
            ],
            [
                'overall_score_percent' => round($totalPct, 2),
                'rank_in_batch'         => $rankInBatch,
                'subject_scores'        => $subjectScoresArr,
                'attendance_percent'    => $attendancePct,
                'exams_attempted'       => $results->count(),
                'improvement_from_last' => $improvement,
                'strong_chapters'       => $strongChapters,
                'weak_chapters'         => $weakChapters,
            ]
        );
    }
}
