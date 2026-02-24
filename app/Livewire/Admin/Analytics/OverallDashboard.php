<?php

namespace App\Livewire\Admin\Analytics;

use App\Models\Attendance;
use App\Models\Batch;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\Student;
use Livewire\Component;

class OverallDashboard extends Component
{
    public function getStatsProperty(): array
    {
        $totalStudents = Student::where('is_active', true)->count();

        $avgAttendance = 0;
        if ($totalStudents > 0) {
            $total   = \DB::table('attendances')->count();
            $present = \DB::table('attendances')->whereIn('status', ['present', 'late'])->count();
            $avgAttendance = $total > 0 ? round($present / $total * 100, 1) : 0;
        }

        $avgExamScore = ExamResult::where('is_published', true)->avg('percentage') ?? 0;

        $examCount = Exam::where('status', 'published')->count();

        return [
            'total_students' => $totalStudents,
            'avg_attendance'  => round($avgAttendance, 1),
            'avg_exam_score'  => round($avgExamScore, 1),
            'exams_conducted' => $examCount,
        ];
    }

    public function getBatchPerformanceProperty()
    {
        return Batch::active()
            ->withCount('students')
            ->get()
            ->map(function ($batch) {
                $avgScore = ExamResult::where('batch_id', $batch->id)
                    ->where('is_published', true)
                    ->avg('percentage') ?? 0;

                $passRate = ExamResult::where('batch_id', $batch->id)
                    ->where('is_published', true)
                    ->where('percentage', '>=', 50)
                    ->count();

                $totalResults = ExamResult::where('batch_id', $batch->id)
                    ->where('is_published', true)
                    ->count();

                $passRatePct = $totalResults > 0 ? round($passRate / $totalResults * 100, 1) : 0;

                $topScorer = ExamResult::where('batch_id', $batch->id)
                    ->where('is_published', true)
                    ->selectRaw('student_id, AVG(percentage) as avg_pct')
                    ->groupBy('student_id')
                    ->orderByDesc('avg_pct')
                    ->with('student.user')
                    ->first();

                return [
                    'batch'          => $batch,
                    'avg_score'      => round($avgScore, 1),
                    'pass_rate'      => $passRatePct,
                    'students_count' => $batch->students_count,
                    'top_scorer'     => $topScorer?->student?->user?->name ?? 'N/A',
                    'total_results'  => $totalResults,
                ];
            });
    }

    public function getRecentResultsProperty()
    {
        return ExamResult::with(['student.user', 'exam'])
            ->where('is_published', true)
            ->latest()
            ->limit(10)
            ->get();
    }

    public function render()
    {
        return view('livewire.admin.analytics.overall-dashboard')
            ->layout('layouts.admin', ['title' => 'Analytics Dashboard']);
    }
}
