<?php

namespace App\Livewire\Admin\Analytics;

use App\Models\Batch;
use App\Models\Student;
use App\Models\StudentPerformanceSnapshot;
use App\Services\RankPredictionService;
use Livewire\Component;

class StudentRankPredictor extends Component
{
    public string $studentId = '';
    public string $batchId   = '';

    public function getStudentsProperty()
    {
        return Student::with('user')->whereHas('user')->get()
            ->sortBy(fn ($s) => $s->user?->name);
    }

    public function getBatchesProperty()
    {
        return Batch::active()->orderBy('name')->get();
    }

    public function getLatestSnapshotProperty(): ?StudentPerformanceSnapshot
    {
        if (! $this->studentId || ! $this->batchId) {
            return null;
        }

        return StudentPerformanceSnapshot::where('student_id', $this->studentId)
            ->where('batch_id', $this->batchId)
            ->latest('snapshot_date')
            ->first();
    }

    public function getPredictionProperty(): array
    {
        $snapshot = $this->latestSnapshot;
        if (! $snapshot) {
            return ['score' => null, 'rank' => null, 'label' => 'No data available'];
        }

        $service     = app(RankPredictionService::class);
        $neetScore   = $service->predictNeetScore($snapshot->overall_score_percent);
        $rankPred    = $service->predictNeetRank($neetScore);

        return [
            'score' => $neetScore,
            'rank'  => $rankPred,
        ];
    }

    public function render()
    {
        return view('livewire.admin.analytics.student-rank-predictor')
            ->layout('layouts.admin', ['title' => 'NEET Rank Predictor']);
    }
}
