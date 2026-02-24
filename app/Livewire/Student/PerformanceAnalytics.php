<?php

namespace App\Livewire\Student;

use App\Models\ExamResult;
use App\Models\Student;
use App\Models\StudentPerformanceSnapshot;
use App\Services\RankPredictionService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PerformanceAnalytics extends Component
{
    public ?Student $student             = null;
    public ?StudentPerformanceSnapshot $snapshot = null;
    public array $prediction             = [];

    public function mount(): void
    {
        $this->student = Student::where('user_id', Auth::id())
            ->with('batches')
            ->first();

        if ($this->student) {
            $batch = $this->student->batches()
                ->where('batch_students.status', 'active')
                ->first();

            if ($batch) {
                $this->snapshot = StudentPerformanceSnapshot::where('student_id', $this->student->id)
                    ->where('batch_id', $batch->id)
                    ->latest('snapshot_date')
                    ->first();

                if ($this->snapshot) {
                    $service          = app(RankPredictionService::class);
                    $neetScore        = $service->predictNeetScore($this->snapshot->overall_score_percent);
                    $rankPred         = $service->predictNeetRank($neetScore);
                    $this->prediction = ['score' => $neetScore, 'rank' => $rankPred];
                }
            }
        }
    }

    public function getExamHistoryProperty()
    {
        if (! $this->student) {
            return collect();
        }

        return ExamResult::where('student_id', $this->student->id)
            ->where('is_published', true)
            ->with('exam')
            ->latest()
            ->limit(10)
            ->get();
    }

    public function render()
    {
        return view('livewire.student.performance-analytics')
            ->layout('layouts.student', ['title' => 'My Performance']);
    }
}
