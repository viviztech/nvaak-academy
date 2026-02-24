<?php

namespace App\Livewire\Admin\Exams;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\Batch;
use App\Models\Subject;
use App\Services\ExamGradingService;

class RankList extends Component
{
    use WithPagination;

    public int    $examId;
    public ?Exam  $exam = null;
    public string $batchFilter = '';
    public string $passFail    = '';
    public int    $perPage     = 50;

    public function mount(int $examId): void
    {
        $this->examId = $examId;
        $this->exam   = Exam::with(['batch', 'subject'])->findOrFail($examId);
    }

    public function updatingBatchFilter(): void
    {
        $this->resetPage();
    }

    public function updatingPassFail(): void
    {
        $this->resetPage();
    }

    public function generateRanks(): void
    {
        $gradingService = app(ExamGradingService::class);
        $gradingService->generateRankList($this->examId);
        session()->flash('success', 'Rank list generated successfully.');
    }

    public function publishResults(): void
    {
        ExamResult::where('exam_id', $this->examId)->update(['is_published' => true]);
        session()->flash('success', 'Results published to students.');
    }

    public function render()
    {
        $results = ExamResult::query()
            ->with(['student.user', 'batch'])
            ->where('exam_id', $this->examId)
            ->when($this->batchFilter, fn ($q) => $q->where('batch_id', $this->batchFilter))
            ->when($this->passFail, fn ($q) => $q->where('pass_fail', $this->passFail))
            ->orderBy('rank_overall')
            ->paginate($this->perPage);

        $batches = Batch::whereHas('studentExams', fn ($q) => $q->where('exam_id', $this->examId))
                        ->orWhere('id', $this->exam->batch_id)
                        ->orderBy('name')
                        ->get();

        $subjectIds = [];
        if ($this->exam->results()->exists()) {
            $first = $this->exam->results()->first();
            $subjectIds = array_keys($first->subject_wise_scores ?? []);
        }
        $subjects = Subject::whereIn('id', $subjectIds)->get()->keyBy('id');

        $stats = [
            'total'    => ExamResult::where('exam_id', $this->examId)->count(),
            'pass'     => ExamResult::where('exam_id', $this->examId)->where('pass_fail', 'pass')->count(),
            'fail'     => ExamResult::where('exam_id', $this->examId)->where('pass_fail', 'fail')->count(),
            'avg_marks'=> ExamResult::where('exam_id', $this->examId)->avg('marks_obtained'),
            'max_marks'=> ExamResult::where('exam_id', $this->examId)->max('marks_obtained'),
        ];

        return view('livewire.admin.exams.rank-list', [
            'results'  => $results,
            'batches'  => $batches,
            'subjects' => $subjects,
            'stats'    => $stats,
        ])->layout('layouts.admin', ['title' => 'Rank List - ' . $this->exam->name]);
    }
}
