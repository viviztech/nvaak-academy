<?php

namespace App\Livewire\Teacher;

use Livewire\Component;
use App\Models\Batch;
use App\Models\Attendance;
use App\Models\SyllabusChapter;
use App\Models\SyllabusCoverage;
use App\Models\IASSubmission;
use App\Models\Exam;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{
    public function render()
    {
        $user   = Auth::user();
        $faculty = $user->faculty ?? null;

        $todaysBatches       = collect();
        $syllabusProgress    = collect();
        $pendingEvaluations  = 0;
        $upcomingExams       = collect();
        $attendanceSummary   = collect();

        if ($faculty) {
            // Batches assigned to this faculty
            $batches = Batch::where('faculty_id', $faculty->id)
                ->where('is_active', true)
                ->get();

            $batchIds = $batches->pluck('id');

            // Today's scheduled batches (simple date-based; adjust to schedule model if available)
            $todaysBatches = $batches->take(5);

            // Syllabus coverage per batch
            $syllabusProgress = $batches->map(function ($batch) {
                $total   = SyllabusChapter::where('batch_id', $batch->id)->count();
                $covered = SyllabusCoverage::where('batch_id', $batch->id)
                    ->where('is_covered', true)
                    ->count();
                return [
                    'batch'   => $batch,
                    'total'   => $total,
                    'covered' => $covered,
                    'pct'     => $total > 0 ? round(($covered / $total) * 100) : 0,
                ];
            });

            // Pending IAS evaluations assigned to this faculty
            $pendingEvaluations = IASSubmission::where('evaluator_id', $faculty->id)
                ->where('status', 'pending')
                ->count();

            // Upcoming exams for this faculty's batches
            $upcomingExams = Exam::where('status', 'published')
                ->whereIn('batch_id', $batchIds)
                ->where('start_time', '>', now())
                ->orderBy('start_time')
                ->take(5)
                ->get();

            // Today's attendance status per batch
            $attendanceSummary = $batches->map(function ($batch) {
                $marked = Attendance::where('batch_id', $batch->id)
                    ->whereDate('date', Carbon::today())
                    ->exists();
                return [
                    'batch'  => $batch,
                    'marked' => $marked,
                ];
            });
        }

        return view('livewire.teacher.dashboard', compact(
            'todaysBatches',
            'syllabusProgress',
            'pendingEvaluations',
            'upcomingExams',
            'attendanceSummary',
            'faculty'
        ))->layout('layouts.teacher', ['title' => 'Teacher Dashboard']);
    }
}
